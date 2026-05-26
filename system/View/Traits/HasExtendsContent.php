<?php

namespace System\View\Traits;

trait HasExtendsContent
{


    private $extendsContent;


    private function checkExtendsContent() {
        $layoutsFilePath = $this->findExtends();
        if ($layoutsFilePath)
        {
            $this->extendsContent = $this->viewLoader($layoutsFilePath);
            $yieldsNamesArray = $this->findYieldNames();
            if ($yieldsNamesArray)
            {
                foreach ($yieldsNamesArray as $yieldName)
                {
                    $this->initialYields($yieldName);
                }
            }
            $this->content = $this->extendsContent;
        }
    }

    // چک کردن مکان yield و section باز و بسته و جایگزاری section با yield
    private function initialYields($yieldName) {

        $string = $this->content;
        $startWord = "@section('" . $yieldName . "')";
        $endWord = "@endsection";

        $startPos = strpos($string, $startWord);
        if ($startPos === false)
        {
            return $this->extendsContent = str_replace("@yield('$yieldName')", "", $this->extendsContent);
        }
        $startPos += strlen($startWord);
        $endPos = strpos($string, $endWord, $startPos);
        if ($endPos === false)
        {
            return $this->extendsContent = str_replace("@yield('$yieldName')", "", $this->extendsContent);

        }
        $length = $endPos - $startPos;
        $sectionContent = substr($string, $startPos, $length);

            return $this->extendsContent = str_replace("@yield('$yieldName')", $sectionContent, $this->extendsContent);


    }


    //اگر در صفحه @extends('') استفاده شده بود یا نه. اگر شده بود یاید yield و ... ها رو پیدا و جایگزاری کند
    private function findExtends() {
        $filePathArray = [];
        //example @extends('app.index')
        preg_match("/s*@extends+\('([^)]+)'\)/", $this->content, $filePathArray);
        return isset($filePathArray[1]) ? $filePathArray[1] : false;
    }


    private function findYieldNames() {
        $yieldsNamesArray = [];
        //example @yield('app.index')
        preg_match_all("/@yield+\('([^)]+)'\)/", $this->extendsContent, $yieldsNamesArray, PREG_UNMATCHED_AS_NULL);
        return isset($yieldsNamesArray[1]) ? $yieldsNamesArray[1] : false;
    }


}