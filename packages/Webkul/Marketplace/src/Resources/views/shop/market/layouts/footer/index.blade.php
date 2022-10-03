<!-- FOOTER -->
@php
    $footerCls = new \PagebuilderThemes\TwoA\Helpers\Footer();
    $footer = $footerCls->getContent();
@endphp
{!! $footer !!}
<!-- END FOOTER -->