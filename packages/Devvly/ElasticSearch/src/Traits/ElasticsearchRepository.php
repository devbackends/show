<?php

namespace Devvly\ElasticSearch\Traits;

use Webkul\Marketplace\Models\Seller;

trait ElasticsearchRepository
{

    /**
     * @param  string  $model
     * @param  string  $term
     * @param  array  $fields
     * @param  array  $sortOptions
     * @return mixed
     */
    public function getElasticsearchItems(string $mode, array $terms, array $fields, array $sortOptions = [])
    {
        $model = app($mode);
        $size = 1000;
        $conditions = [];

        foreach ($terms as $key => $term) {

            if ($key == 'search') {
                $array = [
                    "bool" => [
                        "must" => [
                            'multi_match' => [
                                'query' => $term, 'fields' => $fields
                            ]
                        ]
                    ]
                ];

                if (substr_count($term, " ") > 2) {
                    $array["bool"]["must"]["multi_match"]["type"] = "phrase_prefix";
                }
                array_push($conditions, $array);
            } elseif ($key == 'compatibleWith') {
                array_push($conditions, [
                    "bool" => [
                        "must" => [
                            'multi_match' => [
                                'query' => $term, 'fields' => $fields
                            ]
                        ]
                    ]
                ]);
            } else {

                if ($key != 'sort' && $key != 'order' && $key != 'limit' && $key != 'page' && $key != 'mode' && $key != 'limitForMenu') {
                    $y = [];
                    $termValues = explode(',', $term);
                    foreach ($termValues as $termValue) {
                        array_push($y, [
                                "bool" => [

                                    "must" => [
                                        [
                                            "match" => [
                                                $key => $termValue
                                            ]
                                        ]
                                    ]
                                ]
                            ]);
                    }
                    array_push($conditions, [
                        "bool" => [
                            "should" => $y

                        ]
                    ]);


                }

            }

        }
        if ($mode == "Webkul\Product\Models\ProductFlat") {
            array_push($conditions, [
                "bool" => [
                    "must" => [
                        'match' => [
                            'isInStockIndex' => 1
                        ]
                    ]
                ]
            ]);
        }
        $query = [
            'bool' => [
                'must' => $conditions
            ]

        ];


        $options = [
            'index' => $model->getSearchIndex(), 'type' => $model->getSearchType(), 'body' => [
                'size' => $size, 'query' => $query,
            ]
        ];


        if (!empty($sortOptions['sortField']) && !empty($sortOptions['sortOrder'])) {
            if ($model instanceof Seller && $sortOptions['sortField'] === 'name') {
                $options['body']['sort'] = [
                    ['shop_title.keyword' => ['order' => $sortOptions['sortOrder']]]
                ];
            } else {

                $options['body']['sort'] = [
                    [$sortOptions['sortField'] => ['order' => $sortOptions['sortOrder'], "unmapped_type" => "float"]]
                ];

            }
        } else {
            $options['body']['sort'] = [
                ['created_at.keyword' => ['order' => 'desc']]
            ];
        }

        return $this->elasticsearch->search($options);
    }

    public function getIndexedData(){


        $model = app('Webkul\Product\Models\ProductFlat');
        $size = 1000;
        $query = [
            "match_all" => ["boost" => 1.0]
        ];
        $options = [
            'index' => $model->getSearchIndex(), 'type' => $model->getSearchType(), 'body' => [
                'size' => $size, 'query' => $query,
            ]
        ];
        return $this->elasticsearch->search($options);
    }

}