<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    protected $table = 'educations';

    protected $fillable = ['institution', 'program', 'period', 'details', 'sort_order'];

    protected function casts(): array
    {
        return [
            'details' => 'array',
        ];
    }
}
