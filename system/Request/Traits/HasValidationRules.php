<?php
namespace System\Request\Traits;

use System\Database\DBConnection\DBConnection;

trait HasValidationRules
{

    // request->validation ([
//     name     ruleArray
//     'title' => "rquired|unique:posts|max:255",
//     'body' => "required"
// ])

    // ruleArray [
//     'required',
//     'max:255',
//     'exists:users,id'
// ]
    public function normalValidation($name, $ruleArray) {

        foreach ($ruleArray as $rule)
        {
            if ($rule == 'required')
            {
                $this->required($name);
            } elseif (strpos($rule, 'max:') === 0)
            {
                $rule = str_replace('max:', "", $rule);
                $this->maxStr($name, $rule);
            } elseif (strpos($rule, 'min:') === 0)
            {
                $rule = str_replace('min:', "", $rule);
                $this->minStr($name, $rule);
            } elseif (strpos($rule, 'exists:') === 0)
            {
                $rule = str_replace('exists:', "", $rule);
                $rule = explode(',', $rule);
                $key = isset($rule[1]) == false ? null : $rule[1];
                $this->existsIn($name, $rule[0], $key);
            } elseif ($rule == 'email')
            {
                $this->email($name);
            } elseif ($rule == 'date')
            {
                $this->date($name);
            }

        }

    }

    public function numberValidation($name, $ruleArray) {

        foreach ($ruleArray as $rule)
        {
            if ($rule == 'required')
            {
                $this->required($name);
            } elseif (strpos($rule, 'max:') === 0)
            {
                $rule = str_replace('max:', "", $rule);
                $this->maxNumber($name, $rule);
            } elseif (strpos($rule, 'min:') === 0)
            {
                $rule = str_replace('min:', "", $rule);
                $this->minNumber($name, $rule);
            } elseif (strpos($rule, 'exists:') === 0)
            {
                $rule = str_replace('exists:', "", $rule);
                $rule = explode(',', $rule);
                $key = isset($rule[1]) == false ? null : $rule[1];
                $this->existsIn($name, $rule[0], $key);
            } elseif ($rule == 'number')
            {
                $this->number($name);
            }

        }
    }

    //------------------------------------------------
    protected function maxStr($name, $count) {
        if ($this->checkFieldExist($name))
        {
            if (strlen($this->request[$name]) > $count && $this->checkFirstError($name))
            {
                $this->setError($name, "متن این فیلد بیشتر از ($count) است");
            }
        }
    }

    protected function minStr($name, $count) {
        if ($this->checkFieldExist($name))
        {
            if (strlen($this->request[$name]) < $count && $this->checkFirstError($name))
            {
                $this->setError($name, "متن این فیلد کمتر از ($count) است");
            }
        }
    }

    protected function maxNumber($name, $count) {
        if ($this->checkFieldExist($name))
        {
            if ($this->request[$name] > $count && $this->checkFirstError($name))
            {
                $this->setError($name, " عدد این فیلد بیشتر از ($count) است");
            }
        }
    }

    protected function minNumber($name, $count) {
        if ($this->checkFieldExist($name))
        {
            if ($this->request[$name] < $count && $this->checkFirstError($name))
            {
                $this->setError($name, "عدد این فیلد کمتر از ($count) است");
            }
        }
    }

    protected function required($name) {
        if (!isset($this->request[$name]) || $this->request[$name] === '' && $this->checkFirstError($name))
        {
            $this->setError($name, "$name اجباری هست");
        }
    }

    public function number($name) {
        if ($this->checkFieldExist($name))
        {
            if (!is_numeric($this->request[$name]) && $this->checkFirstError($name))
            {
                $this->setError($name, "$name فقط باید عدد باشد");
            }
        }
    }

    public function date($name) {
        if ($this->checkFieldExist($name))
        {
            if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $this->request[$name]) && $this->checkFirstError($name))
            {
                $this->setError($name, "فرمت وارد شده برای $name اشتباه است");
            }
        }
    }

    protected function email($name) {
        if ($this->checkFieldExist($name))
        {
            if (!filter_var($this->request[$name], FILTER_VALIDATE_EMAIL) && $this->checkFirstError($name))
            {
                $this->setError($name, "ایمیل وارد شده در فیلد $name معتبر نیست");
            }
        }
    }

    public function existsIn($name, $table, $field = "id") {

        if ($this->checkFieldExist($name))
        {
            if ($this->checkFirstError($name))
            {

                $value = $this->$name;
                $sql = "SELECT COUNT(*) FROM $table WHERE $field = ?";
                $stmt = DBConnection::getDBConnectionInst()->prepare($sql);
                $stmt->execute([$value]);
                $res = $stmt->fetchColumn();
                if ($res == 0 || $res === false)
                {
                    $this->setError($name, "مقدار $value در دیتابیس وجود نداشت");
                }

            }
        }
    }
}