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

                    <a href="{{ route('posts.export.excel') }}"
                        class="btn btn-info btn-add">
                        📊 Export Excel
                    </a>

                    <a href="{{ route('posts.create') }}"
                        class="btn btn-light btn-add">
                        + Add Post
                    </a>

                </div>

            </div>
        </div>


        <!-- Analytics Dashboard -->
        <div class="row mb-4">

            <div class="col-md-3 mb-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <h6 class="text-muted">Total Posts</h6>
                        <h2 class="fw-bold">{{ $totalPosts }}</h2>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <h6 class="text-muted">English</h6>
                        <h2 class="fw-bold text-primary">
                            {{ $englishCount }}
                        </h2>
                        <small>Translated Posts</small>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <h6 class="text-muted">Hindi</h6>
                        <h2 class="fw-bold text-warning">
                            {{ $hindiCount }}
                        </h2>
                        <small>Translated Posts</small>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <h6 class="text-muted">Gujarati</h6>
                        <h2 class="fw-bold text-success">
                            {{ $gujaratiCount }}
                        </h2>
                        <small>Translated Posts</small>
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
                                <th>Status</th>
                                <th width="250">Action</th>
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

                                    @if($post->translationStatus() == 'Complete')

                                    <span class="badge bg-success">
                                        Complete
                                    </span>

                                    @else

                                    <span class="badge bg-danger">
                                        {{ $post->translationStatus() }}
                                    </span>

                                    @endif

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
                                <td colspan="6" class="text-center py-4">
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