<footer class="footer">
    {{-- Todo: テキストロゴに差し替えたい --}}
    <a href="{{ route('index.home') }}">
        <img src="{{ asset('img/logo.svg') }}" width="50" height="50">
    </a>

    <nav>
        <ul class="footer__list">
            <li><a href="https://symbax.github.io/help/articles/2" class="footer__link"
                    target="__blank">{{ __('Terms of Service') }}</a>
            </li>
            <li><a href="https://symbax.github.io/help/articles/3" class="footer__link"
                    target="__blank">{{ __('Privacy Policy') }}</a>
            </li>
            <li><a href="https://symbax.github.io/help/" class="footer__link"
                    target="__blank">{{ __('Help Center') }}</a></li>
            <li><a href="https://forms.gle/1Vvjkeda4tHZT9d3A" class="footer__link"
                    target="__blank">{{ __('Inquiry form') }}</a></li>
        </ul>
    </nav>
    <p class="footer__copyright">&copy; 2023 SymbaX.</p>
</footer>
