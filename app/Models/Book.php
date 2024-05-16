<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'isbn',
        'value'
    ];

    public function stores(): BelongsToMany
    {
        return $this->belongsToMany(Store::class)->using(StoredBook::class);
    }
}
