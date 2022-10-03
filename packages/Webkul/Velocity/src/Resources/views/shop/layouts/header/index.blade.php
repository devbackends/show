<header id="sticky-header" class="sticky-header header-middle" v-if="!isMobile()">
    <div id="header-right-container" class="container-fluid">
        <div class="row">
            <div class="col-lg-auto">
                <logo-component></logo-component>
            </div>
            <div class="col-lg">
                <searchbar-component></searchbar-component>
            </div>
        </div>
    </div>
</header>

@push('scripts')
    <script type="text/javascript">
        (() => {
            document.addEventListener('scroll', e => {
                scrollPosition = Math.round(window.scrollY);

                if (scrollPosition > 50){
                    document.querySelector('header').classList.add('header-shadow');
                } else {
                    document.querySelector('header').classList.remove('header-shadow');
                }
            });
        })()
    </script>
@endpush
