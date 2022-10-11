<?php

namespace App\Service;


use function PHPUnit\Framework\throwException;

class FilterYoutubeUrlService
{
    public function __construct(protected IdExtractorService $idExtractorService)
    {
    }

    public function filterVideoLink(string $videoLink): string
    {
        if (strlen($videoLink) === 11) {
            $regExp = "/(\\w|-|_)+/";
            preg_match($regExp, $videoLink, $matches);
            if (!$matches) {
                throw new \RuntimeException("the youtube ID is not valid");
            }
            return $videoLink;
        }
        $videoLinkID = $this->idExtractorService->getId($videoLink);
        if (null === $videoLinkID) {
            throw new \RuntimeException("the youtube url is not valid");
        }
        return $videoLinkID;

    }
}