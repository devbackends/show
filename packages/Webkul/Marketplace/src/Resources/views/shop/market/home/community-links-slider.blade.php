<div class="community py-5">
    <div class="container">
        <h2 class="h1 mb-4 text-center">
            Find people in the community near you
        </h2>
        <div class="community-slider d-flex align-items-stretch slider">
            <div class="community__item community__gun-shows text-center px-4 py-5 m-1">
                <div>
                    <h2 class="text-white">Gun Shows</h2>
                    <a href="{{route('marketplace.shows.index')}}" class="btn btn-primary">Explore</a>
                </div>
            </div>
            <div class="community__item community__instructors text-center px-4 py-5 m-1">
                <div>
                    <h2 class="text-white">Instructors</h2>
                    <a href="{{route('marketplace.instructors.index')}}" class="btn btn-primary">Explore</a>
                </div>
            </div>
            <div class="community__item community__gun-ranges text-center px-4 py-5 m-1">
                <div>
                    <h2 class="text-white">Gun Ranges</h2>
                    <a href="{{route('marketplace.gun-ranges.index')}}" class="btn btn-primary">Explore</a>
                </div>
            </div>
            <div class="community__item community__ffl text-center px-4 py-5 m-1">
                <div>
                    <h2 class="text-white">Find FFLs Near You</h2>
                    <a href="{{route('marketplace.ffl.index')}}" class="btn btn-primary">Explore</a>
                </div>
            </div>
            <div class="community__item community__clubs text-center px-4 py-5 m-1">
                <div>
                    <h2 class="text-white">Clubs and Associations</h2>
                    <a href="{{route('marketplace.clubs.index')}}" class="btn btn-primary">Explore</a>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')

<script>
    $(document).ready(function() {
        $('.community-slider').slick({
            centerMode: true,
            centerPadding: '60px',
            slidesToShow: 3,
            autoplay: true,
            responsive: [{
                    breakpoint: 768,
                    settings: {
                        arrows: false,
                        centerMode: true,
                        centerPadding: '40px',
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        arrows: true,
                        centerMode: true,
                        centerPadding: '40px',
                        slidesToShow: 1
                    }
                }
            ]
        });
    });
</script>

@endpush

@push('css')
<style>

</style>
@endpush