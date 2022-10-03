<?php


namespace Devvly\OnBoarding\Clearent\Models\Demographics;


use Devvly\OnBoarding\Clearent\Models\Model;

class Document extends Model
{
  /** @var string */
  protected $fileName;

  /** @var string */
  protected $merchantNumber;

  /** @var mixed */
  protected $filePath;

  /** @var int */
  protected $category;

  /**
   * @return string
   */
  public function getFileName(): string
  {
    return $this->fileName;
  }

  /**
   * @param  string  $fileName
   */
  public function setFileName(string $fileName): void
  {
    $this->fileName = $fileName;
  }

  /**
   * @return string
   */
  public function getMerchantNumber(): string
  {
    return $this->merchantNumber;
  }

  /**
   * @param  string  $merchantNumber
   */
  public function setMerchantNumber(string $merchantNumber): void
  {
    $this->merchantNumber = $merchantNumber;
  }

  /**
   * @param  mixed  $filePath
   */
  public function setFilePath($filePath): void
  {
    $this->filePath = $filePath;
  }

  /**
   * @return int
   */
  public function getCategory(): int
  {
    return $this->category;
  }

  /**
   * @param  int  $category
   */
  public function setCategory(int $category): void
  {
    $this->category = $category;
  }



  /**
   * @return string
   */
  public function getFileBytes()
  {
    $byteArray = unpack("C*", file_get_contents($this->filePath));
    $jsonData = json_encode(array_values($byteArray));
    return $jsonData;
  }
}