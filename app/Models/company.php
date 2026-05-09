<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class company extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $table = 'companies';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $dates = ['deleted_at'];
    protected $fillable = ['name', 'address', 'industry', 'website', 'ownerid'];

    protected function casts(): array
    {
        return [
            'deleted_at' => 'datetime',
        ];
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'ownerid', 'id')->withTrashed();
    }

    public function jobVacancies()
    {
        return $this->hasMany(job_vacancie::class);
    }

    public function jobApplications()
    {
        return $this->hasManyThrough(
            job_application::class,
            job_vacancie::class,
            'company_id',
            'job_vacancy_id',
            'id',
            'id'
        );
    }
}