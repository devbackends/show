<?php


namespace Devvly\OnBoarding\Clearent\Resources;



use Devvly\OnBoarding\Clearent\Client;
use Devvly\OnBoarding\Clearent\Models\Demographics\BankAccount as Model;
use Devvly\OnBoarding\Clearent\Models\Demographics\VoidedCheck;
use Devvly\OnBoarding\Clearent\Responses\Demographics\BankAccount;
use Devvly\OnBoarding\Clearent\Responses\ClearentException;

class BankAccounts extends IResource
{
  const path = "/demographics/v1.0/BankAccounts";

  /** @var Resources */
  protected $resources;

  public function __construct(Client $client, Resources $resources)
  {
    parent::__construct($client);
    $this->resources = $resources;
  }

  /**
   * @param  int  $merchantNumber
   *
   * @return BankAccount[]
   * @throws ClearentException
   */
  public function all($merchantNumber)
  {
    $url = self::path . "/" . $merchantNumber;
    $headers = [
        'MerchantID' => $merchantNumber
    ];
    $result = $this->client->get($url, $headers);
    $items = [];
    foreach ($result['content'] as $item) {
      $items[] = new BankAccount($item);
    }
    return $items;
  }

  /**
   * @param  int  $merchantNumber
   *
   * @return BankAccount
   * @throws ClearentException
   */
  public function get($merchantNumber)
  {
    $url = self::path . "/" . $merchantNumber;
    $headers = [
        'MerchantID' => $merchantNumber
    ];
    $result = $this->client->get($url, $headers);
    return new BankAccount($result);
  }

  /**
   * @param  int  $merchantNumber
   * @param  Model  $options
   *
   * @return BankAccount
   * @throws ClearentException
   */
  public function create($merchantNumber, $options)
  {
    $id = $options->getBankAccountID();
    $url = self::path . "/" . $merchantNumber;
    $body = $options->toArray();
    $headers = [
        'MerchantID' => $merchantNumber
    ];
    if($id){
      $url .= '/' . $id;
      $result = $this->client->put($url, $body, $headers);
    }else{
      $result = $this->client->post($url, $body, $headers);
    }

    $bankAccount = new BankAccount($result);
    // upload voided check document:
    $path = $options->getVoidedCheckDocumentPath();
    $chunks = explode('.',$path);
    $fileName = 'voided_check' . $merchantNumber . '.' . $chunks[count($chunks) -1];
    $document = new VoidedCheck();
    $document->setFileName($fileName);
    $document->setFilePath($path);
    $document->setMerchantNumber($merchantNumber);
    $document->setBankAccountId($bankAccount->getBankAccountID());
    $documentRes = $this->resources->documents()->uploadVoidedCheck($document);
    $stop = null;
    // update the bank account:
    $options->setVoidedCheckDocumentID($documentRes->getDocumentId());
    if(!$id){
      $url .= '/' . $bankAccount->getBankAccountID();
    }
    $body = $options->toArray();
    $result = $this->client->put($url, $body, $headers);
    $bankAccount = new BankAccount($result);
    return $bankAccount;
  }

}