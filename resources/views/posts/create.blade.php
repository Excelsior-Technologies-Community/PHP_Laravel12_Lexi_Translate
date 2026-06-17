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