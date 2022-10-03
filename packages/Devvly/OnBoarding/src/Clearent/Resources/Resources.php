<?php


namespace Devvly\OnBoarding\Clearent\Resources;

use Devvly\OnBoarding\Clearent\Client;
use Devvly\OnBoarding\Clearent\Resources\Helpers\BusinessInformations;
use Devvly\OnBoarding\Clearent\Resources\Helpers\Pricing;

class Resources
{

  protected $resources;

  /**
   * Resources constructor.
   *
   * @param Client $client
   */
  public function __construct(Client $client)
  {
    $this->resources = [
        'applications' => new Applications($client),
        'references' => new References($client),
        'merchants' => new Merchants($client),
        'physicalAddresses' => new PhysicalAddresses($client),
        'mailingAddresses' => new MailingAddresses($client),
        'salesProfiles' => new SalesProfiles($client, $this),
        'taxPayers' => new TaxPayers($client),
        'siteSurveys' => new SiteSurveys($client),
        'businessContacts' => new BusinessContacts($client),
        'businessInformations' => new BusinessInformations($client, $this),
        'bankAccounts' => new BankAccounts($client, $this),
        'documents' => new Documents($client),
        'signatures' => new Signatures($client, $this),
        'pricingPlans' => new PricingPlans($client),
        'pricing' => new Pricing($client, $this),
        'termsAndConditions' => new TermsAndConditions($client),
        'equipments' => new Equipments($client),
    ];

  }

  /**
   * @return Applications
   */
  public function applications()
  {
    return $this->resources['applications'];
  }

  /**
   * @return References
   */
  public function references(){
    return $this->resources['references'];
  }

  /**
   * @return Merchants
   */
  public function merchants(){
    return $this->resources['merchants'];
  }

  /**
   * @return PhysicalAddresses
   */
  public function physicalAddresses(){
    return $this->resources['physicalAddresses'];
  }

  /**
   * @return MailingAddresses
   */
  public function mailingAddresses(){
    return $this->resources['mailingAddresses'];
  }

  /**
   * @return SalesProfiles
   */
  public function salesProfiles(){
    return $this->resources['salesProfiles'];
  }

  /**
   * @return TaxPayers
   */
  public function taxPayers()
  {
    return $this->resources['taxPayers'];
  }

  /**
   * @return SiteSurveys
   */
  public function siteSurveys()
  {
    return $this->resources['siteSurveys'];
  }

  /**
   * @return BusinessContacts
   */
  public function businessContacts()
  {
    return $this->resources['businessContacts'];
  }

  /**
   * @return BusinessInformations
   */
  public function businessInformations()
  {
    return $this->resources['businessInformations'];
  }

  /**
   * @return BankAccounts
   */
  public function bankAccounts()
  {
    return $this->resources['bankAccounts'];
  }

  /**
   * @return Documents
   */
  public function documents()
  {
    return $this->resources['documents'];
  }

  /**
   * @return PricingPlans
   */
  public function pricingPlans()
  {
    return $this->resources['pricingPlans'];
  }

  /**
   * @return Pricing
   */
  public function pricing()
  {
    return $this->resources['pricing'];
  }

  /**
   * @return TermsAndConditions
   */
  public function termsAndConditions()
  {
    return $this->resources['termsAndConditions'];
  }

  /**
   * @return Signatures
   */
  public function signatures()
  {
    return $this->resources['signatures'];
  }

  /**
   * @return Equipments
   */
  public function equipments()
  {
    return $this->resources['equipments'];
  }
}