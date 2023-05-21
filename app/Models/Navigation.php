<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Navigation extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function subMenus(): HasMany
    {
        return $this->hasMany(Navigation::class, 'main_menu');
    }
}
