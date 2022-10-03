<?php

namespace Webkul\Bulkupload\Repositories\Products;

use Carbon\Carbon;
use Illuminate\Container\Container as App;
use Webkul\Admin\Imports\DataGridImport;
use Illuminate\Support\Facades\Schema;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Product\Models\ProductAttributeValue;
use Webkul\Core\Eloquent\Repository;
use Webkul\Bulkupload\Repositories\ImportNewProductsByAdminRepository as ImportNewProducts;
use Webkul\Product\Repositories\ProductFlatRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Attribute\Repositories\AttributeFamilyRepository as AttributeFamily;
use Webkul\Bulkupload\Repositories\Products\HelperRepository;
use Illuminate\Support\Facades\Validator;
use Webkul\Bulkupload\Repositories\ProductImageRepository as BulkUploadImages;
use Storage;
use Webkul\Attribute\Repositories\AttributeOptionRepository;
use Webkul\Bulkupload\Repositories\BulkProductRepository;
use DB;
use Webkul\Bulkupload\Repositories\Products\SimpleProductRepository;

/**
 * BulkProduct Repository
 *
 */
class ConfigurableProductRepository extends Repository
{
    protected $importNewProducts;

    protected $categoryRepository;

    protected $productFlatRepository;

    protected $productRepository;

    protected $attributeFamily;

    protected $helperRepository;

    protected $bulkUploadImages;

    protected $bulkProductRepository;

    protected $simpleProductRepository;

    protected $attributeOptionRepository;

    public function __construct(
        ImportNewProducts $importNewProducts,
        CategoryRepository $categoryRepository,
        ProductFlatRepository $productFlatRepository,
        ProductRepository $productRepository,
        AttributeFamily $attributeFamily,
        AttributeRepository $attribute,
        HelperRepository $helperRepository,
        BulkUploadImages $bulkUploadImages,
        BulkProductRepository $bulkProductRepository,
        SimpleProductRepository $simpleProductRepository,
        AttributeOptionRepository $attributeOptionRepository
    )
    {
        $this->importNewProducts = $importNewProducts;

        $this->categoryRepository = $categoryRepository;

        $this->productFlatRepository = $productFlatRepository;

        $this->productRepository = $productRepository;

        $this->bulkUploadImages = $bulkUploadImages;

        $this->attributeFamily = $attributeFamily;

        $this->attribute = $attribute;

        $this->helperRepository = $helperRepository;

        $this->bulkProductRepository = $bulkProductRepository;

        $this->simpleProductRepository = $simpleProductRepository;

        $this->attributeOptionRepository = $attributeOptionRepository;
    }

