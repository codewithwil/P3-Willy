<?php

namespace App\Models\Resources\Category;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE   = 1;
    protected $table      = 'categories';
    protected $primaryKey = 'categoryId';
    protected $fillable   = [
        'name', 'status'
    ];
}