<?php

return [
    [
        'key'        => 'catalog.bulkupload',
        'name'       => 'bulkupload::app.admin.bulk-upload.bulk-upload',
        'route'      => 'admin.dataflow-profile.index',
        'sort'       => 6,
        'icon-class' => 'bulk-upload-icon',
    ], [
        'key'        => 'catalog.bulkupload.data-flow-profile',
        'name'       => 'bulkupload::app.admin.bulk-upload.bulk-upload-dataflow-profile',
        'route'      => 'admin.dataflow-profile.index',
        'sort'       => 2,
        'icon-class' => '',
    ], [
        'key'        => 'catalog.bulkupload.upload-files',
        'name'       => 'bulkupload::app.admin.bulk-upload.upload-files',
        'name'       => 'Upload Files',
        'route'      => 'admin.bulk-upload.index',
        'sort'       => 3,
        'icon-class' => '',
    ], [
        'key'        => 'catalog.bulkupload.run-profile',
        'name'       => 'bulkupload::app.admin.bulk-upload.run-profile',
        'route'      => 'admin.run-profile.index',
        'sort'       => 4,
        'icon-class' => '',
    ]
];