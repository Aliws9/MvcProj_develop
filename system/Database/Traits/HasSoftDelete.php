<?php
namespace System\Database\Traits;

trait HasSoftDelete
{

     protected function deleteMethod($id = null) {

          $object = $this;

          if ($id)
          {
               $this->resetQuery();
               $object = $this->findMethod($id);

          }

          if ($object)
          {
               $this->resetQuery();

               $object->setSql("UPDATE  " . $object->getTableName() . " SET " . $this->getAttributeName($this->deletedAt) . " = Now()");

               $object->setWhere("AND", $this->getAttributeName($object->primaryKey) . " = ? ");
               $object->addValues($object->primaryKey, $object->{$object->primaryKey});
               return $object->executeQuery();
          }

     }



     protected function allMethod() {

          $this->setSql("SELECT " . $this->getTableName() . ".* FROM " . $this->getTableName());

          // $this->setWhere("AND", $this->getAttributeName($this->{$this->deletedAt}) . " = ? ");

          $this->setWhere("AND", $this->getAttributeName($this->deletedAt) . " IS NULL ");

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
          $this->resetQuery();

          $this->setSql("SELECT " . $this->getTableName() . ".* FROM " . $this->getTableName());

          $this->setWhere("AND", $this->getAttributeName($this->primaryKey) . " = ? ");
          $this->addValues($this->primaryKey, $id);

          $this->setWhere("AND", $this->getAttributeName($this->deletedAt) . " IS NULL ");

          $stmt = $this->executeQuery();

          $data = $stmt->fetch();

          $this->setAllowMethod(['update', 'delete', 'save']);

          if ($data)
               return $this->arrayToAttributes($data);

          return null;

     }



     // array = [id , name , pass]
     protected function getMethod($array = []) {

          $this->resetQuery();

          if ($this->getSql() == '')
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

               $this->setSql("SELECT " . $fields . " FROM " . $this->getTableName());

          }

          $this->setWhere('AND', $this->getAttributeName($this->deletedAt) . " IS NULL ");

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



     protected function paginateMethod($perPage) {

          $this->resetQuery();

          $this->setWhere('AND', $this->getAttributeName($this->deletedAt) . " IS NULL ");

          //تعداد تمامی ردیف ها
          $totalRow = $this->getCount();
          dd($totalRow);
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

          if ($this->getSql() == '')
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




}