<?php


namespace Devvly\Clearent\Models;


interface IPayload
{

  /**
   * @return string
   */
  public function getPayloadType(): string;

  /**
   * @return int|null
   */
  public function getResultCode();

  /**
   * @return string|null
   */
  public function getTimestamp();
}