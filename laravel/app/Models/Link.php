<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Link extends Model
{
    use HasFactory;

    public array $fillable = ['long_url', 'short_url', 'title', 'tags'];

    public function visitors(): HasMany
    {
        return $this->hasMany(Visitor::class);
    }
}
