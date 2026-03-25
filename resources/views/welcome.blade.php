@extends('layouts.app')

@section('content')
<section class="hero">
    <div class="hero__character-wrap">
        <img src="{{ asset('images/croppedwave.PNG') }}" alt="Character" class="hero__character-img">
    </div>

    <div class="hero__content">
        <div class="hero__text-box">
            <h1 class="hero__title">Testing What Actually Works for PCOS</h1>
             <p class="hero__subtitle">I’m experimenting with diets, supplements, and routines to manage PCOS - sharing what actually helps, what doesn't, and everything I learn along the way.</p>
        </div>
    </div>
</section>
@endsection