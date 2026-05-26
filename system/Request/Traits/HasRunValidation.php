<?php
namespace System\Request\Traits;

trait HasRunValidation{


// اگر در هر قسمت از اعتبار سنجی اروری داشت ارسال فرم رو متوقف میکنه و بازگشت به صفحه اگر نداشت ادامه و ارسال فرم
    protected function errorRedirect(){
        if($this->errorExist == false){
            return $this->request;
        }else{
            return back();
        }
    }

// اگر ارور از قبل ست نشده باشد و فیلد ارسال شده جزو آرایه ای که ارور ها رو نگه میداره, نبود true درغیر این صورت flase
    private function checkFirstError($name){

        if(!errorExist($name) && !in_array($name , $this->errorVariableName)){
            return true;
        }
            return false;

    }


//آیا فیلد ارسال شده برای اعتبار سنجی آیا با $_POST ارسال شده یا خیر
    private function checkFieldExist($name){
        return (isset($this->request[$name]) && !empty($this->request[$name])) ? true : false;
    }

//آیا فیلد ارسال شده برای اعتبار سنجی آیا با $_FILE ارسال شده یا خیر
    private function checkFileExist($name){
        if(isset($this->files[$name]['name'])){
            if(!empty($this->files[$name]['name'])){
                return true;
            }
        }
        return false;
    }

// ست ارور یا پیام یک بار مصرف با سشن
    private function setError($name , $errorMessage){

        array_push($this->errorVariableName , $name);
        error($name , $errorMessage);
        $this->errorExist = true;

    }

}