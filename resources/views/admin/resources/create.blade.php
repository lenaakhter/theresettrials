@extends('layouts.admin')

@section('content')
<main class="admin-posts">
    <div class="admin-posts__header">
        <div>
            <h1 class="admin-posts__title">Add Resource</h1>
            <p class="admin-posts__subtitle">Link a product to a blog post or experiment.</p>
        </div>
        <a href="{{ route('admin.resources.index') }}" class="admin-btn admin-btn--secondary">← Back</a>
    </div>

    @if ($errors->any())
        <div class="admin-flash admin-flash--error" data-flash>
            <span>{{ $errors->first() }}</span>
            <button type="button" class="admin-flash__close" data-flash-close aria-label="Dismiss">&times;</button>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.resources.store') }}" class="admin-editor" enctype="multipart/form-data">
        @csrf

        @include('admin.resources.partials.form', ['resource' => null])

        <button type="submit" class="admin-btn admin-btn--primary" style="margin-top:1rem;">Save Resource</button>
    </form>
</main>
@endsection
