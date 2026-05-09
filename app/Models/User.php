<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Notifications\Notifiable;

#[Fillable(['id', 'name', 'email', 'password', 'role', 'last_login_at'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasUuids;

    protected $table = 'users';
    protected $keyType = 'string';
    public $incrementing = false;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'deleted_at' => 'datetime',
        ];
    }

    public function jobApplications()
    {
        return $this->hasMany(job_application::class, 'user_id', 'id');
    }

    public function resumes()
    {
        return $this->hasMany(resume::class, 'user_id', 'id');
    }

    public function companies()
    {
        return $this->hasOne(company::class, 'ownerid', 'id');
    }
}