<?php

namespace Devvly\Ffl\Services\FflEzCheck;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom;
use PHPHtmlParser\Exceptions\ChildNotFoundException;
use PHPHtmlParser\Exceptions\CircularException;
use PHPHtmlParser\Exceptions\ContentLengthException;
use PHPHtmlParser\Exceptions\LogicalException;
use PHPHtmlParser\Exceptions\NotLoadedException;
use PHPHtmlParser\Exceptions\StrictException;
use Psr\Http\Message\ResponseInterface;

class FflEzCheck
{
    const URL = 'https://fflezcheck.atf.gov/fflezcheck/fflSearch.do';

    const INVALID_MARKER = 'If you are certain you entered the correct license number, there is a strong possibility that the FFL you are attempting to verify is invalid and/or this may be an attempt at a fraudulent transaction.';

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var Dom
     */
    private $dom;

    public function __construct(ClientInterface $client, Dom $document)
    {
        $this->client = $client;
        $this->dom = $document;
    }

    /**
     * @param string $licenseRegion
     * @param string $licenseDistrict
     * @param string $licenseSequence
     * @return bool
     * @throws ChildNotFoundException
     * @throws CircularException
     * @throws ContentLengthException
     * @throws GuzzleException
     * @throws LogicalException
     * @throws NotLoadedException
     * @throws StrictException
     */
    public function isLicenseValid(string $licenseRegion, string $licenseDistrict, string $licenseSequence): bool
    {
        $this->setPageContent(
            $this->getPageResponse(
                $licenseRegion, $licenseDistrict, $licenseSequence
            )->getBody()->getContents()
        );
        return !$this->isInvalidMarkerExist();
    }

    /**
     * @param string $licenseRegion
     * @param string $licenseDistrict
     * @param string $licenseSequence
     * @return ResponseInterface
     * @throws GuzzleException
     */
    private function getPageResponse(string $licenseRegion, string $licenseDistrict, string $licenseSequence): ResponseInterface
    {
        return $this->client->request('POST', self::URL, [
            'form_params' => [
                'licsRegn' => $licenseRegion,
                'licsDis'  => $licenseDistrict,
                'licsSeq'  => $licenseSequence,
                'Search'   => 'Submit',
            ],
        ]);
    }

    /**
     * @param string $pageContent
     * @throws ChildNotFoundException
     * @throws CircularException
     * @throws ContentLengthException
     * @throws LogicalException
     * @throws StrictException
     */
    private function setPageContent(string $pageContent)
    {
        $this->dom->loadStr($pageContent);
    }

    /**
     * @return bool
     * @throws ChildNotFoundException
     * @throws NotLoadedException
     */
    private function isInvalidMarkerExist(): bool
    {
        foreach ($this->dom->find('.maintbl') as $table) {
            foreach ($table->find('p') as $paragraph) {
                if (str_contains($paragraph->innerHtml, self::INVALID_MARKER)) {
                    return true;
                }
            }
        }
        return false;
    }
}