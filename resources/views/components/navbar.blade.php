<nav class="site-nav-bar">
    <input type="checkbox" id="site-nav-toggle" class="site-nav-toggle" aria-label="Toggle navigation menu">

    <div class="site-nav-mobile">
        <div class="site-nav-mobile__spacer" aria-hidden="true"></div>
        <a href="/" class="site-nav-mobile__logo-link">
            <img src="/images/logo.png" alt="Logo" class="site-nav-mobile__logo-image">
        </a>
        <label for="site-nav-toggle" class="site-nav-mobile__hamburger" aria-label="Open menu">
            <span></span>
            <span></span>
            <span></span>
        </label>
    </div>

    <div class="site-nav-mobile__menu">
        <a href="/" class="site-nav-bar__link">Home</a>
        <a href="/blogs" class="site-nav-bar__link">Blogs</a>
        <a href="/about" class="site-nav-bar__link">About</a>
        <a href="/disclaimer" class="site-nav-bar__link">Disclaimer</a>
        <a href="/resources" class="site-nav-bar__link">Links &amp; Resources</a>
        @auth
                @if(auth()->user()->is_admin)
                    <a href="{{ route('admin.experiments.index') }}" class="site-nav-bar__link">Admin Lair</a>
                @endif
            <a href="{{ route('profile.edit') }}" class="site-nav-bar__link">Profile</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="c-button c-button--gooey">Log out
                    <div class="c-button__blobs">
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" class="site-nav-bar__link">Log in</a>
        @endauth
    </div>

    <div class="site-nav-desktop">
        <div class="site-nav-bar__logo">
            <a href="/">
                <img src="/images/logo.png" alt="Logo" class="site-nav-bar__logo-image">
            </a>
        </div>

        <div class="site-nav-bar__links">
            <a href="/" class="site-nav-bar__link">Home</a>
            <a href="/blogs" class="site-nav-bar__link">Blogs</a>
            <a href="/about" class="site-nav-bar__link">About</a>
            <a href="/disclaimer" class="site-nav-bar__link">Disclaimer</a>
            <a href="/resources" class="site-nav-bar__link">Links &amp; Resources</a>
        </div>

        <div class="join-us">
            @auth
                    @if(auth()->user()->is_admin)
                        <a href="{{ route('admin.experiments.index') }}" class="site-nav-bar__link">Admin</a>
                    @endif
                <div class="site-nav-account">
                    <a href="{{ route('profile.edit') }}" class="site-nav-account__trigger" aria-label="Account menu">
                        @if(auth()->user()->profile_photo)
                            <img
                                src="{{ asset(auth()->user()->profile_photo) }}"
                                alt="{{ auth()->user()->display_name ?: auth()->user()->name }}"
                                class="site-nav-account__avatar"
                                style="object-position: {{ auth()->user()->avatar_focus_x ?? 50 }}% {{ auth()->user()->avatar_focus_y ?? 50 }}%;"
                            >
                        @else
                            <span class="site-nav-account__avatar site-nav-account__avatar--placeholder">
                                {{ strtoupper(substr(auth()->user()->display_name ?: auth()->user()->name, 0, 1)) }}
                            </span>
                        @endif
                    </a>

                    <div class="site-nav-account__menu" aria-label="Account options">
                        <a href="{{ route('profile.edit') }}" class="site-nav-account__menu-link">Profile</a>
                        <form method="POST" action="{{ route('logout') }}" class="site-nav-account__menu-form">
                            @csrf
                            <button type="submit" class="site-nav-account__menu-button">Log out</button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="c-button c-button--gooey">Log in
                    <div class="c-button__blobs">
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                </a>
            @endauth
        </div>
    </div>
</nav>
<svg xmlns="http://www.w3.org/2000/svg" version="1.1" class="c-button__svg-defs" aria-hidden="true" focusable="false">
    <defs>
        <filter id="goo">
            <feGaussianBlur in="SourceGraphic" stdDeviation="10" result="blur"></feGaussianBlur>
            <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 18 -7" result="goo"></feColorMatrix>
            <feBlend in="SourceGraphic" in2="goo"></feBlend>
        </filter>
    </defs>
</svg>