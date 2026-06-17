# PHP_Laravel12_Lexi_Translate


## Project Description

PHP_Laravel12_Lexi_Translate is a multilingual post management system built with Laravel 12 and the Lexi Translate package. The application allows users to create, update, delete, search, and manage posts in multiple languages, including English, Hindi, and Gujarati.

The project demonstrates locale-based content translation, dynamic language switching, translation search functionality, pagination, and a modern Bootstrap 5 user interface for managing multilingual content efficiently.


## Key Features

- Multi-language Post Management
- English, Hindi, and Gujarati Translations
- Dynamic Language Switching
- Create, Edit, Update, and Delete Posts
- Translation-based Search Functionality
- Pagination Support
- Locale Middleware Integration
- Lexi Translate Package Integration
- Responsive Bootstrap 5 Dashboard
- Translation Storage and Retrieval
- Clean Laravel Resource Routing
- Modern User-Friendly Interface



## Technologies Used

- Laravel 12
- PHP 8+
- MySQL
- Bootstrap 5
- HTML5
- CSS3
- Lexi Translate Package
- Eloquent ORM


## Supported Languages

- English (EN)
- Hindi (HI)
- Gujarati (GU)



## Application Flow

1. Open Posts Dashboard
2. Select Preferred Language
3. Create a New Post
4. Add English, Hindi, and Gujarati Content
5. Save Translation Records
6. View Posts in Selected Locale
7. Search Posts by Translation
8. Edit or Delete Existing Posts
9. Navigate Records Using Pagination

---



## Installation Steps


---


## STEP 1: Create Laravel 12 Project

### Open terminal / CMD and run:

```
composer create-project laravel/laravel PHP_Laravel12_Lexi_Translate "12.*"

```

### Go inside project:

```
cd PHP_Laravel12_Lexi_Translate

```

#### Explanation:

Creates a fresh Laravel 12 application and installs all required framework dependencies.

This project serves as the foundation for building a multilingual content management system using Lexi Translate.



## STEP 2: Database Setup 

### Update database details:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel12_lexi_translate
DB_USERNAME=root
DB_PASSWORD=

```

### Create database in MySQL / phpMyAdmin:

```
Database name: laravel12_lexi_translate


```

### Then Run:

```
php artisan migrate

```


#### Explanation:

Configures the MySQL database connection for storing posts and translation records.

Laravel uses this database to manage multilingual content and application data.





## STEP 3: Install Lexi Translate Package

### Run:

```
composer require omaralalwi/lexi-translate

php artisan vendor:publish --tag=lexi-translate

php artisan vendor:publish --tag=lexi-migrations

```
 
### Then Run:

```
php artisan migrate

```


#### Explanation: 

Installs the Lexi Translate package and publishes its configuration and migration files.

This package enables multilingual content management with locale-based translations.




## STEP 4: Create Post Module

### Run:

```
php artisan make:model Post -m

```

### database/migrations/xxxx_create_posts_table.php

```
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {

            $table->id();

            $table->string('slug');

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};

```

### app/Models/Post.php

```
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

```


### Then Run:

```
php artisan migrate

```


#### Explanation: 

Creates the Post model and database table for storing post information.

The model is configured with Lexi Translate to support translated fields such as title and description.




## STEP 5: Create Controller

### Run:

```
php artisan make:controller PostController --resource

