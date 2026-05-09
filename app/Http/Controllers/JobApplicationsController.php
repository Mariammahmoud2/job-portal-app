<?php

namespace App\Http\Controllers;

use App\Models\job_application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobApplicationsController extends Controller
{
    public function index()
    {
         $applications = job_application::where('user_id', Auth::id())
            ->with(['jobVacancie.company', 'resume'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('job_applications.index', compact('applications'));
    }
}