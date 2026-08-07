<?php
namespace App\Http\Mail;
class EmailRenderer
{

    public static function render($html, array $data)
    {

        foreach ($data as $key => $value)
        {

            $html = str_replace(
                "{{{$key}}}",
                $value,
                $html
            );

        }

        return $html;

    }

}