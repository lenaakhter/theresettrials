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
        <a href="/about" class="site-nav-bar__link">About</a>
        <a href="/shop" class="site-nav-bar__link">Shop</a>
        <a href="/contact" class="site-nav-bar__link">Contact</a>
        <a href="/join" class="c-button c-button--gooey">Join Us
            <div class="c-button__blobs">
                <div></div>
                <div></div>
                <div></div>
            </div>
        </a>
    </div>

    <div class="site-nav-desktop">
        <div class="site-nav-bar__logo">
            <a href="/">
                <img src="/images/logo.png" alt="Logo" class="site-nav-bar__logo-image">
            </a>
        </div>

        <div class="site-nav-bar__links">
            <a href="/" class="site-nav-bar__link">Home</a>
            <a href="/about" class="site-nav-bar__link">About</a>
            <a href="/shop" class="site-nav-bar__link">Shop</a>
            <a href="/contact" class="site-nav-bar__link">Contact</a>
        </div>

        <div class="join-us">
            <a href="/join" class="c-button c-button--gooey">Join Us
                <div class="c-button__blobs">
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            </a>
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