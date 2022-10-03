<header id="sticky-header" class="sticky-header header-middle d-none d-sm-block">
    <div id="header-container" class="row col-12 remove-padding-margin velocity-divide-page">
        <a href="/" class="brand-logo"></a>
        <searchbar-component></searchbar-component>
    </div>
</header>

@push('scripts')
<script type="text/javascript">
        (() => {
            document.addEventListener('scroll', e => {
                scrollPosition = Math.round(window.scrollY);
                if (scrollPosition > 50){
                    document.querySelector('header').classList.add('header-shadow');
                    document.querySelector('#alert-container').classList.add('alert-container--scrolled');
                } else {
                    document.querySelector('header').classList.remove('header-shadow');
                    document.querySelector('#alert-container').classList.remove('alert-container--scrolled');
                }
            });


        })()
    </script>
@endpush
