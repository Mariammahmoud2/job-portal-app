<?php

namespace App\Http\Controllers;

use App\Models\job_vacancie;
use App\Models\job_application;
use App\Models\resume;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;
use Gemini\Laravel\Facades\Gemini;
use Spatie\PdfToText\Pdf;

class JobController extends Controller
{
    
    public function show(job_vacancie $job)
    {
        $job->load('company');
        return view('jobs.show', compact('job'));
    }

     
    public function showApplyForm(job_vacancie $job)
    {
        $application = job_application::where('user_id', Auth::id())
                                      ->where('job_vacancy_id', $job->id)
                                      ->with('resume')
                                      ->first();

        return view('jobs.apply', compact('job', 'application'));
    }

     public function submitApplication(Request $request, job_vacancie $job): RedirectResponse
    {
        $request->validate([
            'resume_file' => 'required|file|mimes:pdf|max:5120',
        ]);

        if ($request->hasFile('resume_file')) {
            $file     = $request->file('resume_file');
            $fileName = 'resume_' . time() . '.' . $file->getClientOriginalExtension();

             $path = $file->storeAs(path: 'resumes', name: $fileName, options: 's3');

             $tempFile   = tempnam(sys_get_temp_dir(), 'resume');
            $pdfContent = Storage::disk('s3')->get($path);
            file_put_contents($tempFile, $pdfContent);

             $pdfToTextPaths     = ['/opt/homebrew/bin/pdftotext', '/usr/bin/pdftotext', '/usr/local/bin/pdftotext'];
            $pdfToTextAvailable = false;
            foreach ($pdfToTextPaths as $p) {
                if (file_exists($p)) {
                    $pdfToTextAvailable = true;
                    break;
                }
            }
            if (!$pdfToTextAvailable) {
                throw new \Exception('pdf-to-text is not installed');
            }

             $rawText = (new Pdf())->setPdf($tempFile)->text();
            unlink($tempFile);

             $extractPrompt = "
                You are a precise resume parser.
                Extract information exactly as it appears in the resume without adding any interpretation or additional content.
                Parse the following resume content and extract the information as a JSON object with the exact keys:
                'summary', 'skills', 'experience', 'education'.
                - summary: string
                - skills: array of strings
                - experience: array of objects (title, company, duration, description)
                - education: array of objects (degree, institution, year)
                Return ONLY the JSON object, no explanation.

                Resume:
                {$rawText}
            ";

            $extractResponse = Gemini::generativeModel(model: 'models/gemini-2.5-flash')
                ->generateContent($extractPrompt);

            $extractJson = preg_replace('/```json|```/', '', $extractResponse->text());
            $parsed      = json_decode(trim($extractJson), true);
 
            $skillsText = $this->formatSkills($parsed['skills'] ?? []);
            $experienceText = $this->formatExperience($parsed['experience'] ?? []);
            $educationText = $this->formatEducation($parsed['education'] ?? []);

            $newResume = resume::create([
                'file_name'  => $file->getClientOriginalName(),
                'file_url'   => $path,
                'user_id'    => Auth::id(),
                'content'    => $rawText,
                'summary'    => $parsed['summary'] ?? '',
                'skills'     => $skillsText,
                'experience' => $experienceText,
                'education'  => $educationText,
            ]);

             $scorePrompt = "
                You are an expert HR evaluator.
                Compare the following resume with the job vacancy and give a score from 0 to 10 and a short feedback.
                Return ONLY a JSON object with keys: 'score' (float) and 'feedback' (string).

                Job Title: {$job->title}
                Job Description: {$job->description}

                Resume Summary: {$newResume->summary}
                Resume Skills: {$skillsText}
                Resume Experience: {$experienceText}

                Return ONLY the JSON object, no explanation.
            ";

            $scoreResponse = Gemini::generativeModel(model: 'models/gemini-2.5-flash')
                ->generateContent($scorePrompt);

            $scoreJson = preg_replace('/```json|```/', '', $scoreResponse->text());
            $scored    = json_decode(trim($scoreJson), true);

            $aiScore    = $scored['score']    ?? 0;
            $aiFeedback = $scored['feedback'] ?? 'No feedback available';

             $existing = job_application::where('user_id', Auth::id())
                                       ->where('job_vacancy_id', $job->id)
                                       ->first();

            if ($existing) {
                DB::statement("
                    UPDATE job_applications
                    SET
                        resume_id = ?,
                        status = ?,
                        `ai generated score` = ?,
                        `ai generated feedback` = ?,
                        updated_at = NOW()
                    WHERE id = ?
                ", [
                    $newResume->id,
                    'pending',
                    $aiScore,
                    $aiFeedback,
                    $existing->id,
                ]);
            } else {
                DB::statement("
                    INSERT INTO job_applications
                        (id, user_id, job_vacancy_id, resume_id, status, `ai generated score`, `ai generated feedback`, created_at, updated_at)
                    VALUES
                        (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
                ", [
                    Str::uuid(),
                    Auth::id(),
                    $job->id,
                    $newResume->id,
                    'pending',
                    $aiScore,
                    $aiFeedback,
                ]);
            }
        }

        return redirect()->route('job-applications.index')
                         ->with('success', 'تم رفع السيرة الذاتية بنجاح!');
    }

     public function deleteApplication(job_vacancie $job): RedirectResponse
    {
        $application = job_application::where('user_id', Auth::id())
                                      ->where('job_vacancy_id', $job->id)
                                      ->first();

        if ($application) {
            $application->delete();
            return back()->with('success', 'تم حذف الطلب.');
        }
        return back()->with('error', 'الطلب غير موجود.');
    }

     
    private function formatSkills(array $skills): string
    {
        if (empty($skills)) {
            return '';
        }

        return implode(', ', array_filter($skills, fn($skill) => !empty($skill)));
    }
 
    private function formatExperience(array $experiences): string
    {
        if (empty($experiences)) {
            return '';
        }

        $formatted = [];
        
        foreach ($experiences as $exp) {
            $title = $exp['title'] ?? '';
            $company = $exp['company'] ?? 'Self-employed';
            $duration = $exp['duration'] ?? '';
            $description = $exp['description'] ?? '';

            $line = trim("{$title}");
            
            if (!empty($company)) {
                $line .= " at {$company}";
            }
            
            if (!empty($duration)) {
                $line .= " ({$duration})";
            }
            
            if (!empty($description)) {
                $line .= " - {$description}";
            }

            if (!empty(trim($line))) {
                $formatted[] = $line;
            }
        }

        return implode("\n", $formatted);
    }

     
    private function formatEducation(array $education): string
    {
        if (empty($education)) {
            return '';
        }

        $formatted = [];
        
        foreach ($education as $edu) {
            $degree = $edu['degree'] ?? '';
            $institution = $edu['institution'] ?? '';
            $year = $edu['year'] ?? '';

            $line = trim($degree);
            
            if (!empty($institution)) {
                $line .= " from {$institution}";
            }
            
            if (!empty($year)) {
                $line .= " ({$year})";
            }

            if (!empty(trim($line))) {
                $formatted[] = $line;
            }
        }

        return implode("\n", $formatted);
    }
}