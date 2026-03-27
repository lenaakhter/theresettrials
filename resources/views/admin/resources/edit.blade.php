@extends('layouts.admin')

@section('content')
<main class="admin-posts">
    <div class="admin-posts__header">
        <div>
            <h1 class="admin-posts__title">Edit Resource</h1>
            <p class="admin-posts__subtitle">Update product details and linked content.</p>
        </div>
        <a href="{{ route('admin.resources.index') }}" class="admin-btn admin-btn--secondary">← Back</a>
    </div>

    @if (session('status'))
        <div class="admin-flash admin-flash--success" data-flash>
            <span>{{ session('status') }}</span>
            <button type="button" class="admin-flash__close" data-flash-close aria-label="Dismiss">&times;</button>
        </div>
    @endif

    @if ($errors->any())
        <div class="admin-flash admin-flash--error" data-flash>
            <span>{{ $errors->first() }}</span>
            <button type="button" class="admin-flash__close" data-flash-close aria-label="Dismiss">&times;</button>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.resources.update', $resource) }}" class="admin-editor" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @include('admin.resources.partials.form', ['resource' => $resource])

        <button type="submit" class="admin-btn admin-btn--primary" style="margin-top:1rem;">Update Resource</button>
    </form>
</main>
@endsection
