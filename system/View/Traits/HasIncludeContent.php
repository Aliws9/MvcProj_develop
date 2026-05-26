<?php

namespace System\View\Traits;

trait HasIncludeContent
{

    private function checkIncludeContent() {
        while (1)
        {
            $includesNamesArray = $this->findIncludeNames();
            if (!empty($includesNamesArray) && isset($includesNamesArray))
            {
                foreach ($includesNamesArray as $includeName)
                {
                    $this->initialIncludes($includeName);
                }
            } else
            {
                break;
            }
        }
    }

    private function findIncludeNames() {
        $includesNamesArray = [];
        preg_match_all("/@include+\('([^)]+)'\)/", html_entity_decode($this->content), $includesNamesArray, PREG_UNMATCHED_AS_NULL);
        return isset($includesNamesArray[1]) ? $includesNamesArray[1] : false;
    }


    // چک کردن مکان yield و section باز و بسته و جایگزاری section با yield
    private function initialIncludes($includeName) {
        $this->content = str_replace("@include('".$includeName."')", html_entity_decode($this->viewLoader($includeName)), $this->content);
    }

}