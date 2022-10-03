<?php

namespace Webkul\MarketplaceUspsShipping\Helpers;

use SimpleXMLElement;

class XmlHelper
{

    protected $options;

    public function __construct($options)
    {
        $this->options = $options;
    }

    public function getXml()
    {
        $xmlNode = ($this->options['isUs'])
            ? new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><RateV4Request/>')
            : new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><IntlRateV2Request/>');

        $xmlNode->addAttribute('USERID', $this->options['userId']);
        $xmlNode->addChild('Revision', '2');
        // Build package child
        $packageObj = $xmlNode->addChild('Package');
            $packageObj->addAttribute('ID', $this->options['id']);

        if ($this->options['isUs']) {
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
            $packageObj->addChild('Size', $this->options['size']);

            if ($this->options['size'] === 'LARGE') {
                $packageObj->addChild('Width', $this->options['width']);
                $packageObj->addChild('Length', $this->options['length']);
                $packageObj->addChild('Height', $this->options['height']);
            }
            $packageObj->addChild('Machinable', $this->options['machinable']);
        } else {

            $packageObj->addChild('Pounds', $this->options['weight_in_pounds']);
            $packageObj->addChild('Ounces', $this->options['weight_in_ounces']);
            $packageObj->addChild('MailType', 'All');
            $packageObj->addChild('ValueOfContents', $this->options['price']);
            $packageObj->addChild('Country', $this->options['country_name']);
            $packageObj->addChild('Container', $this->options['container']);
            $packageObj->addChild('Size', $this->options['size']);
            if ($this->options['size'] === 'LARGE') {
                $packageObj->addChild('Width', $this->options['width']);
                $packageObj->addChild('Length', $this->options['length']);
                $packageObj->addChild('Height', $this->options['height']);
                $packageObj->addChild('Girth', '');
            }
            date_default_timezone_set('America/Los_Angeles');
            $packageObj->addChild('OriginZip', $this->options['ship_from']['zipcode']);
            $packageObj->addChild('AcceptanceDateTime', date('c'));
            $packageObj->addChild('DestinationPostalCode', $this->options['ship_to']['zipcode']);
        }

        return $xmlNode->saveXML();
    }

}