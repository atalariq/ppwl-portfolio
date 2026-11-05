<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    protected $fillable = ['role', 'organization', 'period', 'description', 'sort_order'];
}
