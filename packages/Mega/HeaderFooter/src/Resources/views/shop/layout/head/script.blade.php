<?php
$helper = app('Mega\HeaderFooter\Helper\MainHelper');
$enabled = $helper->isEnabled();
?>
@if($enabled)
    <?php
    $headerJs = $helper->getHeaderJs();
    ?>
    @if($headerJs != '')
        {!! $headerJs !!}
    @endif
@endif
