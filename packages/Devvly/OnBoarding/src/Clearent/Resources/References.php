<?php


namespace Devvly\OnBoarding\Clearent\Resources;

use Devvly\OnBoarding\Clearent\Client;
use Devvly\OnBoarding\Clearent\Responses\ClearentException;
use Devvly\OnBoarding\Clearent\Responses\References\CompanyType;
use Devvly\OnBoarding\Clearent\Responses\References\CompensationType;
use Devvly\OnBoarding\Clearent\Responses\References\CountryOption;
use Devvly\OnBoarding\Clearent\Responses\References\DocumentCategory;
use Devvly\OnBoarding\Clearent\Responses\References\FutureDeliveryType;
use Devvly\OnBoarding\Clearent\Responses\References\MerchantAcquisitionType;
use Devvly\OnBoarding\Clearent\Responses\References\MerchantCategoryCode;
use Devvly\OnBoarding\Clearent\Responses\References\PhoneType;
use Devvly\OnBoarding\Clearent\Responses\References\PreviousProcessor;
use Devvly\OnBoarding\Clearent\Responses\References\SignatureSection;
use Devvly\OnBoarding\Clearent\Responses\References\SignatureSourceType;
use Devvly\OnBoarding\Clearent\Responses\References\SiteType;
use Devvly\OnBoarding\Clearent\Responses\References\StateOption;
use Devvly\OnBoarding\Clearent\Responses\Demographics\ContactType;

class References extends IResource
{

  const path = "/demographics/v1.0/References";

  public function __construct(Client $client)
  {
    parent::__construct($client);
  }

  /**
   * @param bool $transform
   *
   * @return CountryOption[]|array
   * @throws ClearentException
   */
  public function getCountryOptions($transform = true){
    $url = self::path . "/CountryOptions";
    $response = $this->client->get($url);
    if(!is_array($response)){
      return $response;
    }
    $content = $response['content'];
    if (!$transform) {
      return $content;
    }
    $data = [];
    foreach ($content as $item) {
      $data[] = new CountryOption($item);
    }
    return $data;
  }

  /**
   * @param bool $transform
   * @return StateOption[]|array
   * @throws ClearentException
   */
  public function getStateOptions($transform = true){
    $url = self::path . "/StateOptions";
    $response = $this->client->get($url);
    if(!is_array($response)){
      return $response;
    }
    $content = $response['content'];
    if (!$transform) {
      return $content;
    }
    $data = [];
    foreach ($content as $item) {
      $data[] = new StateOption($item);
    }
    return $data;
  }

  /**
   * @param bool $transform
   *
   * @return MerchantCategoryCode[]|array
   * @throws ClearentException
   */
  public function getMccCodes($transform = true){
    $url = self::path . "/MccCodes";
    $response = $this->client->get($url);
    if(!is_array($response)){
      return $response;
    }
    $content = $response['content'];
    if (!$transform) {
      return $content;
    }
    $data = [];
    foreach ($content as $item) {
      $data[] = new MerchantCategoryCode($item);
    }
    return $data;
  }

  /**
   * @param bool $transform
   *
   * @return CompanyType[]|array
   * @throws ClearentException
   */
  public function getCompanyTypes($transform = true){
    $url = self::path . "/CompanyTypes";
    $response = $this->client->get($url);
    if(!is_array($response)){
      return $response;
    }
    $content = $response['content'];
    if (!$transform) {
      return $content;
    }
    $types = [];
    foreach ($content as $item) {
      $types[] = new CompanyType($item);
    }
    return $types;
  }

  /**
   * @param bool $transform
   *
   * @return CompensationType[]|array
   * @throws ClearentException
   */
  public function getCompensationTypes($transform = true){
    $url = self::path . "/CompensationTypes";
    $response = $this->client->get($url);
    if(!is_array($response)){
      return $response;
    }
    $content = $response['content'];
    if (!$transform) {
      return $content;
    }
    $types = [];
    foreach ($content as $item) {
      $types[] = new CompensationType($item);
    }
    return $types;
  }

  /**
   * @param bool $transform
   *
   * @return PreviousProcessor[]|array
   * @throws ClearentException
   */
  public function getPreviousProcessors($transform = true){
    $url = self::path . "/PreviousProcessors";
    $response = $this->client->get($url);
    if(!is_array($response)){
      return $response;
    }
    $content = $response['content'];
    if (!$transform) {
      return $content;
    }
    $types = [];
    foreach ($content as $item) {
      $types[] = new PreviousProcessor($item);
    }
    return $types;
  }

