<?php


namespace Devvly\OnBoarding\Clearent\Models\Demographics;


use Devvly\OnBoarding\Clearent\Models\Model;

class SignatureAgreement extends Model
{
  /** @var string */
  protected $ipAddress;

  /** @var string */
  protected $timestamp;

  /** @var int */
  protected $signatureSourceTypeId;

  /** @var int */
  protected $signatureSectionTypeId;

  /** @var bool */
  protected $signerViewedLegalText;

  /** @var int */
  protected $businessContactId;

  /** @var string */
  protected $documentId;

  /**
   * @return string
   */
  public function getIpAddress()
  {
    return $this->ipAddress;
  }

  /**
   * @param  string  $ipAddress
   */
  public function setIpAddress($ipAddress): void
  {
    $this->ipAddress = $ipAddress;
  }

  /**
   * @return string
   */
  public function getTimestamp()
  {
    return $this->timestamp;
  }

  /**
   * @param  string  $timestamp
   */
  public function setTimestamp($timestamp): void
  {
    $this->timestamp = $timestamp;
  }

  /**
   * @return int
   */
  public function getSignatureSourceTypeId(): int
  {
    return $this->signatureSourceTypeId;
  }

  /**
   * @param  int  $signatureSourceTypeId
   */
  public function setSignatureSourceTypeId(int $signatureSourceTypeId): void
  {
    $this->signatureSourceTypeId = $signatureSourceTypeId;
  }

  /**
   * @return int
   */
  public function getSignatureSectionTypeId(): int
  {
    return $this->signatureSectionTypeId;
  }

  /**
   * @param  int  $signatureSectionTypeId
   */
  public function setSignatureSectionTypeId(int $signatureSectionTypeId): void
  {
    $this->signatureSectionTypeId = $signatureSectionTypeId;
  }

  /**
   * @return bool
   */
  public function isSignerViewedLegalText(): bool
  {
    return $this->signerViewedLegalText;
  }

  /**
   * @param  bool  $signerViewedLegalText
   */
  public function setSignerViewedLegalText(bool $signerViewedLegalText): void
  {
    $this->signerViewedLegalText = $signerViewedLegalText;
  }

  /**
   * @return int
   */
  public function getBusinessContactId(): int
  {
    return $this->businessContactId;
  }

  /**
   * @param  int  $businessContactId
   */
  public function setBusinessContactId(int $businessContactId): void
  {
    $this->businessContactId = $businessContactId;
  }

  /**
   * @return string
   */
  public function getDocumentId()
  {
    return $this->documentId;
  }

  /**
   * @param  string  $documentId
   */
  public function setDocumentId($documentId): void
  {
    $this->documentId = $documentId;
  }
}