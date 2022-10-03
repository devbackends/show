@if($seller->nra_certified || $seller->uscca_certified || $seller->general_events_certified|| $seller->retailer_badge || $seller->competition_shooter_badge || $seller->promotor_badge || $seller->influencer_badge)
@php $instructor_courses=app('Webkul\Product\Repositories\ProductFlatRepository')->getInstructorCourses($seller->id);  @endphp

<div class="container mb-5">
    <div class="row">
        <div class="col-12 col-md-5">
            <p class="font-paragraph-big-bold">About Instructor</p>
            <div class="instructors__profile-certified mb-4">
                @if($seller->nra_certified) <img src="/themes/market/assets/images/profile-badge-nra.png" alt="" data-toggle="tooltip" data-placement="top" title="NRA"> @endif
                @if($seller->uscca_certified) <img src="/themes/market/assets/images/profile-badge-uscca.png" alt="" data-toggle="tooltip" data-placement="top" title="USCCA"> @endif
                @if($seller->general_events_certified) <img src="/themes/market/assets/images/profile-badge-ffl.png" alt="" data-toggle="tooltip" data-placement="top" title="FFL"> @endif
                @if($seller->retailer_badge) <img src="/themes/market/assets/images/profile-badge-retailer.png" alt="" data-toggle="tooltip" data-placement="top" title="Retailer"> @endif
                @if($seller->competition_shooter_badge) <img src="/themes/market/assets/images/profile-badge-shooter.png" alt="" data-toggle="tooltip" data-placement="top" title="Competition Shooter"> @endif
                @if($seller->promotor_badge) <img src="/themes/market/assets/images/profile-badge-promoter.png" alt="" data-toggle="tooltip" data-placement="top" title="Promoter"> @endif
                @if($seller->veteran_badge) <img src="/themes/market/assets/images/profile-badge-veteran.png" alt="" data-toggle="tooltip" data-placement="top" title="Veteran"> @endif
                @if($seller->influencer_badge) <img src="/themes/market/assets/images/profile-badge-influencer.png" alt="" data-toggle="tooltip" data-placement="top" title="Content Creator"> @endif
            </div>
            <p>{{$seller->instructor_description}}</p>
        </div>
        @if($instructor_courses->count())
        <div class="col-12 col-md-7">
            <div>
                <p class="font-paragraph-big-bold">Available Courses</p>
                <instructor-courses></instructor-courses>
            </div>
        </div>
        @endif
    </div>
</div>


@push('scripts')
<script type="text/x-template" id="instructor-courses-template">
<div>
    <shimmer-component v-if="isLoading" shimmer-count="1"></shimmer-component>

    <div v-if="isCoursesListLoaded" class="row">
        <div class="col-12" :key="index" v-for="(course, index) in instructorCourses">
            <course-card :course="course">
            </course-card>
        </div>
    </div>
    </div>
</script>

<script type="text/javascript">
    var instructorCourses = @json($instructor_courses);
    (() => {
        Vue.component('instructor-courses', {
            'template': '#instructor-courses-template',
            data: function() {
                return {
                    'isLoading': false,
                    'isCoursesListLoaded': true,
                    'instructorCourses':  instructorCourses
                }
            },

            methods: {

            }
        })
    })()
</script>
@endpush
@endif