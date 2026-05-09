<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class job_vacancie extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $table = 'job_vacancies';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'title',
        'description',
        'location',
        'salary',
        'type',
        'company_id',
        'category_id',
        'view_count'
    ];

    protected function casts(): array
    {
        return [
            'deleted_at' => 'datetime',
        ];
    }

    public function company()
    {
        return $this->belongsTo(company::class, 'company_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(job_categorie::class, 'category_id', 'id');
    }

    public function applications()
    {
        return $this->hasMany(job_application::class, 'job_vacancy_id');
    }
}