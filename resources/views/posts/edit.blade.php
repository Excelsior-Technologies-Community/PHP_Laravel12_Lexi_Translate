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