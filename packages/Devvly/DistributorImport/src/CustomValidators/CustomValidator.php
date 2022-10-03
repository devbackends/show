<?php

namespace Devvly\DistributorImport\CustomValidators;

interface CustomValidator {

    public function __construct(array $data);

    public function execute();

}
