<?php

namespace App\Exports;

use App\Models\Post;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PostsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Post::all()->map(function ($post) {

            return [
                $post->id,
                $post->slug,

                $post->transAttr('title', 'en'),
                $post->transAttr('description', 'en'),

                $post->transAttr('title', 'hi'),
                $post->transAttr('description', 'hi'),

                $post->transAttr('title', 'gu'),
                $post->transAttr('description', 'gu'),

                $post->translationStatus(),

                $post->created_at?->format('Y-m-d H:i:s'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Slug',

            'English Title',
            'English Description',

            'Hindi Title',
            'Hindi Description',

            'Gujarati Title',
            'Gujarati Description',

            'Translation Status',

            'Created At',
        ];
    }
}