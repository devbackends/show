<?php

namespace Webkul\MarketplaceUspsShipping\Services;

use SimpleXMLElement;

class Xml
{
    protected $options;

    public function __construct($options)
    {
        $this->options = $options;
    }

    public function execute()
    {
        $xmlNode = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><RateV4Request/>');

        $xmlNode->addAttribute('USERID', $this->options['userId']);
        $xmlNode->addChild('Revision', '2');
        // Build package child
        $packageObj = $xmlNode->addChild('Package');
        $packageObj->addAttribute('ID', $this->options['id']);

        $service = 'All';
        if ($this->options['flat_rate_services']) $service = 'Priority';
        $packageObj->addChild('Service', $service);

        if ($this->options['first_class_services']) {
            $packageObj->addChild('FirstClassMailType', 'PARCEL');
        }

        $packageObj->addChild('ZipOrigination', $this->options['ship_from']['zipcode']);
        $packageObj->addChild('ZipDestination', $this->options['ship_to']['zipcode']);
        $packageObj->addChild('Pounds', $this->options['weight_in_pounds']);
        $packageObj->addChild('Ounces', $this->options['weight_in_ounces']);
        $packageObj->addChild('Container', $this->options['container']);
        //$packageObj->addChild('Size', $this->options['size']);

        //if ($this->options['size'] === 'LARGE') {
            $packageObj->addChild('Width', $this->options['width']);
            $packageObj->addChild('Length', $this->options['length']);
            $packageObj->addChild('Height', $this->options['height']);
        //}
        $packageObj->addChild('Machinable', $this->options['machinable']);

        return $xmlNode->saveXML();
    }

}