<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class job_categorie extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $table = 'job_categories';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $dates = ['deleted_at'];
    protected $fillable = ['name'];

    protected function casts(): array
    {
        return [
            'deleted_at' => 'datetime',
        ];
    }

    public function jobVacancies()
    {
        return $this->hasMany(job_vacancie::class, 'category_id', 'id');
    }
}