  /**
   * @param bool $transform
   *
   * @return PhoneType[]|array
   * @throws ClearentException
   */
  public function getPhoneTypes($transform = true){
    $url = self::path . "/PhoneTypes";
    $response = $this->client->get($url);
    if(!is_array($response)){
      return $response;
    }
    $content = $response['content'];
    if (!$transform) {
      return $content;
    }
    $types = [];
    foreach ($content as $item) {
      $types[] = new PhoneType($item);
    }
    return $types;
  }

  /**
   * @param bool $transform
   *
   * @return ContactType[]|array
   * @throws ClearentException
   */
  public function getContactTypes($transform = true){
    $url = self::path . "/ContactTypes";
    $response = $this->client->get($url);
    if(!is_array($response)){
      return $response;
    }
    $content = $response['content'];
    if (!$transform) {
      return $content;
    }
    $types = [];
    foreach ($content as $item) {
      $types[] = new ContactType($item);
    }
    return $types;
  }

  /**
   * @param bool $transform
   *
   * @return DocumentCategory[]|array
   * @throws ClearentException
   */
  public function getDocumentCategories($transform = true){
    $url = self::path . "/DocumentCategories";
    $response = $this->client->get($url);
    if(!is_array($response)){
      return $response;
    }
    $content = $response['content'];
    if (!$transform) {
      return $content;
    }
    $data = [];
    foreach ($content as $item) {
      $data[] = new DocumentCategory($item);
    }
    return $data;
  }

  /**
   * @param bool $transform
   *
   * @return SignatureSection[]
   * @throws ClearentException
   */
  public function getSignatureSections($transform = true){
    $url = self::path . "/SignatureSections";
    $response = $this->client->get($url);
    if(!is_array($response)){
      return $response;
    }
    $content = $response['content'];
    if (!$transform) {
      return $content;
    }
    $data = [];
    foreach ($content as $item) {
      $data[] = new SignatureSection($item);
    }
    return $data;
  }

  /**
   * @param bool $transform
   *
   * @return SignatureSourceType[]
   * @throws ClearentException
   */
  public function getSignatureSourceTypes($transform = true){
    $url = self::path . "/SignatureSourceTypes";
    $response = $this->client->get($url);
    if(!is_array($response)){
      return $response;
    }
    $content = $response['content'];
    if (!$transform) {
      return $content;
    }
    $types = [];
    foreach ($content as $item) {
      $types[] = new SignatureSourceType($item);
    }
    return $types;
  }

  /**
   * @param bool $transform
   *
   * @return SiteType[]|array
   * @throws ClearentException
   */
  public function getSiteTypes($transform = true){
    $url = self::path . "/SiteTypes";
    $response = $this->client->get($url);
    if(!is_array($response)){
      return $response;
    }
    $content = $response['content'];
    if (!$transform) {
      return $content;
    }
    $types = [];
    foreach ($content as $item) {
      $types[] = new SiteType($item);
    }
    return $types;
  }

  /**
   * @param bool $transform
   *
   * @return FutureDeliveryType[]|array
   * @throws ClearentException
   */
  public function getFutureDeliveryTypes($transform = true){
    $url = self::path . "/FutureDeliveryTypes";
    $response = $this->client->get($url);
    if(!is_array($response)){
      return $response;
    }
    $content = $response['content'];
    if (!$transform) {
      return $content;
    }
    $types = [];
    foreach ($content as $item) {
      $types[] = new FutureDeliveryType($item);
    }
    return $types;
  }

  /**
   * @param bool $transform
   *
   * @return MerchantAcquisitionType[]|array
   * @throws ClearentException
   */
  public function getMerchantAcquisitionTypes($transform = true){
    $url = self::path . "/MerchantAcquisitionTypes";
    $response = $this->client->get($url);
    if(!is_array($response)){
      return $response;
    }
    $content = $response['content'];
    if (!$transform) {
      return $content;
    }
    $types = [];
    foreach ($content as $item) {
      $types[] = new MerchantAcquisitionType($item);
    }
    return $types;
  }


}