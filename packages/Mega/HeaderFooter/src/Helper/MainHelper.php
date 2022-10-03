<?php


namespace Mega\HeaderFooter\Helper;


class MainHelper
{

    protected $configPrefix = 'megaheaderfooter.general.headerfooter.';
    protected $configHeaderPrefix = 'megaheaderfooter.general.header.';
    protected $configFooterPrefix = 'megaheaderfooter.general.footer.';


    public function isEnabled(){
        return $this->getConfig($this->configPrefix.'active');
    }

    public function getHeaderHtml(){
        return $this->getConfig($this->configHeaderPrefix.'header_html');
    }

    public function getFooterHtml(){
        return $this->getConfig($this->configFooterPrefix.'footer_html');
    }


    public function getMiscJs(){
        return $this->getConfig($this->configFooterPrefix.'misc_js');
    }


    public function getMiscCss(){
        return $this->getConfig($this->configFooterPrefix.'misc_css');
    }

    public  function getHeaderJs(){
        return $this->getConfig($this->configHeaderPrefix.'header_js');
    }

    public function getConfig($config){
        return core()->getConfigData($config);
    }

}