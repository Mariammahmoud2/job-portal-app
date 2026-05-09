<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Facades\DB;

class job_application extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $table = 'job_applications';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'status',
        'job_vacancy_id',
        'user_id',
        'resume_id',
    ];

    protected function casts(): array
    {
        return [
            'deleted_at' => 'datetime',
        ];
    }

    // Accessor للـ score
    public function getAiGeneratedScoreAttribute()
    {
        $result = DB::select("SELECT `ai generated score` FROM job_applications WHERE id = ?", [$this->id]);
        return $result[0]->{'ai generated score'} ?? null;
    }

    // Accessor للـ feedback
    public function getAiGeneratedFeedbackAttribute()
    {
        $result = DB::select("SELECT `ai generated feedback` FROM job_applications WHERE id = ?", [$this->id]);
        return $result[0]->{'ai generated feedback'} ?? null;
    }

    public function jobVacancie()
    {
        return $this->belongsTo(job_vacancie::class, 'job_vacancy_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function resume()
    {
        return $this->belongsTo(resume::class, 'resume_id', 'id');
    }
}