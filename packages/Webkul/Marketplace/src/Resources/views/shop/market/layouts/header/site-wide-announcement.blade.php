<div id="site-wide-announcement" class="announcement">
  <div class="row align-items-center justify-content-center">
    <div class="col-12 col-md-auto">
      <p class="">We’ve been nominated for a Gundie Award!</p>
    </div>
    <div class="col-12 col-md-auto"><a href="https://thegundies.com/categories/most-innovative-brand-of-the-year" target="_blank" class="btn btn-black mt-3 mt-md-0 d-block d-md-inline-block">Click Here to Vote Now</a></div>
  </div>

  <span class="announcement__close" onclick="closeAnnouncement()">
    <i class="far fa-times"></i>
  </span>
</div>
@push('scripts')
<script type="text/javascript">
  announcementHeight = $('.announcement').innerHeight();
  $('header').css("top", announcementHeight + 'px');
  $('#alert-container').css({
    'padding-top': announcementHeight + 'px'
  });

  document.addEventListener('scroll', e => {
    scrollPosition = Math.round(window.scrollY);
    announcementHeight = $('.announcement').innerHeight();
    if (scrollPosition > 50) {
      $('.announcement').addClass('announcement--fixed');
      $('header').css({
        top: announcementHeight + 'px'
      });
      $('#alert-container').css({
        'padding-top': announcementHeight + 'px'
      });
    } else {
      $('.announcement').removeClass('announcement--fixed');
      $('header').css({
        top: "0px"
      });
      $('#alert-container').css({
        'padding-top': announcementHeight + 'px'
      });
    }
  });

  function closeAnnouncement() {
    $('.announcement').remove()
    $('header').css({
      top: "0px"
    });
    $('#alert-container').css({
      'padding-top': '0px'
    });
  }

  (() => {})()
</script>
@endpush
