<?php

namespace App\Service;

class IdExtractorService
{
    public function getId(string $url) : ?string
    {
        $regExp = "/^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/";
        preg_match($regExp, $url, $matches);

        if ($matches && strlen($matches[2]) == 11) {
            return $matches[2];
        }
        return null;

    }
}