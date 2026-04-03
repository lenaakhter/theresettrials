@extends('layouts.app')

@section('content')
<section class="info-page info-page--about">
    <div class="info-page__inner">
        <header class="info-page__hero">
            <p class="info-page__kicker">The Reset Trials</p>
            <h1 class="info-page__title">{{ $aboutPage->title }}</h1>
        </header>

        <div class="static-page__body about-page__content">
            {!! nl2br(e($aboutPage->content)) !!}
        </div>

        @if (auth()->check() && auth()->user()->is_admin)
            <p style="margin-top: 1rem;">
                <a href="{{ route('admin.pages.about.edit') }}" style="color: #c56a7f; font-weight: 700;">Edit this page</a>
            </p>
        @endif
    </div>
</section>
@endsection
