<?php


namespace Devvly\Clearent\Models;

class Payload implements IPayload
{

  /**
   * @var string
   */
  protected $payloadType;

  /** @var int */
  protected $resultCode;

  /** @var string */
  protected $timestamp;

  /** @var Link[]|null */
  protected $links;

  /**
   * Payload constructor.
   *
   * @param  string  $payloadType
   * @param  mixed  $content
   * @param  array  $links
   */
  public function __construct(string $payloadType, $content, $links = null)
  {
    $this->payloadType = $payloadType;
    if (is_array($content)) {
      if (isset($content['time-stamp'])) {
        $this->timestamp = $content['time-stamp'];
      }
      if (isset($content['result-code'])) {
        $this->resultCode = $content['result-code'];
      }
    }
    if (isset($content['links'])) {
      $this->initLinks($content['links']);
    }
    if (isset($links)) {
      $this->initLinks($links);
    }

  }

  protected function initLinks($data)
  {
    $links = null;
    if (isset($data['link'])) {
      $links = $data['link'];
    } else {
      $links = $data;
    }

    if (!$this->links && $links) {
      $this->links = [];
    }
    if ($links) {
      foreach ($links as $link) {
        $this->links = array_filter(
            $this->links,
            function (Link $item) use ($link) {
              return $item->getRel() !== $link['rel'];
            }
        );
        $this->links[] = new Link($link);
      }
    }
  }

  /**
   * @inheritDoc
   */
  public function getPayloadType(): string
  {
    return $this->payloadType;
  }

  /**
   * @inheritDoc
   */
  public function getResultCode()
  {
    return $this->resultCode;
  }

  /**
   * @inheritDoc
   */
  public function getTimestamp()
  {
    return $this->timestamp;
  }

  /**
   * @return Link[]|null
   */
  public function getLinks(): ?array
  {
    return $this->links;
  }


}