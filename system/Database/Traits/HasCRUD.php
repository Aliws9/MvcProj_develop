<?php

namespace System\Database\Traits;

use System\Database\DBConnection\DBConnection;

// use function PHPSTORM_META\type;

// تمام متد های مورد استفاده در دیتابیس مثل SELCT , INSERT , UPDATE , DELETE , ALL , FIND , GET ,...  رو در این تریت مینویسیم و از تریت های HasAttribute و HasQueryBuilder هم استفاده میکنیم

trait HasCRUD
{


    protected function createMethod($values) {

        $values = $this->arrayToCastEncodeValue($values);
        $this->arrayToAttributes($values, $this);
        $this->saveMethod();

    }

    protected function updateWhereMethod($conditions, $values) {
    // conditions = ['slug' => 'php-news']
    // values = ['name' => 'new name', 'description' => 'new desc']
    
    $values = $this->arrayToCastEncodeValue($values);
    
    $fillArray = [];
    foreach ($values as $attribute => $value) {
        if (in_array($attribute, $this->fillable)) {
            array_push($fillArray, $this->getAttributeName($attribute) . " = ?");
            $this->addValues($attribute, $value);
        }
    }
    
    $fillString = implode(', ', $fillArray);
    
    $this->setSql("UPDATE " . $this->getTableName() . " SET $fillString, " .
        $this->getAttributeName($this->updatedAt) . "=Now()");
    
    foreach ($conditions as $field => $val) {
        $this->setWhere("AND", $this->getAttributeName($field) . " = ?");
        $this->addValues($field, $val);
    }
    
    $result = $this->executeQuery();
    $this->resetQuery();
    return $result;
}

    protected function updateMethod($values) {

        $values = $this->arrayToCastEncodeValue($values);

        $this->arrayToAttributes($values, $this);
        $this->saveMethod();

    }


    protected function deleteMethod($id = null) {

        $object = $this;
        $this->resetQuery();

        if ($id)
        {
            $object = $this->findMethod($id);
            $this->resetQuery();
        }

        $object->setSql("DELETE FROM  " . $object->getTableName());
        $object->setWhere("AND", $this->getAttributeName($this->primaryKey) . " = ? ");
        $object->addValues($object->primaryKey, $object->{$object->primaryKey});
        return $object->executeQuery();

    }


    protected function allMethod() {


        $this->setSql("SELECT * FROM " . $this->getTableName());
        $stmt = $this->executeQuery();
        $data = $stmt->fetchAll();
        if ($data)
        {


            $this->arrayToObjects($data);
            return $this->collection;


        } else
        {
            return [];
        }

    }


    protected function findMethod($id) {


        $this->setSql("SELECT * FROM " . $this->getTableName());

        $this->setWhere("AND", $this->getAttributeName($this->primaryKey) . " = ? ");
        $this->addValues($this->primaryKey, $id);

        $stmt = $this->executeQuery();

        $data = $stmt->fetch();

        $this->setAllowMethod(['update', 'delete', 'save' , 'count']);

        if ($data)
            return $this->arrayToAttributes($data);

        return null;

    }


    protected function whereMethod($attribute, $firstValue, $secondValue = null) {

        if ($secondValue === null)
        {
            $condition = $this->getAttributeName($attribute) . " = ?";
            $this->addValues($attribute, $firstValue);
        } else
        {
            $condition = $this->getAttributeName($attribute) . ' ' . $firstValue . ' ?';
            $this->addValues($attribute, $secondValue);
        }

        $operator = 'AND';

        $this->setWhere($operator, $condition);
        $this->setAllowMethod(['where', 'whereOr', 'whereIn', 'whereNull', 'whereNotNull', 'limit', 'orderBy', 'get', 'paginate']);

        return $this;

    }



    protected function whereOrMethod($attribute, $firstValue, $secondValue = null) {

        if ($secondValue === null)
        {
            $condition = $this->getAttributeName($attribute) . " = ?";
            $this->addValues($attribute, $firstValue);
        } else
        {
            $condition = $this->getAttributeName($attribute) . ' ' . $firstValue . ' ?';
            $this->addValues($attribute, $secondValue);
        }

        $operator = 'OR';

        $this->setWhere($operator, $condition);
        $this->setAllowMethod(['where', 'whereOr', 'whereIn', 'whereNull', 'whereNotNull', 'limit', 'orderBy', 'get', 'paginate']);
        return $this;

    }

    protected function whereNullMethod($attribute) {

        $condition = $this->getAttributeName($attribute) . ' IS NULL ';

        $operator = 'AND';

        $this->setWhere($operator, $condition);
        $this->setAllowMethod(['where', 'whereOr', 'whereIn', 'whereNull', 'whereNotNull', 'limit', 'orderBy', 'get', 'paginate' , 'count']);

        return $this;

    }

    protected function whereInMethod($attribute, $values) {

        if (is_array($values))
        {

            $values_array = [];

            foreach ($values as $value)
            {
                $this->addValues($attribute, $value);
                array_push($values_array, '?');
            }

            $condition = $this->getAttributeName($attribute) . ' IN (' . implode(' , ', $values_array) . ')';

            $operator = 'AND';

            $this->setWhere($operator, $condition);
            $this->setAllowMethod(['where', 'whereOr', 'whereIn', 'whereNull', 'whereNotNull', 'limit', 'orderBy', 'get', 'paginate']);
            return $this;


        }

    }


