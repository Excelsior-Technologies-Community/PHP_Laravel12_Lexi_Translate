<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Omaralalwi\LexiTranslate\Traits\LexiTranslatable;

class Post extends Model
{
    use LexiTranslatable;

    protected $fillable = [
        'slug'
    ];

    protected $translatableFields = [
        'title',
        'description'
    ];
}