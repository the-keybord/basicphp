<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = ['name', 'default_test_size', 'default_test_time'];

    public function subcategories(): HasMany
    {
        return $this->hasMany(Subcategory::class);
    }
}