    protected function orderByMethod($attribute, $expression) {

        $this->setOrderBy($attribute, $expression);
        $this->setAllowMethod(['limit', 'orderBy', 'get', 'paginate']);
        return $this;

    }

    protected function limitMethod($from, $num) {

        $this->setLimit($from, $num);
        $this->setAllowMethod(['limit', 'get', 'paginate']);
        return $this;

    }

    protected function countMethod() {
        $t = $this->getCount();
        $this->setAllowMethod(['limit', 'orderBy', 'get', 'paginate', 'whereNull']);

        return $t;
    }



    protected function getMethod($array = []) {


        if ($this->sql == '')
        {

            if (empty($array))
            {
                $fields = $this->getTableName() . '.*';

            } else
            {


                foreach ($array as $key => $field)
                {

                    $array[$key] = $this->getAttributeName($field);

                }

                $fields = implode(' , ', $array);

            }

            $query = "SELECT " . $fields . " FROM " . $this->getTableName();

            $this->setSql($query);

        }

        $stmt = $this->executeQuery();
        $data = $stmt->fetchAll();

        // $this->setAllowMethod(['limit', 'get', 'paginate']);

        if ($data)
        {

            $this->arrayToObjects($data);
            return $this->collection;

        } else
        {

            return [];

        }


    }


    // paginate func صفحه بندی
    protected function paginateMethod($perPage) {

        //تعداد تمامی ردیف ها
        $totalRow = $this->getCount();

        //آدرس پیج فعلی که از لینک فعلی گرفته شده
        $currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;

        //تعداد صفحه هایی که لازم هست برای صفحه بندی بر اساس تعداد مقالات در هر صفحه
        $totalPages = ceil($totalRow / $perPage);

        //اگر صفحه وارد شده کمتر یا بیشتر از کل پیج ها و صفحات ما بود
        $currentPage = min($currentPage, $totalPages);
        $currentPage = max($currentPage, 1);

        //تعداد و ترتیب رکورد های صفحه فعلی
        $currentRow = ($currentPage - 1) * $perPage;

        $this->setLimit($currentRow, $perPage);

        if ($this->sql == '')
        {
            $this->setSql("SELECT " . $this->getTableName() . ".* FROM " . $this->getTableName());
        }
        
        $stmt = $this->executeQuery();
        $data = $stmt->fetchAll();

        // $this->setAllowMethod(['limit', 'get', 'paginate']);

        if ($data)
        {

            $this->arrayToObjects($data);
            return $this->collection;

        } else
        {

            return [];

        }

    }




    protected function whereNotNullMethod($attribute) {

        $condition = $this->getAttributeName($attribute) . ' IS NOT NULL ';

        $operator = 'AND';

        $this->setWhere($operator, $condition);
        $this->setAllowMethod(['where', 'whereOr', 'whereIn', 'whereNull', 'whereNotNull', 'limit', 'orderBy', 'get', 'paginate']);
        return $this;

    }




    //  نهایی کردن عملیات اس کیو ال برای insert , update
    protected function saveMethod() {

        $fillString = $this->fill();
        if (!isset($this->{$this->primaryKey}))
        {
            //insert
            $this->setSql("INSERT INTO " . $this->getTableName() . " SET $fillString, " .
                $this->getAttributeName($this->createdAt) . "=Now()");

        } else
        {

            //update
            $this->setSql("UPDATE " . $this->getTableName() . " SET $fillString, " .
                $this->getAttributeName($this->createdAt) . "=Now()");

            $this->setWhere("AND", $this->getAttributeName($this->primaryKey) . " = ?");
            $this->addValues($this->primaryKey, $this->{$this->primaryKey});

        }


        $this->executeQuery();
        $this->resetQuery();

        if (!isset($this->{$this->primaryKey}))
        {
            $object = $this->findMethod(DBConnection::newInsertId());

            $defaultVars = get_class_vars(get_called_class());
            $allVars = get_object_vars($object);

            $diffrentVars = array_diff(array_keys($allVars), array_keys($defaultVars));

            foreach ($diffrentVars as $attribute)
            {

                $this->inCatsAttributes($attribute) == true ? $this->registerAttribute($this, $attribute, $this->castEncodeValue($attribute, $object->$attribute)) :
                    $this->registerAttribute($this, $attribute, $object->$attribute);

            }

        }

        $this->resetQuery();
        $this->setAllowMethod(['update', 'delete', 'save']);
        return $this;


    }



    // پر کردن متغیر fillable که در کلاس مدل تعریف کردیم
    // fillable متغیری هست که مقدار های قابل پر شدن در دیتابیس رو نگهداری میکرد مثل name
    protected function fill() {

        $fillArray = [];

        foreach ($this->fillable as $attribute)
        {

            if (isset($this->$attribute))
            {
                if($this->$attribute ===''){
                    $this->$attribute = null;
                }

                array_push($fillArray, $this->getAttributeName($attribute) . " = ?");

                if ($this->inCatsAttributes($attribute) == true)
                {
                    $this->addValues($attribute, $this->castEncodeValue($attribute, $this->$attribute));
                } else
                {
                    $this->addValues($attribute, $this->$attribute);
                }

            }

        }

        $fillSrting = implode(', ', $fillArray);
        return $fillSrting;

    }




}






