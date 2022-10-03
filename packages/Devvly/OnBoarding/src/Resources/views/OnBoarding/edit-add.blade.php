@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.configuration.title') }}
@stop

@section('css')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-xVVam1KS4+Qt2OrFa+VdRUoXygyKIuNWUUUBZYv+n27STsJ7oDOHJgfF0bNKLMJF" crossorigin="anonymous"/>
    <link rel="stylesheet" href="{{ asset('vendor/devvly/onboarding/assets/css/onboarding.css') }}"/>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
@stop

@section('content')
    <div class="content">
          <div class="page-header">
              <div class="page-title">
                  <h1>
                      {{ __('OnBoarding::app.edit_add.title') }}
                  </h1>
              </div>
          </div>
          <div class="page-content">
              <main-component></main-component>
          </div>
    </div>
@stop
@push('scripts')
    <script>
			window.base_url = window.location.origin;

			$( document ).ready(function() {

				/*start accordion function*/
				$( document ).on('click','.accordion-header-title',function(e){
					if(! $(this).parent().hasClass("disabled")){
						this.classList.toggle("active");
						if(this.classList.contains("active")){
							$(this.children[0][0]).removeClass('fa-chevron-right');
							$(this.children[0][0]).addClass('fa-chevron-down');

						}else{
							$(this.children[0][0]).removeClass('fa-chevron-down');
							$(this.children[0][0]).addClass('fa-chevron-right');
						}

						var panel = this.nextElementSibling;
						if (panel.style.maxHeight) {
							panel.style.maxHeight = null;
						} else {
							panel.style.maxHeight = panel.scrollHeight + 50 + "px";
						}
					}

				});

				/*end accordion function*/
			});

    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script src="{{asset('vendor/devvly/onboarding/assets/js/app.js')}}"></script>
@endpush