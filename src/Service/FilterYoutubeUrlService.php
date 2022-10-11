<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RedirectResponse;

class FilterYoutubeUrlService
{
    private ?string $id;
    private ?string $flashType;
    private ?string $message;

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }


    /**
     * @return string|null
     */
    public function getFlashType(): ?string
    {
        return $this->flashType;
    }

    /**
     * @param string|null $flashType
     */


    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }


    public function filterVideoLink(string $videoLink): FilterYoutubeUrlService
    {
        $idExtractorService = new IdExtractorService;
        if (strlen($videoLink) !== 11) {
            $videoLinkID = $idExtractorService->getId($videoLink);
            if (null === $videoLinkID) {
                $this->id = null;
                $this->flashType = "notice";
                $this->message = "the youtube url is not valid";
                return $this;
            }
            $this->id = $videoLinkID;
        } else {
            $regExp = "/(\\w|-|_)+/";
            preg_match($regExp, $videoLink, $matches);
            if (!$matches) {
                $this->id = null;
                $this->flashType = "notice";
                $this->message = "the youtube ID is not valid";
                return $this;
            }
            $this->id = $videoLink;
        }

        $this->flashType = "success";
        $this->message = "the youtube Link is converted";
        return $this;

    }
}