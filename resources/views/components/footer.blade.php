<footer class="site-footer">
    <div class="site-footer__inner">

        <div class="site-footer__brand">
            <a href="{{ route('home') }}" class="site-footer__logo-link">
                <img src="{{ asset('images/logo.png') }}" alt="The Reset Trials" class="site-footer__logo">
            </a>
            <p class="site-footer__tagline">Testing what actually works for PCOS - honestly, without filters.</p>
            <nav class="site-footer__nav" aria-label="Footer navigation">
                <a href="{{ route('home') }}" class="site-footer__nav-link">Home</a>
                <a href="{{ route('posts.index') }}" class="site-footer__nav-link">Blogs</a>
                <a href="{{ route('about') }}" class="site-footer__nav-link">About</a>
                <a href="{{ route('disclaimer') }}" class="site-footer__nav-link">Disclaimer</a>
                <a href="{{ route('resources') }}" class="site-footer__nav-link">Resources</a>
            </nav>
            <img src="{{ asset('images/waving.PNG') }}" alt="" class="site-footer__character" aria-hidden="true">
        </div>

        <div class="site-footer__feedback">
            <h3 class="site-footer__feedback-title">Got feedback?</h3>
            <p class="site-footer__feedback-sub">Ideas, suggestions, or just want to say hi - drop a note below.</p>

            @if (session('feedback_sent'))
                <div class="site-footer__thanks dismissible-notice" data-dismissible-notice>
                    <span>Thanks! Your message means a lot ✨</span>
                    <button type="button" class="dismissible-notice__close" data-notice-close aria-label="Dismiss notification">&times;</button>
                </div>
            @else
                <form action="{{ route('feedback.store') }}" method="POST" class="site-footer__form">
                    @csrf
                    <input
                        type="text"
                        name="name"
                        placeholder="Your name (optional)"
                        class="site-footer__input"
                        maxlength="100"
                    >
                    <textarea
                        name="message"
                        placeholder="Your message..."
                        required
                        maxlength="2000"
                        class="site-footer__textarea"
                        rows="4"
                    ></textarea>
                    @error('message')
                        <div class="site-footer__error dismissible-notice" data-dismissible-notice>
                            <span>{{ $message }}</span>
                            <button type="button" class="dismissible-notice__close" data-notice-close aria-label="Dismiss notification">&times;</button>
                        </div>
                    @enderror
                    <button type="submit" class="site-footer__submit">Send feedback</button>
                </form>
            @endif
        </div>

    </div>

    <p class="site-footer__copy">&copy; {{ date('Y') }} The Reset Trials. All rights reserved.</p>
</footer>