    /*
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Product\Contracts\Product';
    }

    /**
     *
     */
    public function createProduct($requestData, $imageZipName, $product)
    {
        $vendor_id=$requestData['seller'];
        if ($requestData['totalNumberOfCSVRecord'] < 1000) {
            $processCSVRecords = $requestData['totalNumberOfCSVRecord']/($requestData['totalNumberOfCSVRecord']/10);
        } else {
            $processCSVRecords = $requestData['totalNumberOfCSVRecord']/($requestData['totalNumberOfCSVRecord']/100);
        }

        $dataFlowProfileRecord = $this->importNewProducts->findOneByField('data_flow_profile_id', $requestData['data_flow_profile_id']);

        if ($dataFlowProfileRecord) {

            $csvData = (new DataGridImport)->toArray($dataFlowProfileRecord->file_path)[0];

            foreach ($csvData as $key => $value)
            {
                if ($requestData['numberOfCSVRecord'] >= 0) {
                    for ($i = $requestData['countOfStartedProfiles']; $i < count($csvData); $i++)
                    {
                        $product['loopCount'] = $i;

                        if ($csvData[$i]['type'] == 'configurable') {
                            try {
                                $createValidation = $this->helperRepository->createProductValidation($csvData[$i], $i);

                                if (isset($createValidation)) {
                                    return $createValidation;
                                }

                                $csvData = (new DataGridImport)->toArray($dataFlowProfileRecord->file_path)[0];

                                $categoryData = explode(',', $csvData[$i]['categories_slug']);

                                foreach ($categoryData as $key => $value)
                                {
                                    $categoryID[$key] = $this->categoryRepository->findBySlugOrFail($categoryData[$key])->id;
                                }

                                unset($data);

                                $productFlatData = $this->productFlatRepository->findWhere(['sku' => $csvData[$i]['sku'], 'url_key' => $csvData[$i]['url_key']])->first();

                                $productData = $this->productRepository->findWhere(['sku' => $csvData[$i]['sku']])->first();

                                $attributeFamilyData = $this->attributeFamily->findOneByfield(['name' => $csvData[$i]['attribute_family_name']]);

                                if(!$attributeFamilyData){
                                    $line=$i+1;
                                    return  array(
                                        'remainDataInCSV' => 0,
                                        'productsUploaded' => $i,
                                        'countOfStartedProfiles' => $requestData['countOfStartedProfiles'],
                                        'error' => ['The attribute family on line '.$line.' is not found'],
                                    );
                                }
                                if (! isset($productFlatData) && empty($productFlatData)) {
                                    $data['type'] = $csvData[$i]['type'];
                                    $data['attribute_family_id'] = $attributeFamilyData->id;
                                    $data['sku'] = $csvData[$i]['sku'];
                                    $product = $this->bulkProductRepository->create($data);
                                    if(isset($csvData[$i]['inventories'])){
                                        $productFlat = $this->productFlatRepository->findWhere(['product_id' => $product->id ])->first();
                                        $productFlat->quantity=$csvData[$i]['inventories'];
                                        $productFlat->save();
                                    }

                                } else {
                                    $product = $productData;
                                }



                                unset($data);
                                $data = array();
                                $attributeCode = array();
                                $attributeValue = array();

                                foreach ($product->getTypeInstance()->getEditableAttributes()->toArray() as $key => $value)
                                {
                                    $searchIndex = $value['code'];

                                    if (array_key_exists($searchIndex, $csvData[$i])) {
                                        array_push($attributeCode, $searchIndex);

                                        $attribute=app('Webkul\Attribute\Repositories\AttributeRepository')->findWhere(['code'=>$searchIndex])->first();
                                        if ($attribute->type=='select' || $attribute->type=='multiselect' ){
                                            $attributeOption = $this->attributeOptionRepository->findOneByField(['admin_name' => ucwords($csvData[$i][$searchIndex])]);
                                            if(is_array($attributeOption)) {
                                                array_push($attributeValue, $attributeOption['id']);
                                            }
                                            elseif(isset($attributeOption->id)) {
                                                array_push($attributeValue, $attributeOption->id);
                                            }
                                            else{
                                                array_push($attributeValue, $csvData[$i][$searchIndex]);
                                            }
                                        }
                                        elseif($attribute->type=='date'){
                                            $date=Carbon::parse($csvData[$i][$searchIndex])->format('Y-m-d');
                                            array_push($attributeValue,$date);
                                            $data[$searchIndex] = $date;
                                        }
                                        else {
                                            array_push($attributeValue, $csvData[$i][$searchIndex]);
                                        }

                                        $data = array_combine($attributeCode, $attributeValue);
                                    }
                                }

                                $data['dataFlowProfileRecordId'] = $dataFlowProfileRecord->id;
                                $data['channel'] = core()->getCurrentChannel()->code;
                                $data['locale'] = core()->getCurrentLocale()->code;
                                $data['categories'] = $categoryID;

                                $individualProductimages = explode(',', $csvData[$i]['images']);

                                if (isset($imageZipName)) {
                                    $images = Storage::disk('local')->files('public/imported-products/extracted-images/admin/'.$dataFlowProfileRecord->id.'/'.$imageZipName['dirname'].'/');

                                    foreach ($images as $imageArraykey => $imagePath)
                                    {
                                        if(strpos($imagePath, "__MACOSX/._") !== false){ $imagePath=str_replace("__MACOSX/._","",$imagePath); }
                                        if(strpos($imagePath, "__MACOSX/") !== false){ $imagePath=str_replace("__MACOSX/","",$imagePath); }
                                        $imageName = explode('/', $imagePath);

                                        if (in_array(last($imageName), preg_replace('/[\'"]/', '',$individualProductimages))) {
                                            $data['images'][$imageArraykey] = $imagePath;
                                        }
                                    }
                                }

                                if (isset($imageZipName)) {
                                    $this->bulkUploadImages->bulkuploadImages($data, $product,    $imageZipName);
                                }

                                $productAttributeStore = $this->bulkProductRepository->productRepositoryUpdateForVariants($data, $product->id);

                                if (! isset($productFlatData) && empty($productFlatData)) {
                                    $productFlatData = DB::table('product_flat')->select('id')->orderBy('id', 'desc')->first();
                                }

                                $product['productFlatId'] = $productFlatData->id;

                                $arr[] = $productFlatData->id;
                                unset($categoryID);
                            } catch (\Exception $e) {

                                $categoryError = explode('[' ,$e->getMessage());
                                $categorySlugError = explode(']' ,$e->getMessage());

                                $error = $e;

                                $productUploadedWithError = $requestData['productUploaded'] + 1;
                                $remainDataInCSV = $requestData['totalNumberOfCSVRecord'] - $productUploadedWithError;
                                $requestData['countOfStartedProfiles'] = $i + 1;

                                if ($categoryError[0] == "No query results for model ") {
                                    $dataToBeReturn = array(
                                        'remainDataInCSV' => $remainDataInCSV,
                                        'productsUploaded' => $requestData['productUploaded'],
                                        'countOfStartedProfiles' => $requestData['countOfStartedProfiles'],
                                        'error' => "Invalid Category Slug: " . $categorySlugError[1],
                                    );
                                    $categoryError[0] = null;
                                } else if (isset($e->errorInfo)) {
                                    $dataToBeReturn = array(
                                        'remainDataInCSV' => $remainDataInCSV,
                                        'productsUploaded' => $requestData['productUploaded'],
                                        'countOfStartedProfiles' => $requestData['countOfStartedProfiles'],
                                        'error' => $e->errorInfo[2],
                                    );
                                } else {
                                    $dataToBeReturn = array(
                                        'remainDataInCSV' => $remainDataInCSV,
                                        'productsUploaded' => $requestData['productUploaded'],
                                        'countOfStartedProfiles' => $requestData['countOfStartedProfiles'],
                                        'error' => $e->getMessage(),
                                    );
                                }
                                return $dataToBeReturn;
                            }
                        } else if (isset($product['productFlatId'])) {
                            try {
                                $current = $product['loopCount'];
                                $num = 0;
                                $inventory = [];

                                $csvData = (new DataGridImport)->toArray($dataFlowProfileRecord->file_path)[0];

                                for ($i = $current; $i < count($csvData); $i++)
                                {
                                    $product['loopCount'] = $i;

                                    if ($csvData[$i]['type'] != 'configurable') {
                                        unset($data);
                                        $productFlatData = $this->productFlatRepository->findWhere(['sku' => $csvData[$i]['sku'], 'url_key' => null])->first();

                                        $productData = $this->productRepository->findWhere(['sku' => $csvData[$i]['sku']])->first();

                                        $attributeFamilyData = $this->attributeFamily->findOneByfield(['name' => $csvData[$i]['attribute_family_name']]);

                                        if(!$attributeFamilyData){
                                            $line=$i+1;
                                            return  array(
                                                'remainDataInCSV' => 0,
                                                'productsUploaded' => $i,
                                                'countOfStartedProfiles' => $requestData['countOfStartedProfiles'],
                                                'error' => ['The attribute family on line '.$line.' is not found'],
                                            );
                                        }

                                        if (! isset($productFlatData) && empty($productFlatData)) {
                                            $data['parent_id'] = $product->id;
                                            $data['type'] = "simple";
                                            $data['attribute_family_id'] = $attributeFamilyData->id;
                                            $data['sku'] = $csvData[$i]['sku'];

                                            $configSimpleproduct = $this->productRepository->create($data);
                                        } else {
                                            $configSimpleproduct = $productData;
                                        }


                                        unset($data);

                                        $validateVariant = Validator::make($csvData[$i], [
                                            'sku' => ['required', 'unique:products,sku,' . $configSimpleproduct->id, new \Webkul\Core\Contracts\Validations\Slug],
                                            'name' => 'required',
                                            'super_attribute_price' => 'required',
                                            'super_attribute_weight' => 'required',
                                            'super_attribute_option' => 'required',
                                            'super_attributes' => 'required'
                                        ]);

                                        if ($validateVariant->fails()) {
                                            $errors = $validateVariant->errors()->getMessages();

                                            $this->helperRepository->deleteProductIfNotValidated($product->id);

                                            foreach($errors as $key => $error)
                                            {
                                                $errorToBeReturn[] = str_replace(".", "", $error[0]). " for sku " .$csvData[$i]['sku'];
                                            }
                                            $productUploadedWithError = $requestData['productUploaded'] + 1;

                                            $requestData['countOfStartedProfiles'] = $i + 1;

                                            if ($requestData['numberOfCSVRecord'] != 0) {
                                                $remainDataInCSV = $requestData['totalNumberOfCSVRecord'] - $productUploadedWithError;
                                            } else {
                                                $remainDataInCSV = 0;
                                            }

                                            $dataToBeReturn = array(
                                                'remainDataInCSV' => $remainDataInCSV,
                                                'productsUploaded' => $requestData['productUploaded'],
                                                'countOfStartedProfiles' => $requestData['countOfStartedProfiles'],
                                                'error' => $errorToBeReturn,
                                            );

                                            return $dataToBeReturn;
                                        }

                                        $data['vendor_id'] = $vendor_id;

                                        $data['quantity'] =  $csvData[$i]['super_attribute_qty'];

                                        $superAttributes = explode(',', $csvData[$i]['super_attributes']);
                                        $superAttributesOption = explode(',', $csvData[$i]['super_attribute_option']);

                                        $attOptionArr=[];
                                        foreach ($superAttributesOption as $option){
                                            $attrOption = app('Webkul\Attribute\Repositories\AttributeOptionRepository')->findwhere(['admin_name' => $option])->first();

                                            if($attrOption){
                                                array_push($attOptionArr,$attrOption->id);
                                            }else{
                                                if(!$attributeFamilyData){
                                                    $line=$i+1;
                                                    return  array(
                                                        'remainDataInCSV' => 0,
                                                        'productsUploaded' => $i,
                                                        'countOfStartedProfiles' => $requestData['countOfStartedProfiles'],
                                                        'error' => ['The super_attribute_option on line '.$line.' is not found'],
                                                    );
                                                }
                                            }
                                        }

                                        $data['super_attributes'] = array_combine($superAttributes, $superAttributesOption);

                                        if (isset($data['super_attributes']) && $i == $current) {
                                            $super_attributes = [];

                                            foreach ($data['super_attributes'] as $attributeCode => $attributeOptions) {
                                                $attribute = $this->attribute->findOneByField('code', $attributeCode);

                                                $super_attributes[$attribute->id] = $attributeOptions;

                                                $users = $product->super_attributes()->where('id', $attribute->id)->exists();

                                                if (! $users) {
                                                    $product->super_attributes()->attach($attribute->id,['selected_attribute_options' => json_encode($attOptionArr) ]);
                                                }
                                            }
                                        }

                                        $data['channel'] = core()->getCurrentChannel()->code;
                                        $data['locale'] = core()->getCurrentLocale()->code;
                                        $data['dataFlowProfileRecordId'] = $dataFlowProfileRecord->id;
                                        $data['price'] = (string)$csvData[$i]['super_attribute_price'];
                                        $data['special_price'] = (string)$csvData[$i]['special_price'];
                                        $data['special_price_from'] = (string)$csvData[$i]['special_price_from'];
                                        $data['special_price_to'] = (string)$csvData[$i]['special_price_to'];
                                        $data['new'] = (string)$csvData[$i]['new'];
                                        $data['featured'] = (string)$csvData[$i]['featured'];
                                        $data['tax_category_id'] = (string)$csvData[$i]['tax_category_id'];
                                        $data['cost'] = (string)$csvData[$i]['cost'];
                                        $data['width'] = (string)$csvData[$i]['width'];
                                        $data['height'] = (string)$csvData[$i]['height'];
                                        $data['depth'] = (string)$csvData[$i]['depth'];
                                        $data['status'] = (string)$csvData[$i]['status'];
                                        $data['attribute_family_id'] = $attributeFamilyData->id;
                                        $data['short_description'] = (string)$csvData[$i]['short_description'];
                                        $data['sku'] = (string)$csvData[$i]['sku'];
                                        $data['name'] = (string)$csvData[$i]['name'];
                                        $data['weight'] = (string)$csvData[$i]['super_attribute_weight'];
                                        $data['status'] = (string)$csvData[$i]['status'];

                                        $attributeOptionColor = $this->attributeOptionRepository->findOneByField('admin_name', $data['super_attributes']['color']);
                                        $attributeOptionSize = $this->attributeOptionRepository->findOneByField('admin_name', $data['super_attributes']['size']);

                                        $data['color'] = $attributeOptionColor->id;
                                        $data['size'] = $attributeOptionSize->id;

                                        $configSimpleProductAttributeStore = $this->bulkProductRepository->productRepositoryUpdateForVariants($data, $configSimpleproduct->id);


                                        $configSimpleProductAttributeStore['parent_id'] = $product['productFlatId'];

                                        $this->createFlat($configSimpleProductAttributeStore);

                                        $individualProductimages = explode(',', $csvData[$i]['images']);
                                        if (isset($imageZipName)) {
                                            $images = Storage::disk('local')->files('public/imported-products/extracted-images/admin/'.$dataFlowProfileRecord->id.'/'.$imageZipName['dirname'].'/');
                                            foreach ($images as $imageArraykey => $imagePath)
                                            {
                                                if(strpos($imagePath, "__MACOSX/._") !== false){ $imagePath=str_replace("__MACOSX/._","",$imagePath); }
                                                if(strpos($imagePath, "__MACOSX/") !== false){ $imagePath=str_replace("__MACOSX/","",$imagePath); }
                                                $imageName = explode('/', $imagePath);

                                                if (in_array(last($imageName), preg_replace('/[\'"]/', '',$individualProductimages))) {
                                                    $data['images'][$imageArraykey] = $imagePath;
                                                }
                                            }
                                        }
                                        if (isset($imageZipName)) {
                                            $this->bulkUploadImages->bulkuploadImages($data, $configSimpleproduct,    $imageZipName);
                                        }
                                    } else {
                                        $savedProduct = $requestData['productUploaded'] + 1;
                                        $remainDataInCSV = $requestData['totalNumberOfCSVRecord'] - $savedProduct;
                                        $productsUploaded = $savedProduct;

                                        $requestData['countOfStartedProfiles'] = $product['loopCount'];

                                        $dataToBeReturn = array(
                                            'remainDataInCSV' => $remainDataInCSV,
                                            'productsUploaded' => $productsUploaded,
                                            'countOfStartedProfiles' => $requestData['countOfStartedProfiles']
                                        );

                                        return $dataToBeReturn;
                                    }
                                }

                                if ($requestData['errorCount'] == 0) {
                                    $dataToBeReturn = [
                                        'remainDataInCSV' => 0,
                                        'productsUploaded' => $requestData['totalNumberOfCSVRecord'],
                                        'countOfStartedProfiles' => count($csvData),
                                    ];

                                    return $dataToBeReturn;
                                } else {
                                    $dataToBeReturn = [
                                        'remainDataInCSV' => 0,
                                        'productsUploaded' => $requestData['totalNumberOfCSVRecord'] - $requestData['errorCount'],
                                        'countOfStartedProfiles' => count($csvData),
                                    ];

                                    return $dataToBeReturn;
                                }

                                $product['productFlatId'] = null;
                            } catch (\Exception $e) {
                                dd($e);
                                $error = $e;
                                $requestData['countOfStartedProfiles'] = $i + 1;
                                $remainDataInCSV = $requestData['totalNumberOfCSVRecord'] - $requestData['productUploaded'];

                                $dataToBeReturn = array(
                                    'remainDataInCSV' => $remainDataInCSV,
                                    'productsUploaded' => $requestData['productUploaded'],
                                    'countOfStartedProfiles' => $requestData['countOfStartedProfiles'],
                                    'error' => $error->errorInfo[2] ?? $error->getMessage(),
                                );

                                return $dataToBeReturn;
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * save data to product flat
     */
    public function createFlat($product, $parentProduct = null)
    {
        static $familyAttributes = [];

        static $superAttributes = [];

        if (! array_key_exists($product->attribute_family->id, $familyAttributes))
            $familyAttributes[$product->attribute_family->id] = $product->attribute_family->custom_attributes;

        if ($parentProduct && ! array_key_exists($parentProduct->id, $superAttributes))
            $superAttributes[$parentProduct->id] = $parentProduct->super_attributes()->pluck('code')->toArray();

        foreach (core()->getAllChannels() as $channel) {
            foreach ($channel->locales as $locale) {
                $productFlat = $this->productFlatRepository->findOneWhere([
                    'product_id' => $product->id
                ]);

                if (! $productFlat) {
                    $productFlat = $this->productFlatRepository->create([
                        'product_id' => $product->id
                    ]);
                }
                foreach ($familyAttributes[$product->attribute_family->id] as $attribute) {
                    if ($parentProduct && ! in_array($attribute->code, array_merge($superAttributes[$parentProduct->id], ['sku', 'name', 'price', 'weight', 'status'])))
                        continue;

                    if (in_array($attribute->code, ['tax_category_id']))
                        continue;

                    if (! Schema::hasColumn('product_flat', $attribute->code))
                        continue;

                    if ($attribute->value_per_channel) {
                        if ($attribute->value_per_locale) {
                            $productAttributeValue = $product->attribute_values()->where('attribute_id', $attribute->id)->first();
                        } else {
                            $productAttributeValue = $product->attribute_values()->where('attribute_id', $attribute->id)->first();
                        }
                    } else {
                        if ($attribute->value_per_locale) {
                            $productAttributeValue = $product->attribute_values()->where('locale', $locale->code)->where('attribute_id', $attribute->id)->first();
                        } else {
                            $productAttributeValue = $product->attribute_values()->where('attribute_id', $attribute->id)->first();
                        }
                    }

                    if ($product->type == 'configurable' && $attribute->code == 'price') {
                        try {
                            $productFlat->{$attribute->code} = app('Webkul\Bulkupload\Helpers\Price')->getVariantMinPrice($product);
                        } catch(\Exception $e) {}
                    } else {
                        try {
                            $productFlat->{$attribute->code} = $productAttributeValue[ProductAttributeValue::$attributeTypeFields[$attribute->type]];
                        } catch(\Exception $e) {}
                    }

                    if ($attribute->type == 'select') {
                        $attributeOption = $this->attributeOptionRepository->find($product->{$attribute->code});

                        if ($attributeOption) {
                            if ($attributeOptionTranslation = $attributeOption->translate($locale->code)) {
                                $productFlat->{$attribute->code . '_label'} = $attributeOptionTranslation->label;
                            } else {
                                $productFlat->{$attribute->code . '_label'} = $attributeOption->admin_name;
                            }
                        }
                    } elseif ($attribute->type == 'multiselect') {
                        $attributeOptionIds = explode(',', $product->{$attribute->code});

                        if (count($attributeOptionIds)) {
                            $attributeOptions = $this->attributeOptionRepository->findWhereIn('id', $attributeOptionIds);

                            $optionLabels = [];

                            foreach ($attributeOptions as $attributeOption) {
                                if ($attributeOptionTranslation = $attributeOption->translate($locale->code)) {
                                    $optionLabels[] = $attributeOptionTranslation->label;
                                } else {
                                    $optionLabels[] = $attributeOption->admin_name;
                                }
                            }

                            $productFlat->{$attribute->code . '_label'} = implode(', ', $optionLabels);
                        }
                    }
                }

                $productFlat->created_at = $product->created_at;

                $productFlat->updated_at = $product->updated_at;

                if ($parentProduct) {
                    $parentProductFlat = $this->productFlatRepository->findOneWhere([

                        'product_id' => $parentProduct->id,
                        'channel' => $channel->code,
                        'locale' => $locale->code
                    ]);
                }
                $productFlat->parent_id = $product->parent_id;

                $productFlat->save();
            }
        }
    }
}
