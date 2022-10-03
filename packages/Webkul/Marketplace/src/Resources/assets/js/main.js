(function ($) {

  // toggle search bar
  $('.js-toggle-search').on('click', function (e) {
    e.preventDefault();
    var $this = $(this);

    $this.toggleClass('active');

    $('.searchbar').toggle();

    $(document).on('click', function (e) {
      if ($(e.target).closest('.vc-small-screen').length == 0) {
        $('.searchbar').hide();
        $this.removeClass('active');
      }
    });
  });

  // toggle mobile nav
  $('.js-toggle-nav').on('click', function (e) {
    e.preventDefault();
    var $this = $(this);

    $this.toggleClass('active');

    $('.nav-container').toggleClass('active');

    $(document).on('click', function (e) {
      if ($(e.target).closest('.nav-container .wrapper').length == 0 && $(e.target).closest('.js-toggle-nav').length == 0) {
        $('.nav-container').removeClass('active');
        $this.removeClass('active');
      }
    });
  });

  $('.js-close-nav').on('click', function (e) {
    e.preventDefault();

    $('.js-toggle-nav').toggleClass('active');
    $('.nav-container').toggleClass('active');
  });

  // toggle user nav
  $('.js-toggle-user-nav').on('click', function (e) {
    e.preventDefault();
    var $this = $(this);

    $('.js-user-nav').toggleClass('active');

    $(document).on('click', function (e) {
      if ($(e.target).closest('.customer-sidebar').length == 0 && $(e.target).closest('.js-toggle-user-nav').length == 0) {
        $('.js-user-nav').removeClass('active');
      }
    });
  });


  $(document).ready(function(){

    $('#carouselNewProducts').on('slid.bs.carousel', '', function() {
      var $this = $(this);
      console.log("TEST!!!!!");

      $this.find('.carousel-control').show();

      if($('.carousel-inner .carousel-item:first').hasClass('active')) {
        $this.find('.carousel-control-prev').hide();
        console.log('first slide');
      } else if($('.carousel-inner .carousel-item:nth-last-child(4)').hasClass('active')) {
        $this.find('.carousel-control-next').hide();
        console.log('last slide');
      }

    });

    document.addEventListener('scroll', e => {
        scrollPosition = Math.round(window.scrollY);
        const settingsPageHeader=document.querySelector('.settings-page__header');
        const settingsPage=document.querySelector('.settings-page');
        if (scrollPosition > 66) {
            if(settingsPageHeader){
                settingsPageHeader.classList.add('settings-page__header--fixed');
            }
            if(settingsPage){
                settingsPage.classList.add('settings-page--fixed');
            }
        } else {
            if(settingsPageHeader){
                settingsPageHeader.classList.remove('settings-page__header--fixed');
            }
            if(settingsPage){
                settingsPage.classList.remove('settings-page--fixed');
            }
        }
    });

});


})(jQuery)




