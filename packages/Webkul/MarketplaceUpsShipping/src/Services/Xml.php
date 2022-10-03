<?php

namespace Webkul\MarketplaceUpsShipping\Services;

use SimpleXMLElement;

class Xml
{
    /**
     * @var array
     */
    protected $options;

    /**
     * XmlHelper constructor.
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->options = $options;
    }

    /**
     * Generate xml
     *
     * @return string
     */
    public function execute(): string
    {
        return $this->createAccessXml() . $this->createRateXml();
    }

    /**
     * @return string
     */
    protected function createRateXml(): string
    {
        $xmlObj = new SimpleXMLElement("<RatingServiceSelectionRequest></RatingServiceSelectionRequest>");

        // Build Request child
        $requestObj = $xmlObj->addChild('Request');
        $requestObj->addChild("RequestAction","Rate");
        $requestObj->addChild("RequestOption","Shop");

        // Build Shipment child
        $shipmentObj = $xmlObj->addChild('Shipment');
        // Build Shipper child
        $shipperObj = $shipmentObj->addChild('Shipper');
        $shipperObj->addChild("Name", $this->options['shipper']['name']);
        $shipperObj->addChild("ShipperNumber", $this->options['shipper']['number']);
        // Build Shipper Address child
        $shipperAddressObj = $shipperObj->addChild('Address');
        $shipperAddressObj->addChild("AddressLine1", $this->options['shipper']['address']);
        $shipperAddressObj->addChild("City", $this->options['shipper']['city']);
        $shipperAddressObj->addChild("PostalCode", $this->options['shipper']['zipcode']);
        $shipperAddressObj->addChild("CountryCode", $this->options['shipper']['country_id']);
        // Build ShipFrom child
        $shipFromObj = $shipmentObj->addChild('ShipFrom');
        $shipFromObj->addChild("CompanyName", $this->options['ship_from']['company_name']);
        // Build ShipFromAddress child
        $shipFromAddressObj = $shipFromObj->addChild('Address');
        $shipFromAddressObj->addChild("AddressLine1", $this->options['ship_from']['address']);
        $shipFromAddressObj->addChild("City", $this->options['ship_from']['city']);
        $shipFromAddressObj->addChild("StateProvinceCode", $this->options['ship_from']['zone']);
        $shipFromAddressObj->addChild("PostalCode", $this->options['ship_from']['zipcode']);
        $shipFromAddressObj->addChild("CountryCode", $this->options['ship_from']['country_id']);
        // Build ShipTo child
        $shipToObj = $shipmentObj->addChild('ShipTo');
        $shipToObj->addChild("CompanyName", str_replace('&', '%26', $this->options['ship_to']['company_name']));
        // Build ShipToAddress child
        $shipToAddressObj = $shipToObj->addChild('Address');
        $shipToAddressObj->addChild("AddressLine1", str_replace('&', '%26', $this->options['ship_to']['address']));
        $shipToAddressObj->addChild("City", $this->options['ship_to']['city']);
        $shipToAddressObj->addChild("PostalCode", $this->options['ship_to']['zipcode']);
        $shipToAddressObj->addChild("CountryCode", $this->options['ship_to']['country_id']);
        // Build Package child
        $packageObj = $shipmentObj->addChild('Package');
        // Build PackageType child
        $packageTypeObj = $packageObj->addChild('PackagingType');
        $packageTypeObj->addChild("Code", $this->options['package_code']);
        // Build PackageWeight child
        $packageWeight = $packageObj->addChild('PackageWeight');
        // Build UunitOfMeasurement child
        $unitOfMeasurementObj = $packageWeight->addChild('UnitOfMeasurement');
        $unitOfMeasurementObj->addChild("Code","LBS");
        $packageWeight->addChild("Weight", $this->options['weight']);

        return $xmlObj->asXML();
    }

    /**
     * @return string
     */
    protected function createAccessXml(): string
    {
        $accessRequesttXML = new SimpleXMLElement("<AccessRequest></AccessRequest>");
        $accessRequesttXML->addChild("AccessLicenseNumber", $this->options['access_key']);
        $accessRequesttXML->addChild("UserId", $this->options['user_id']);
        $accessRequesttXML->addChild("Password", $this->options['password']);
        return $accessRequesttXML->asXML();
    }
}