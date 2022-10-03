<?php


namespace Devvly\OnBoarding\Clearent\Responses\Equipment;


use Devvly\OnBoarding\Clearent\Responses\Response;

class Products extends Response
{
  /** @var ProductsPagination */
  protected $products;

  /**
   * @return ProductsPagination
   */
  public function getProducts(): ProductsPagination
  {
    return $this->products;
  }

  /**
   * @param  array  $products
   */
  protected function setProducts($products): void
  {
    $this->products = new ProductsPagination($products);
  }
}