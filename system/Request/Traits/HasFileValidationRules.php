<?php

namespace System\Request\Traits;

trait HasFileValidationRules
{

    protected function fileValidation($name, $ruleArray) {

        foreach ($ruleArray as $rule)
        {
            if ($rule == 'required')
            {
                $this->fileRequired($name);
            }// mimes یعنی فرمت فایل
            elseif (strpos($rule, 'mimes:') === 0)
            {
                $rule = str_replace('mimes:', "", $rule);
                $role = explode(',', $rule);
                $this->fileType($name, $rule);
            }// max سایز فایل
            elseif (strpos($rule, 'max:') === 0)
            {
                $rule = str_replace('max:', "", $rule);
                $this->maxFile($name, $rule);
            } elseif (strpos($rule, 'min:') === 0)
            {
                $rule = str_replace('min:', "", $rule);
                $this->minFile($name, $rule);
            }
        }

    }


    protected function fileRequired($name) {
        if (!isset($this->files[$name]['name']) || empty($this->files[$name]['name']) && $this->checkFirstError($name))
        {
            $this->setError($name, "$name اجباری هست");
        }
    }

    protected function fileType($name, $typesArray) {

        if ($this->checkFirstError($name) && $this->checkFileExist($name))
        {
            $currenFileType = explode('/', $this->files[$name]['type'])[1];
            if (!in_array($currenFileType, $typesArray))
            {
                $this->setError($name, "فایل ارسال شده شما مجاز نیست. فرمت های مجاز : " . implode(' , ', $typesArray));
            }
        }

    }

    protected function maxFile($name, $size) {
        //تبدیل کیلوبایت به مگابایت با اعشار
        $size = $size * 1024;

        if ($this->checkFirstError($name) && $this->checkFileExist($name))
        {
            if ($this->files[$name]['size'] > $size)
            {
                $this->setError($name, "اندازه فایل شما بیشتر از حد مجاز :" . ($size / 1024) . " کیلوبایت است");
            }
        }

    }

        protected function minFile($name, $size) {
        //تبدیل کیلوبایت به مگابایت با اعشار
        $size = $size * 1024;

        if ($this->checkFirstError($name) && $this->checkFileExist($name))
        {
            if ($this->files[$name]['size'] < $size)
            {
                $this->setError($name, "اندازه فایل شما کمتر از حد مجاز :" . ($size / 1024) . " کیلوبایت است");
            }
        }

    }

}