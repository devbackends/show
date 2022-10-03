<?php

namespace Devvly\Ffl\DTO;

class FflDataSetDto
{
    const BASE_PATH = 'tmp/';

    private $fileName;

    /**
     * @return mixed
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @param mixed $fileName
     */
    public function setFileName($fileName): void
    {
        $this->fileName = $fileName;
    }
}
