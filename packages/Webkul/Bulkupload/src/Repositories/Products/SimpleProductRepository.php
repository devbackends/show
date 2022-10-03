<?php

namespace Webkul\Bulkupload\Repositories\Products;

use Carbon\Carbon;
use Illuminate\Container\Container as App;
use Webkul\Admin\Imports\DataGridImport;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Core\Eloquent\Repository;
use Webkul\Bulkupload\Repositories\ImportNewProductsByAdminRepository as ImportNewProducts;
use Webkul\Product\Repositories\ProductFlatRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Attribute\Repositories\AttributeFamilyRepository as AttributeFamily;
use Webkul\Bulkupload\Repositories\Products\HelperRepository;
use Illuminate\Support\Facades\Validator;
use Webkul\Attribute\Repositories\AttributeOptionRepository;
use Webkul\Bulkupload\Repositories\ProductImageRepository as BulkUploadImages;
use Storage;

/**
 * BulkProduct Repository
 *
 */
class SimpleProductRepository extends Repository
{

    protected $importNewProducts;

    protected $categoryRepository;

    protected $productFlatRepository;

    protected $productRepository;

    protected $attributeFamily;

    protected $helperRepository;

    protected $bulkUploadImages;

    protected $attributeOptionRepository;


    public function __construct(
        ImportNewProducts $importNewProducts,
        AttributeOptionRepository $attributeOptionRepository,
        CategoryRepository $categoryRepository,
        ProductFlatRepository $productFlatRepository,
        ProductRepository $productRepository,
        AttributeFamily $attributeFamily,
        HelperRepository $helperRepository,
        BulkUploadImages $bulkUploadImages
    )
    {
        $this->importNewProducts = $importNewProducts;

        $this->categoryRepository = $categoryRepository;

        $this->attributeOptionRepository = $attributeOptionRepository;

        $this->productFlatRepository = $productFlatRepository;

        $this->productRepository = $productRepository;

        $this->bulkUploadImages = $bulkUploadImages;

        $this->attributeFamily = $attributeFamily;

        $this->helperRepository = $helperRepository;

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
        $demo = 0;
        $vendor_id=$requestData['seller'];
        $dataFlowProfileRecord = $this->importNewProducts->findOneByField('data_flow_profile_id', $requestData['data_flow_profile_id']);

        $csvData = (new DataGridImport)->toArray($dataFlowProfileRecord->file_path)[0];
        $processRecords = (int)$requestData['countOfStartedProfiles'] + (int)$requestData['numberOfCSVRecord'];

        for ($i = $requestData['countOfStartedProfiles']; $i < $processRecords; $i++)
        {   $csvData[$i]['vendor_id']=$vendor_id;
            $createValidation = $this->helperRepository->createProductValidation($csvData[$i], $i);

            if (isset($createValidation)) {
                return $createValidation;
            }

            $categoryData = explode(',', $csvData[$i]['categories_slug']);

            foreach ($categoryData as $key => $value)
            {
                $categoryID[$key] = $this->categoryRepository->findBySlugOrFail($categoryData[$key])->id;
            }

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

            if (!isset($productFlatData) && empty($productFlatData)) {
                $data['type'] = $csvData[$i]['type'];
                $data['attribute_family_id'] = $attributeFamilyData->id;
                $data['sku'] = $csvData[$i]['sku'];
                $data['quantity'] =  $csvData[$i]['inventories'];
                $simpleproductData = $this->productRepository->create($data);
                $productFlat = $this->productFlatRepository->findWhere(['product_id' => $simpleproductData->id ])->first();
                $productFlat->quantity=$data['quantity'];
                $productFlat->save();
            } else {
                $productFlatData->quantity=$csvData[$i]['inventories'];
                $productFlatData->save();
                $simpleproductData = $productData;
            }

            unset($data);
            $data = array();

            $attributeCode = array();
            $attributeValue = array();

            //default attributes
            if($simpleproductData){
                foreach ($simpleproductData->getTypeInstance()->getEditableAttributes()->toArray() as $key => $value)
                {
                    $searchIndex = $value['code'];

                    if (array_key_exists($searchIndex, $csvData[$i])) {
                        array_push($attributeCode, $searchIndex);

                        $attribute=app('Webkul\Attribute\Repositories\AttributeRepository')->findWhere(['code'=>$searchIndex])->first();
                        if ($attribute->type=='select' || $attribute->type=='multiselect' ){
                            $attributeOption = $this->attributeOptionRepository->findOneByField(['admin_name' => ucwords($csvData[$i][$searchIndex])]);
                            if(is_array($attributeOption)){
                                array_push($attributeValue, $attributeOption['id']);
                            }elseif(isset($attributeOption->id)) {
                                array_push($attributeValue, $attributeOption->id);
                            }
                            else{
                                array_push($attributeValue, $csvData[$i][$searchIndex]);
                            }

                        }elseif($attribute->type=='date'){
                            $date=Carbon::parse($csvData[$i][$searchIndex])->format('Y-m-d');
                            array_push($attributeValue,$date);
                            $data[$searchIndex] = $date;
                        } else {
                            array_push($attributeValue, $csvData[$i][$searchIndex]);
                        }

                        $data = array_combine($attributeCode, $attributeValue);
                    }
                }
            }


            $data['dataFlowProfileRecordId'] = $dataFlowProfileRecord->id;
            $data['vendor_id'] = $vendor_id;



            $categoryData = explode(',', $csvData[$i]['categories_slug']);

            foreach ($categoryData as $key => $value)
            {
                $categoryID[$key] = $this->categoryRepository->findBySlugOrFail($categoryData[$key])->id;
            }

            $data['categories'] = $categoryID;

            $data['channel'] = core()->getCurrentChannel()->code;
            $data['locale'] = core()->getCurrentLocale()->code;

            //Product Images
            $individualProductimages = explode(',', $csvData[$i]['images']);
            if (isset($imageZipName)) {
                $images = Storage::disk('local')->files( 'public/imported-products/extracted-images/admin/'.$dataFlowProfileRecord->id.'/'.$imageZipName['dirname'].'/');
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

            $returnRules = $this->helperRepository->validateCSV($requestData['data_flow_profile_id'], $data, $dataFlowProfileRecord, $simpleproductData);



            $csvValidator = Validator::make($data, $returnRules);

            if ($csvValidator->fails()) {
                $errors = $csvValidator->errors()->getMessages();

                $this->helperRepository->deleteProductIfNotValidated($simpleproductData->id);

                foreach($errors as $key => $error)
                {
                    if ($error[0] == "The url key has already been taken.") {
                        $errorToBeReturn[] = "The url key " . $data['url_key'] . " has already been taken";
                    } else {
                        $errorToBeReturn[] = str_replace(".", "", $error[0]). " for sku " . $data['sku'];
                    }
                }

                $requestData['countOfStartedProfiles'] =  $i + 1;

                $productsUploaded = $i - $requestData['errorCount'];

                $dataToBeReturn = array(
                    'remainDataInCSV' => 0, /*$remainDataInCSV*/
                    'productsUploaded' => $productsUploaded,
                    'countOfStartedProfiles' => $requestData['countOfStartedProfiles'],
                    'error' => $errorToBeReturn,
                );

                return $dataToBeReturn;
            }
            $data['quantity'] =  $csvData[$i]['inventories'];
            $configSimpleProductAttributeStore = $this->productRepository->update($data, $simpleproductData->id);

            if (isset($imageZipName)) {
                $this->bulkUploadImages->bulkuploadImages($data, $simpleproductData,    $imageZipName);
            }

        }

        $requestData['countOfStartedProfiles'] = $i;

        $dataToBeReturn = [
            'remainDataInCSV' => 0, /*$remainDataInCSV*/
            'productsUploaded' => $processRecords, /*$uptoProcessCSVRecords*/
            'countOfStartedProfiles' => $requestData['countOfStartedProfiles'],
        ];

        return $dataToBeReturn;
    }
}
