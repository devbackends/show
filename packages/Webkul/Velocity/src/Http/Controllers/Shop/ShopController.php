<?php

namespace Webkul\Velocity\Http\Controllers\Shop;

use Illuminate\Http\Request;
use Webkul\Attribute\Models\AttributeOption;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\SAASCustomizer\Models\Attribute\Attribute;
use Webkul\Velocity\Http\Shop\Controllers;
use Webkul\Checkout\Contracts\Cart as CartModel;
use Cart;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
    /**
     * Index to handle the view loaded with the search results
     *
     * @return \Illuminate\View\View
     */
    public function search()
    {
        $results = app(ProductRepository::class)->getAll();


        if ($results) {
            $productItems = $results->items();

            if ($productItems) {
                $formattedProducts =[];

                foreach ($productItems as $product) {
                    array_push($formattedProducts, $this->velocityHelper->formatProduct($product));
                }

                $viewPagination = $results->appends(request()->input())->links();

                $productsArray = $results->toArray();
                $productsArray['data'] = $formattedProducts;
                $productsArray['viewPagination'] = $viewPagination;
                $results = $productsArray;
            }
        }

        return view($this->_config['view'])->with('results', $results ? $results : null);
    }

    public function fetchProductDetails($slug)
    {
        $product = $this->productRepository->findBySlug($slug);

        if ($product) {
            $productReviewHelper = app('Webkul\Product\Helpers\Review');

            $galleryImages = $this->productImageHelper->getProductBaseImage($product);

            $response = [
                'status'  => true,
                'details' => [
                    'name'         => $product->name,
                    'urlKey'       => $product->url_key,
                    'priceHTML'    => $product->getTypeInstance()->getPriceHtml(),
                    'productPrice'    => $product->getTypeInstance()->getPrice(),
                    'totalReviews' => $productReviewHelper->getTotalReviews($product),
                    'rating'       => ceil($productReviewHelper->getAverageRating($product)),
                    'image'        => $galleryImages['small_image_url'],
                ]
            ];
        } else {
            $response = [
                'status' => false,
                'slug'   => $slug,
            ];
        }

        return $response;
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function categoryDetails()
    {
        $slug = request()->get('category-slug');

        switch ($slug) {
            case 'new-products':
            case 'featured-products':
                $formattedProducts = [];
                $count = request()->get('count');

                if ($slug == "new-products") {
                    $products = $this->velocityProductRepository->getNewProducts($count);
                } else if ($slug == "featured-products") {
                    $products = $this->velocityProductRepository->getFeaturedProducts($count);
                }

                foreach ($products as $product) {
                    array_push($formattedProducts, $this->velocityHelper->formatProduct($product));
                }

                $response = [
                    'status'   => true,
                    'products' => $formattedProducts,
                ];

                break;
            default:
                $categoryDetails = $this->categoryRepository->findByPath($slug);

                if ($categoryDetails) {
                    $list = false;
                    $customizedProducts = [];
                    $products = $this->productRepository->getAll($categoryDetails->id);

                    foreach ($products as $product) {
                        $productDetails = [];

                        $productDetails = array_merge($productDetails, $this->velocityHelper->formatProduct($product));

                        array_push($customizedProducts, $productDetails);
                    }

                    $response = [
                        'status'           => true,
                        'list'             => $list,
                        'categoryDetails'  => $categoryDetails,
                        'categoryProducts' => $customizedProducts,
                    ];
                }

                break;
        }

        return $response ?? [
            'status' => false,
        ];
    }

    /**
     * @return array
     */
    public function fetchCategories()
    {
        $formattedCategories = [];
        $categories = $this->categoryRepository->getVisibleCategoryTree(core()->getCurrentChannel()->root_category_id);
        $productCategories =DB::select("select category_id from `product_categories` Group By `category_id`");
        $productCategoriesArray=[];
        foreach($productCategories as $productCategory){
           array_push($productCategoriesArray,$productCategory->category_id);
         }
        foreach ($categories as $key => $category) {
            //check if a category has a product, if not hide it
            if(in_array($category->id, $productCategoriesArray)){
                $children=$category->children;
                if($children->count()){
                    foreach ($children as $index => $subcategory){
                        if(!in_array($subcategory->id, $productCategoriesArray)){
                            $children->forget($index);
                        }
                    }
                    $category->children=$children;
                }
                array_push($formattedCategories, $this->getCategoryFilteredData($category));
            }
        }

        return [
            'status'     => true,
            'categories' => $formattedCategories,
        ];
    }

    /**
     * @return array
     */
    public function fetchAttributes()
    {
        return [
            'status'     => true,
            'attributes' => Attribute::all(),
        ];
    }

    public function fetchAttribute(int $id)
    {
        return [
            'status' => true,
            'attribute' => Attribute::query()->find($id),
        ];
    }

    public function fetchAttributeOptions(int $id)
    {
        return [
            'status' => true,
            'options' => AttributeOption::query()->where('attribute_id' , '=', $id)->get(),
        ];
    }

    /**
     * @param  string  $slug
     * @return array
     */
    public function fetchFancyCategoryDetails($slug)
    {
        $categoryDetails = $this->categoryRepository->findByPath($slug);

        if ($categoryDetails) {
            $response = [
                'status'          => true,
                'categoryDetails' => $this->getCategoryFilteredData($categoryDetails)
            ];
        }

        return $response ?? [
            'status' => false,
        ];
    }

    /**
     * @param  \Webkul\Category\Contracts\Category  $category
     * @return array
     */
    private function getCategoryFilteredData($category)
    {
        $formattedChildCategory = [];

        foreach ($category->children as $child) {
            array_push($formattedChildCategory, $this->getCategoryFilteredData($child));
        }

        return [
            'id'                 => $category->id,
            'slug'               => $category->slug,
            'name'               => $category->name,
            'children'           => $formattedChildCategory,
            'category_icon_path' => $category->category_icon_path,
        ];
    }

    /**
     * @return \Illuminate\View\View
     */
    public function getWishlistList()
    {
        return view($this->_config['view']);
    }

    /**
     * this function will provide the count of wishlist and comparison for logged in user
     *
     * @return \Illuminate\Http\Response
     */
    public function getItemsCount()
    {
        if ($customer = auth()->guard('customer')->user()) {
            $wishlistItemsCount = $this->wishlistRepository->count([
                'customer_id' => $customer->id,
                'channel_id'  => core()->getCurrentChannel()->id,
            ]);

            $comparedItemsCount = $this->compareProductsRepository->count([
                'customer_id' => $customer->id,
            ]);

            $response = [
                'status' => true,
                'compareProductsCount'    => $comparedItemsCount,
                'wishlistedProductsCount' => $wishlistItemsCount,
            ];
        }

        return response()->json($response ?? [
            'status' => false
        ]);
    }

    /**
     * This function will provide details of multiple product
     *
     * @return \Illuminate\Http\Response
     */
    public function getDetailedProducts()
    {
        // for product details
        if ($items = request()->get('items')) {
            $moveToCart = request()->get('moveToCart');

            $productCollection = $this->velocityHelper->fetchProductCollection($items, $moveToCart);

            $response = [
                'status'   => 'success',
                'products' => $productCollection,
            ];
        }

        return response()->json($response ?? [
            'status' => false
        ]);
    }

    public function getCategoryProducts($categoryId)
    {
        $products = $this->productRepository->getAll($categoryId);
        $productItems = $products->items();

        if ($productItems) {
            $formattedProducts =[];

            foreach ($productItems as $product) {
                array_push($formattedProducts, $this->velocityHelper->formatProduct($product));
            }

            $productsArray = $products->toArray();
            $productsArray['data'] = $formattedProducts;
        }

        return response()->json($response ?? [
            'products'       => $productsArray,
            'paginationHTML' => $products->appends(request()->input())->links()->toHtml(),
        ]);
    }
    public function autocompleteProduct(Request $request){

        $term= $request->request->get('term');
        $results = DB::select("SELECT * FROM  product_search_suggestion WHERE manufacturer LIKE '%".$term."%' or caliber LIKE '%".$term."%' or gauge LIKE '%".$term."%' or baarell_length LIKE '%".$term."%' or  `year` LIKE '%".$term."%' or `finish` LIKE '%".$term."%' or `capacity` LIKE '%".$term."%' or `overall_length` LIKE '%".$term."%' or `weight` LIKE '%".$term."%'   ORDER BY manufacturer ASC");

        $cityList = array();

        foreach($results as $city){
            $cityList[] = $city->manufacturer;
        }
        return json_encode($cityList);

    }
    public function comingSoon()
    {

        return view($this->_config['view']);
    }
}
