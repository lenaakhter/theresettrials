<div class="mailing-popup" id="mailing-popup" aria-hidden="true">
    <div class="mailing-popup__overlay" data-popup-close></div>
    <section class="mailing-popup__card" role="dialog" aria-modal="true" aria-labelledby="mailing-popup-title">
        <div class="mailing-popup__image-wrap" aria-hidden="true">
            <img src="{{ asset('images/reading.PNG') }}" alt="" class="mailing-popup__image">
        </div>

        <div class="mailing-popup__content">
            <button type="button" class="mailing-popup__close" aria-label="Close popup" data-popup-close>×</button>
            <h2 id="mailing-popup-title" class="mailing-popup__title">Join the<br>Mailing List</h2>
            <p class="mailing-popup__text">Get updates when new blog posts and PCOS experiment notes go live.</p>
            <a href="{{ route('join.create') }}" class="mailing-popup__cta">Join now</a>
        </div>
    </section>
</div>

<script>
(() => {
    const popup = document.getElementById('mailing-popup');
    if (!popup) return;

    const storageKey = 'mailingPopupDismissed';
    if (window.localStorage.getItem(storageKey) === '1') return;

    const close = () => {
        popup.classList.remove('mailing-popup--open');
        popup.setAttribute('aria-hidden', 'true');
        window.localStorage.setItem(storageKey, '1');
    };

    popup.querySelectorAll('[data-popup-close]').forEach((element) => {
        element.addEventListener('click', close);
    });

    window.setTimeout(() => {
        popup.classList.add('mailing-popup--open');
        popup.setAttribute('aria-hidden', 'false');
    }, 900);
})();
</script>
