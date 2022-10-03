<?php
    $helper = app('Mega\HeaderFooter\Helper\MainHelper');
    $enabled = $helper->isEnabled();
    ?>
@if($enabled)
    <?php
        $headerHtml = $helper->getHeaderHtml();
    ?>
    @if($headerHtml != "")
        {!! $headerHtml !!}
    @endif
@endif
