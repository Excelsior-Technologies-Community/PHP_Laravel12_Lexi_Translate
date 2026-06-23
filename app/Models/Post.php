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

    public function translationStatus()
    {
        $missing = [];

        $en = trim($this->transAttr('title', 'en') ?? '');
        $hi = trim($this->transAttr('title', 'hi') ?? '');
        $gu = trim($this->transAttr('title', 'gu') ?? '');

        if (!$en) {
            $missing[] = 'English';
        }

        if (!$hi) {
            $missing[] = 'Hindi';
        }

        if (!$gu) {
            $missing[] = 'Gujarati';
        }

        return empty($missing)
            ? 'Complete'
            : 'Missing: ' . implode(', ', $missing);
    }
}