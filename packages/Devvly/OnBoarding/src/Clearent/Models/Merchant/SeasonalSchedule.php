<?php


namespace Devvly\OnBoarding\Clearent\Models\Merchant;


use Devvly\OnBoarding\Clearent\Models\Model;

class SeasonalSchedule extends Model
{

  use seasonalScheduleAttributes;

  public function fromArray($data)
  {
    $months = array_keys(get_object_vars($this));
    $this->setAll(false);
    foreach ($data as $month) {
      if(in_array($month, $months)) {
        $this->{$month} = true;
      }
    }
  }

  public function setAll($value = true)
  {
    $keys = [
        'january',
        'february',
        'march',
        'april',
        'may',
        'june',
        'july',
        'august',
        'september',
        'october',
        'november',
        'december',
    ];
    foreach ($keys as $key) {
      $this->{$key} = $value;
    }
  }

  public function set($attribute, $value)
  {
    $keys = [
        'january',
        'february',
        'march',
        'april',
        'may',
        'june',
        'july',
        'august',
        'september',
        'october',
        'november',
        'december',
    ];
    foreach ($keys as $key) {
      if($attribute === $key){
        $this->{$key} = $value;
      }
    }
  }

  /**
   * @param  bool  $january
   */
  public function setJanuary($january)
  {
    $this->january = $january;
  }

  /**
   * @param  bool  $february
   */
  public function setFebruary($february)
  {
    $this->february = $february;
  }

  /**
   * @param  bool  $march
   */
  public function setMarch($march)
  {
    $this->march = $march;
  }

  /**
   * @param  bool  $april
   */
  public function setApril($april)
  {
    $this->april = $april;
  }

  /**
   * @param  bool  $may
   */
  public function setMay($may)
  {
    $this->may = $may;
  }

  /**
   * @param  bool  $june
   */
  public function setJune($june)
  {
    $this->june = $june;
  }

  /**
   * @param  bool  $july
   */
  public function setJuly($july)
  {
    $this->july = $july;
  }

  /**
   * @param  bool  $august
   */
  public function setAugust(bool $august): void
  {
    $this->august = $august;
  }

  /**
   * @param  bool  $september
   */
  public function setSeptember($september)
  {
    $this->september = $september;
  }

  /**
   * @param  bool  $october
   */
  public function setOctober($october)
  {
    $this->october = $october;
  }

  /**
   * @param  bool  $november
   */
  public function setNovember($november)
  {
    $this->november = $november;
  }

  /**
   * @param  bool  $december
   */
  public function setDecember($december)
  {
    $this->december = $december;
  }
}