```

### app/Http/Controllers/PostController.php

```
<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of posts.
     */
   public function index(Request $request)
    {
        $posts = Post::query();

        if ($request->filled('search')) {

            $search = $request->search;

            $posts->where('slug', 'like', "%{$search}%")
                ->orWhereHas('translations', function ($query) use ($search) {

                    $query->where('text', 'like', "%{$search}%");

                });
        }

        $posts = $posts->oldest()->paginate(4);

        return view('posts.index', compact('posts'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store post.
     */
    public function store(Request $request)
    {
        $request->validate([
            'slug' => 'required|string|max:255',
            'title_en' => 'required',
            'description_en' => 'required',
        ]);

        $post = Post::create([
            'slug' => $request->slug
        ]);

        $post->setTranslations([
            'en' => [
                'title' => $request->title_en,
                'description' => $request->description_en,
            ],

            'hi' => [
                'title' => $request->title_hi,
                'description' => $request->description_hi,
            ],

            'gu' => [
                'title' => $request->title_gu,
                'description' => $request->description_gu,
            ]
        ]);

        return redirect()
            ->route('posts.index')
            ->with('success', 'Post Created Successfully');
    }

    /**
     * Show single post.
     */
    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    /**
     * Show edit form.
     */
    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    /**
     * Update post.
     */
    public function update(Request $request, Post $post)
    {
        $post->update([
            'slug' => $request->slug
        ]);

        if (
            $request->filled('title_en') &&
            $request->filled('description_en')
        ) {

            $post->setTranslations([
                'en' => [
                    'title' => $request->title_en,
                    'description' => $request->description_en,
                ],
                'hi' => [
                    'title' => $request->title_hi ?? '',
                    'description' => $request->description_hi ?? '',
                ],
                'gu' => [
                    'title' => $request->title_gu ?? '',
                    'description' => $request->description_gu ?? '',
                ],
            ]);
        }

        return redirect()
            ->route('posts.index')
            ->with('success', 'Post Updated Successfully');
    }

    /**
     * Delete post.
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()
            ->route('posts.index')
            ->with('success', 'Post Deleted Successfully');
    }
}

```

#### Explanation: 

Handles all post-related operations including create, update, delete, search, and translation management.

The controller acts as the bridge between the user interface and application data.





## STEP 6: Add Routes

### routes/web.php

```
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

Route::get('/', function () {
    return redirect()->route('posts.index');
});

Route::resource('posts', PostController::class);

Route::get('/lang/{locale}', function ($locale) {

    session()->put('locale', $locale);

    return redirect()->route('posts.index');

})->name('lang.switch');

```

#### Explanation: 

Defines application URLs and maps them to controller actions.

Routes manage post operations and language switching functionality.



## STEP 7: Create Middleware

### Run:

```
php artisan make:middleware SetLocale

```

### App\Http\Middleware\SetLocale.php

``` 
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next)
{
    app()->setLocale(
        session('locale', 'en')
    );

    return $next($request);
}
}

```


### Register in Laravel 12

#### bootstrap/app.php

```
->withMiddleware(function (Middleware $middleware): void {

    $middleware->web(append: [
        \App\Http\Middleware\SetLocale::class,
    ]);

})
   
```


#### Explanation: 

Creates a middleware that automatically sets the selected application language.

This ensures translated content is displayed according to the active locale.




## STEP 8: Create Blade Files

### resources/views/posts/index.blade.php

```
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Lexi Translate</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f7fc;
        }

        .hero-card {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .15);
        }

        .stat-card {
            border: none;
            border-radius: 18px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .08);
            transition: .3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .table-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, .08);
        }

        .search-box {
            border-radius: 12px;
            padding: 12px 18px;
        }

        .btn-add {
            border-radius: 12px;
            padding: 10px 20px;
            font-weight: 600;
        }

        .table tbody tr:hover {
            background: #f8fafc;
        }

        .action-btn {
            border-radius: 10px;
        }

        .badge-custom {
            background: #eef2ff;
            color: #4f46e5;
            font-size: 13px;
            padding: 6px 10px;
            border-radius: 20px;
        }

        .pagination {
            gap: 8px;
        }

        .pagination .page-link {
            border: none;
            border-radius: 12px;
            min-width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: #4f46e5;
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .08);
        }

        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
        }

        .pagination .page-link:hover {
            background: #eef2ff;
            color: #4f46e5;
        }

        .lang-btn {
            border-radius: 12px;
            font-weight: 600;
            min-width: 110px;
            transition: all .3s ease;
        }

        .lang-btn:hover {
            transform: translateY(-2px);
        }
    </style>
</head>

<body>

    <div class="container py-4">

        <!-- Header -->
        <div class="hero-card">
            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <h2 class="fw-bold mb-2">
                        🌍 Laravel Lexi Translate
                    </h2>

                    <p class="mb-0">
                        Multilingual Post Management Dashboard
                    </p>
                </div>

                <div class="d-flex gap-2">

                    <a href="{{ route('lang.switch', 'en') }}"
                        class="btn {{ app()->getLocale() == 'en' ? 'btn-primary' : 'btn-light' }} lang-btn">
                        ENGLISH
                    </a>

                    <a href="{{ route('lang.switch', 'hi') }}"
                        class="btn {{ app()->getLocale() == 'hi' ? 'btn-warning' : 'btn-light' }} lang-btn">
                        HINDI
                    </a>

                    <a href="{{ route('lang.switch', 'gu') }}"
                        class="btn {{ app()->getLocale() == 'gu' ? 'btn-success' : 'btn-light' }} lang-btn">
                        GUJARATI
                    </a>

                    <a href="{{ route('posts.create') }}" class="btn btn-light btn-add">
                        + Add Post
                    </a>

                </div>

            </div>
        </div>

        <!-- Stats -->
        <div class="row mb-4">

            <div class="col-md-4 mb-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <h6 class="text-muted">Total Posts</h6>
                        <h2 class="fw-bold">
                            {{ $posts->total() }}
                        </h2>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <h6 class="text-muted">Languages</h6>
                        <h2 class="fw-bold">3</h2>
                        <small>English • Hindi • Gujarati</small>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <h6 class="text-muted">Current Locale</h6>
                        <h2 class="fw-bold">
                            {{ strtoupper(app()->getLocale()) }}
                        </h2>
                    </div>
                </div>
            </div>

        </div>

        <!-- Table Section -->
        <div class="card table-card">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <h4 class="mb-0">
                        Posts Management
                    </h4>

                    <span class="badge-custom">
                        {{ $posts->count() }} Records
                    </span>

                </div>

                <form method="GET" action="{{ route('posts.index') }}">

                    <input type="text" name="search" value="{{ request('search') }}"
                        class="form-control search-box mb-4" placeholder="🔍 Search Translation...">

                </form>

                <div class="table-responsive">

                    <table class="table align-middle">

                        <thead class="table-light">

                            <tr>
                                <th>ID</th>
                                <th>Slug</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th width="180">Action</th>
                            </tr>

                        </thead>

                        <tbody>

                            @forelse($posts as $post)

                                <tr>

                                    <td>
                                        <strong>#{{ $post->id }}</strong>
                                    </td>

                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ $post->slug }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ $post->transAttr('title') }}
                                    </td>

                                    <td>
                                        {{ Str::limit($post->transAttr('description'), 50) }}
                                    </td>

                                    <td>

                                        <a href="{{ route('posts.edit', $post->id) }}"
                                            class="btn btn-warning btn-sm action-btn">
                                            Edit
                                        </a>

                                        <form action="{{ route('posts.destroy', $post->id) }}" method="POST"
                                            class="d-inline">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-danger btn-sm action-btn"
                                                onclick="return confirm('Delete this post?')">
                                                Delete
                                            </button>

                                        </form>

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        No Posts Found
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

                <div class="d-flex justify-content-center mt-4">

                    @if($posts->lastPage() > 1)

                        <nav>

                            <ul class="pagination">

                                @for($i = 1; $i <= $posts->lastPage(); $i++)

                                    <li class="page-item {{ $posts->currentPage() == $i ? 'active' : '' }}">

                                        <a class="page-link" href="{{ $posts->url($i) }}">

                                            {{ $i }}

                                        </a>

                                    </li>

                                @endfor

                            </ul>

                        </nav>

                    @endif

                </div>

            </div>

        </div>

    </div>

</body>

</html>

```



### resources/views/posts/create.blade.php


```
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-md-8">

            <div class="card shadow border-0">

                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Create New Post</h3>
                </div>

                <div class="card-body">

                    <form method="POST" action="{{ route('posts.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Slug</label>
                            <input type="text" name="slug" class="form-control" placeholder="Enter Slug">
                        </div>

                        <hr>

                        <h5 class="text-primary">English</h5>

                        <div class="mb-3">
                            <input type="text" name="title_en" class="form-control" placeholder="English Title">
                        </div>

                        <div class="mb-3">
                            <textarea name="description_en" class="form-control" rows="3" placeholder="English Description"></textarea>
                        </div>

                        <h5 class="text-success">Hindi</h5>

                        <div class="mb-3">
                            <input type="text" name="title_hi" class="form-control" placeholder="Hindi Title">
                        </div>

                        <div class="mb-3">
                            <textarea name="description_hi" class="form-control" rows="3" placeholder="Hindi Description"></textarea>
                        </div>

                        <h5 class="text-warning">Gujarati</h5>

                        <div class="mb-3">
                            <input type="text" name="title_gu" class="form-control" placeholder="Gujarati Title">
                        </div>

                        <div class="mb-3">
                            <textarea name="description_gu" class="form-control" rows="3" placeholder="Gujarati Description"></textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button class="btn btn-primary">
                                Save Post
                            </button>

                            <a href="{{ route('posts.index') }}" class="btn btn-secondary">
                                Back
                            </a>
                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>

```

### resources/views/posts/edit.blade.php

```
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f4f7fc;
        }

        .form-card {
            border: none;
            border-radius: 25px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .12);
        }

        .card-header-custom {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            padding: 25px;
        }

        .form-control {
            border-radius: 12px;
            padding: 12px;
        }

        .section-title {
            padding: 10px 15px;
            border-radius: 12px;
            color: white;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .english {
            background: #4f46e5;
        }

        .hindi {
            background: #16a34a;
        }

        .gujarati {
            background: #f59e0b;
        }

        .btn-custom {
            border-radius: 12px;
            padding: 10px 25px;
            font-weight: 600;
        }
    </style>
</head>

<body style="background:#f4f7fc;">

    <div class="container py-5">

        <div class="row justify-content-center">

            <div class="col-md-8">

                <div class="card shadow border-0">

                    <div class="card-header bg-warning">
                        <h3 class="mb-0">Edit Post</h3>
                    </div>

                    <div class="card-body">

                        <form method="POST" action="{{ route('posts.update', $post->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">Slug</label>
                                <input type="text" name="slug" value="{{ $post->slug }}" class="form-control">
                            </div>

                            <div class="d-flex gap-2">
                                <button class="btn btn-warning">
                                    Update Post
                                </button>

                                <a href="{{ route('posts.index') }}" class="btn btn-secondary">
                                    Back
                                </a>
                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>

```

#### Explanation: 

Builds the user interface for managing multilingual posts using Bootstrap 5.

The views provide features such as translation management, search, pagination, and locale switching.






## STEP 9: Run the Application  

### Start dev server:

```
php artisan serve

```

### Open in browser:

```
http://127.0.0.1:8000/posts

```

#### Explanation:

Starts the Laravel development server for local testing and development.

Users can access the multilingual dashboard and manage translated content through the browser.



## Expected Output:

### Posts Dashboard (English)


<img width="1898" height="967" alt="Screenshot 2026-06-17 153430" src="https://github.com/user-attachments/assets/5e8245e3-e140-4d9c-a802-a3155433ecee" />


### Posts Dashboard (Hindi)


<img width="1895" height="965" alt="Screenshot 2026-06-17 153501" src="https://github.com/user-attachments/assets/6bdbffbe-040e-4904-bb9d-adea8b133c7a" />


### Posts Dashboard (Gujarati)


<img width="1890" height="970" alt="Screenshot 2026-06-17 153529" src="https://github.com/user-attachments/assets/bd0b6371-22cd-4423-9cec-b0cb50fc97af" />


### Create New Post


<img width="1886" height="962" alt="Screenshot 2026-06-17 153712" src="https://github.com/user-attachments/assets/f04eb2c0-9852-4bf0-ad0b-2be7d0c24cde" />


### Update Existing Post


<img width="1895" height="929" alt="Screenshot 2026-06-17 153737" src="https://github.com/user-attachments/assets/885f332a-0a1a-41f3-92e1-5fd1c9bbb2d9" />


### Delete Post Operation


<img width="1891" height="953" alt="Screenshot 2026-06-17 154025" src="https://github.com/user-attachments/assets/a6b4658a-bc84-4730-afaa-1a28b1708712" />


### Translation Search & Filtering


<img width="1903" height="950" alt="Screenshot 2026-06-17 154403" src="https://github.com/user-attachments/assets/3d886b1c-e21c-4992-9198-079eed08ae4f" />

<img width="1905" height="953" alt="Screenshot 2026-06-17 154455" src="https://github.com/user-attachments/assets/6b0b7501-2a2b-47c1-8b6d-f1d19d5557da" />

<img width="1909" height="955" alt="Screenshot 2026-06-17 154511" src="https://github.com/user-attachments/assets/f2796fc9-72d0-4f82-b190-1baaac799c04" />


### Pagination & Navigation


<img width="1890" height="956" alt="Screenshot 2026-06-17 154040" src="https://github.com/user-attachments/assets/508c0882-a1a5-44a5-bc4f-aaa79d6341b2" />



---


## Project Folder Structure

```
PHP_Laravel12_Lexi_Translate
│
├── app
│   ├── Http
│   │   ├── Controllers
│   │   │   └── PostController.php
│   │   └── Middleware
│   │       └── SetLocale.php
│   │
│   └── Models
│       └── Post.php
│
├── bootstrap
│   └── app.php
│
├── config
│   └── lexi-translate.php
│
├── database
│   ├── migrations
│   │   ├── xxxx_create_posts_table.php
│   │   ├── xxxx_create_translations_table.php
│   │   └── lexi_translate_package_migration.php
│   │
│   └── seeders
│
├── public
│
├── resources
│   └── views
│       └── posts
│           ├── index.blade.php
│           ├── create.blade.php
│           └── edit.blade.php
│
├── routes
│   └── web.php
│
├── storage
├── tests
├── vendor
│
├── .env
├── artisan
├── composer.json
├── composer.lock
├── package.json
├── README.md
└── vite.config.js
```

