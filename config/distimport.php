<?php

return [

    'diskName' => 'distributor_import',


    'files' => [
        'main' => [
            'keys' => [
                'rsrStockId',
                'upcCode',
                'shortDescription',
                'departmentId',
                'manufacturerId',
                'retailPrice',
                'rsrPrice',
                'weight',
                'quantity',
                'model',
                'manufacturerName',
                'manufacturerPartNumber',
                'status',
                'description',
                'image',
                'AK',
                'AL',
                'AR',
                'AZ',
                'CA',
                'CO',
                'CT',
                'DC',
                'DE',
                'FL',
                'GA',
                'HI',
                'IA',
                'ID',
                'IL',
                'IN',
                'KS',
                'KY',
                'LA',
                'MA',
                'MD',
                'ME',
                'MI',
                'MN',
                'MO',
                'MS',
                'MT',
                'NC',
                'ND',
                'NE',
                'NH',
                'NJ',
                'NM',
                'NV',
                'NY',
                'OH',
                'OK',
                'OR',
                'PA',
                'RI',
                'SC',
                'SD',
                'TN',
                'TX',
                'UT',
                'VA',
                'VT',
                'WA',
                'WI',
                'WV',
                'WY',
                'groundShipmentsOnly',
                'adultSignatureRequired',
                'blockedFromDropShip',
                'date',
                'retailMap',
                'imageDisclaimer',
                'length',
                'width',
                'height',
                'something',
                'something2',
                'something3'
            ],
            'content' => 'ftpdownloads/rsrinventory-new.txt',
            'attributes' => 'ftpdownloads/attributes-all.txt',
            'product-warnings' => 'ftpdownloads/rsr-product-message.txt',
            'descriptions' => 'ftpdownloads/product_sell_descriptions_unicode.xml',
            'deleteRsrProducts'=> 'ftpdownloads/rsrdeletedinv.txt',
            'inventory' => 'ftpdownloads/IM-QTY-CSV.csv',
            'restrictions' => 'ftpdownloads/rsr-ship-restrictions.txt',
            'customValidator' => Devvly\DistributorImport\CustomValidators\InvFileValidator::class,

        ],
    ],

    'customValidatorsConfigs' => [
        Devvly\DistributorImport\CustomValidators\InvFileValidator::class => [
            'stateRestrictions' => [
                'AK' => 'Alaska',
                'AL' => 'Alabama',
                'AR' => 'Arkansas',
                'AZ' => 'Arizona',
                'CA' => 'California',
                'CO' => 'Colorado',
                'CT' => 'Connecticut',
                'DC' => 'District of Columbia',
                'DE' => 'Delaware',
                'FL' => 'Florida',
                'GA' => 'Georgia',
                'HI' => 'Hawaii',
                'IA' => 'Iowa',
                'ID' => 'Idaho',
                'IL' => 'Illinois',
                'IN' => 'Indiana',
                'KS' => 'Kansas',
                'KY' => 'Kentucky',
                'LA' => 'Louisiana',
                'MA' => 'Massachusetts',
                'MD' => 'Maryland',
                'ME' => 'Maine',
                'MI' => 'Michigan',
                'MN' => 'Minnesota',
                'MO' => 'Missouri',
                'MS' => 'Mississippi',
                'MT' => 'Montana',
                'NC' => 'North Carolina',
                'ND' => 'North Dakota',
                'NE' => 'Nebraska',
                'NH' => 'New Hampshire',
                'NJ' => 'New Jersey',
                'NM' => 'New Mexico',
                'NV' => 'Nevada',
                'NY' => 'New York',
                'OH' => 'Ohio',
                'OK' => 'Oklahoma',
                'OR' => 'Oregon',
                'PA' => 'Pennsylvania',
                'RI' => 'Rhode Island',
                'SC' => 'South Carolina',
                'SD' => 'South Dakota',
                'TN' => 'Tennessee',
                'TX' => 'Texas',
                'UT' => 'Utah',
                'VA' => 'Virginia',
                'VT' => 'Vermont',
                'WA' => 'Washington',
                'WI' => 'Wisconsin',
                'WV' => 'West Virginia',
                'WY' => 'Wyoming',
            ],
            'departmentToFamily' => [
                '01' => ['name' => 'Handguns', 'familyId' => '48'], // Firearm
                '02' => ['name' => 'Used Handguns', 'familyId' => '48'], // Firearm
                '03' => ['name' => 'Used Long Guns', 'familyId' => '48'], // Firearm
                '04' => ['name' => 'Tasers', 'familyId' => '49'], // General
                '05' => ['name' => 'Long Guns', 'familyId' => '48'], // Firearm
                '06' => ['name' => 'NFA Products', 'familyId' => ''], // Exclude
                '07' => ['name' => 'Black Powder', 'familyId' => ''], // Exclude
                '08' => ['name' => 'Optics', 'familyId' => '58'], // Optics
                '09' => ['name' => 'Optical Accessories', 'familyId' => '58'], // Optics
                '10' => ['name' => 'Magazines', 'familyId' => '49'], // General
                '11' => ['name' => 'Grips, Pads, Stocks, Bipods', 'familyId' => '49'], // General
                '12' => ['name' => 'Soft Gun Cases, Packs, Bags', 'familyId' => '49'], // General
                '13' => ['name' => 'Misc. Accessories', 'familyId' => '49'], // General
                '14' => ['name' => 'Holsters & Pouches', 'familyId' => '49'], // General
                '15' => ['name' => 'Reloading Equipment', 'familyId' => '49'], // General
                '16' => ['name' => 'Black Powder Accessories', 'familyId' => '49'], // General
                '17' => ['name' => 'Closeout Accessories', 'familyId' => '49'], // General
                '18' => ['name' => 'Ammunition', 'familyId' => '47'], // Ammunition
                '19' => ['name' => 'Survival & Camping Supplies', 'familyId' => '49'], // General
                '20' => ['name' => 'Lights, Lasers & Batteries', 'familyId' => '49'], // General
                '21' => ['name' => 'Cleaning Equipment', 'familyId' => '49'], // General
                '22' => ['name' => 'Airguns', 'familyId' => '48'], // Firearm
                '23' => ['name' => 'Knives & Tools', 'familyId' => '56'], // Knives
                '24' => ['name' => 'High Capacity Magazines', 'familyId' => '49'], // General
                '25' => ['name' => 'Safes & Security', 'familyId' => '49'], // General
                '26' => ['name' => 'Safety & Protection', 'familyId' => '49'], // General
                '27' => ['name' => 'Non-Lethal Defense', 'familyId' => '49'], // General
                '28' => ['name' => 'Binoculars', 'familyId' => '58'], // General
                '29' => ['name' => 'Spotting Scopes', 'familyId' => '58'], // Optics
                '30' => ['name' => 'Sights', 'familyId' => '58'], // Optics
                '31' => ['name' => 'Optical Accessories', 'familyId' => '58'], // Optics
                '32' => ['name' => 'Barrels & Choke Tubes', 'familyId' => '57'], // Parts
                '33' => ['name' => 'Clothing', 'familyId' => '49'], // General
                '34' => ['name' => 'Parts', 'familyId' => '57'], // Parts
                '35' => ['name' => 'Slings & Swivels', 'familyId' => '49'], // General
                '36' => ['name' => 'Electronics', 'familyId' => '49'], // General
                '37' => ['name' => 'Not Used', 'familyId' => '49'], // General
                '38' => ['name' => 'Books, Software & DVD’s', 'familyId' => '49'], // General
                '39' => ['name' => 'Targets', 'familyId' => '49'], // General
                '40' => ['name' => 'Hard Gun Cases', 'familyId' => '49'], // General
                '41' => ['name' => 'Upper Receivers & Conversion Kits', 'familyId' => '57'], // Parts
                '42' => ['name' => 'SBR Barrels & Upper Receivers', 'familyId' => ''], // Exclude
                '43' => ['name' => 'Upper Receivers & Conversion Kits – High Capacity', 'familyId' => '57'], // Parts
            ],
            'departmentToCategory' => [
                "01" => ["category" => "70"],
                "1" => ["category" => "70"],
                "02" => ["category" => "70"],
                "2" => ["category" => "70"],
                "03" => ["category" => "71"],
                "3" => ["category" => "71"],
                "04" => ["category" => "92"],
                "4" => ["category" => "92"],
                "05" => ["category" => "71"],
                "5" => ["category" => "71"],
                "06" => ["category" => "64"],
                "6" => ["category" => "64"],
                "07" => ["category" => "64"],
                "7" => ["category" => "64"],
                "08" => ["category" => "89"],
                "8" => ["category" => "89"],
                "09" => ["category" => "89"],
                "9" => ["category" => "89"],
                "10" => ["category" => "88"],
                "11" => ["category" => "85"],
                "12" => ["category" => "69"],
                "13" => ["category" => "94"],
                "14" => ["category" => "87"],
                "15" => ["category" => "82"],
                "16" => ["category" => "82"],
                "17" => ["category" => "95"],
                "18" => ["category" => "74"],
                "19" => ["category" => "91"],
                "20" => ["category" => "96"],
                "21" => ["category" => "84"],
                "22" => ["category" => "73"],
                "23" => ["category" => "81"],
                "24" => ["category" => "88"],
                "25" => ["category" => "86"],
                "26" => ["category" => "90"],
                "27" => ["category" => "92"],
                "28" => ["category" => "89"],
                "29" => ["category" => "89"],
                "30" => ["category" => "89"],
                "31" => ["category" => "89"],
                "32" => ["category" => "97"],
                "33" => ["category" => "78"],
                "34" => ["category" => "80"],
                "35" => ["category" => "87"],
                "36" => ["category" => "96"],
                "38" => ["category" => "83"],
                "39" => ["category" => "93"],
                "40" => ["category" => "69"],
                "41" => ["category" => "98"],
                "42" => ["category" => "64"],
                "43" => ["category" => "98"]
            ],
            'departmentToProductType' => [
                "01" => ["productType" => "simple"],
                "02" => ["productType" => "simple"],
                "03" => ["productType" => "simple"],
                "04" => ["productType" => "simple"],
                "05" => ["productType" => "simple"],
                "06" => ["productType" => "simple"],
                "07" => ["productType" => "simple"],
                "08" => ["productType" => "simple"],
                "09" => ["productType" => "simple"],
                "10" => ["productType" => "simple"],
                "11" => ["productType" => "simple"],
                "12" => ["productType" => "simple"],
                "13" => ["productType" => "simple"],
                "14" => ["productType" => "configurable"],
                "15" => ["productType" => "simple"],
                "16" => ["productType" => "simple"],
                "17" => ["productType" => "simple"],
                "18" => ["productType" => "simple"],
                "19" => ["productType" => "simple"],
                "20" => ["productType" => "simple"],
                "21" => ["productType" => "simple"],
                "22" => ["productType" => "simple"],
                "23" => ["productType" => "simple"],
                "24" => ["productType" => "simple"],
                "25" => ["productType" => "simple"],
                "26" => ["productType" => "simple"],
                "27" => ["productType" => "simple"],
                "28" => ["productType" => "simple"],
                "29" => ["productType" => "simple"],
                "30" => ["productType" => "simple"],
                "31" => ["productType" => "simple"],
                "32" => ["productType" => "simple"],
                "33" => ["productType" => "simple"],
                "34" => ["productType" => "simple"],
                "35" => ["productType" => "simple"],
                "36" => ["productType" => "simple"],
                "38" => ["productType" => "simple"],
                "39" => ["productType" => "simple"],
                "40" => ["productType" => "simple"],
                "41" => ["productType" => "simple"],
                "42" => ["productType" => "simple"],
                "43" => ["productType" => "configurable"]
            ]

        ],
        'attributes_keys' => [
            "1" => "rsr_stock_id",
            "2" => "manufacturer_id",
            "3" => "accessories",
            "4" => "action",
            "5" => "type_of_barrel",
            "6" => "barrel_length",
            "7" => "catalog_code",
            "8" => "chamber",
            "9" => "chokes",
            "10" => "condition",
            "11" => "capacity",
            "12" => "description",
            "13" => "dram",
            "14" => "edge",
            "15" => "firing_casing",
            "16" => "finish",
            "17" => "fit",
            "18" => "fit1",
            "19" => "feet_per_second",
            "20" => "frame",
            "21" => "caliber",
            "22" => "caliber1",
            "23" => "grain_weight",
            "24" => "grips",
            "25" => "hand",
            "26" => "manufacturer",
            "27" => "manufacturer_part_number",
            "28" => "manufacturer_weight",
            "29" => "moa",
            "30" => "model",
            "31" => "model1",
            "32" => "new_stock_number",
            "33" => "nsn",
            "34" => "objective",
            "35" => "ounce_of_shot",
            "36" => "packaging",
            "37" => "power",
            "38" => "reticle",
            "39" => "safety",
            "40" => "sights",
            "41" => "size",
            "42" => "type",
            "43" => "units_per_box",
            "44" => "units_per_case",
            "45" => "wt_characteristics",
            "46" => '',
            "47" => "diameter",
            "48" => "color",
            "49" => "material",
            "50" => "stock",
            "51" => "lens_color",
            "52" => "handle_color"
        ]
    ],

    // Images
    'localImagesFolder' => 'temporary',
    'remoteImagesFolders' => [
        'ftp_highres_images/rsr_number/',
        'ftp_highres_images/rsr_number/#/',
        'ftp_images/rsr_number/',
        'ftp_images/rsr_number/#/'
    ],

];


