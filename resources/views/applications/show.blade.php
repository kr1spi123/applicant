<!DOCTYPE html>
<html>
<head>
    <title>Application Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h1>Application #{{ $application->id }}</h1>
    <a href="{{ route('applications.index') }}" class="btn btn-secondary mb-3">Back</a>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $application->specialty->name }}</h5>
            <p class="card-text">
                <strong>Status:</strong> {{ $application->status }}<br>
                <strong>Your Rating:</strong> {{ $application->rating }}<br>
                <strong>Current Position in Queue:</strong> {{ $position }}
            </p>
            @if($application->qr_code_path)
                <a href="{{ Storage::url($application->qr_code_path) }}" class="btn btn-primary" target="_blank">Download Official PDF</a>
            @else
                <button class="btn btn-secondary" disabled>PDF Generating...</button>
            @endif
        </div>
    </div>
</body>
</html>
