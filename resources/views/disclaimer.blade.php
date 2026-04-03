@extends('layouts.app')

@section('content')
<section class="info-page info-page--disclaimer">
    <div class="info-page__inner">
        <header class="info-page__hero">
            <p class="info-page__kicker">Read First</p>
            <h1 class="info-page__title">{{ $disclaimerPage->title }}</h1>
        </header>

        <div class="info-page__panel static-page__body">
            @php
                $paragraphs = preg_split('/\r\n\r\n|\n\n|\r\r/', trim((string) $disclaimerPage->content)) ?: [];
            @endphp

            <div class="disclaimer-copy">
                @foreach ($paragraphs as $paragraph)
                    @if (trim($paragraph) !== '')
                        <p>{{ $paragraph }}</p>
                    @endif
                @endforeach
            </div>

            <p class="disclaimer-note">{{ $disclaimerNote->content }}</p>
        </div>

        @if (auth()->check() && auth()->user()->is_admin)
            <p style="margin-top: 1rem;">
                <a href="{{ route('admin.pages.disclaimer.edit') }}" style="color: #c56a7f; font-weight: 700;">Edit this page</a>
            </p>
        @endif
    </div>
</section>
@endsection
