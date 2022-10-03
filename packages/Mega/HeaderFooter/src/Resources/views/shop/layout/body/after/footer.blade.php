<?php
$helper = app('Mega\HeaderFooter\Helper\MainHelper');
$enabled = $helper->isEnabled();
?>
@if($enabled)
    <?php
    $footerHtml = $helper->getFooterHtml();
    $js = $helper->getMiscJs();
    $css = $helper->getMiscCss()
    ?>
    @if($footerHtml != '')
        {!! $footerHtml !!}
    @endif
    @if( $js != '')
        {!! $js !!}
    @endif
    @if($css != "")
        <style>
            {!! $css !!}
        </style>
    @endif
@endif
