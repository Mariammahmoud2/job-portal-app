<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class resume extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $table = 'resumes';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'file_url',
        'file_name',
        'content',
        'education',
        'summary',
        'skills',
        'experience',
        'user_id'
    ];

    protected function casts(): array
    {
        return [
            'deleted_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}