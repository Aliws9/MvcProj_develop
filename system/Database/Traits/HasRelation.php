<?php

namespace System\Database\Traits;

trait HasRelation
{



     protected function hasOne($model, $foreignKey, $localKey) {

          if ($this->{$this->primaryKey})
          {
               $modelObject = new $model();
               return $modelObject->getHasOneRelation($this->table, $foreignKey, $localKey, $this->$localKey);
          }

     }


     public function getHasOneRelation($table, $foreignKey, $otherKey, $otherKeyValue) {

          // a = users
          // b = phone

          $this->setSql("SELECT `b`.* FROM {$table} AS `a` JOIN " . $this->getTableName() . " AS `b` on 
          `a`.`{$otherKey}` = `b`.`{$foreignKey}` ");

          $this->table = 'b';

          $this->setWhere('AND', "`a`.`$otherKey` = ? ");

          $this->addValues($otherKey, $otherKeyValue);

          $stmt = $this->executeQuery();

          $data = $stmt->fetch();
          // $this->setAllowdMethod('update' , 'delete' , 'save');

          if ($data)
          {
               return $this->arrayToAttributes($data);
               
          } else
          {
               return null;
          }

     }







     
     protected function hasMany($model, $foreignKey, $otherKey) {

          if ($this->{$this->primaryKey})
          {
               $modelObject = new $model();
               return $modelObject->getHasManyRelation($this->table, $foreignKey, $otherKey, $this->$otherKey);
          }

     }


     public function getHasManyRelation($table, $foreignKey, $otherKey, $otherKeyValue) {

          // a = users
          // b = phone

          $this->setSql("SELECT `b`.* FROM {$table} AS `a` JOIN " . $this->getTableName() . " AS `b` on 
          `a`.`{$otherKey}` = `b`.`{$foreignKey}` ");

          $this->table = 'b';

          $this->setWhere('AND', "`a`.`$otherKey` = ? ");

          $this->addValues($otherKey, $otherKeyValue);

          return $this;

     }





          protected function blongsTo($model, $foreignKey, $localKey) {

          if ($this->{$this->primaryKey})
          {
               $modelObject = new $model();
               return $modelObject->getBelongsToRelation($this->table, $foreignKey, $localKey, $this->$foreignKey);
          }

     }


     public function getBelongsToRelation($table, $foreignKey, $otherKey, $foreignKeyValue) {

          // a = users
          // b = phone

          $this->setSql("SELECT `b`.* FROM {$table} AS `a` JOIN " . $this->getTableName() . " AS `b` on 
          `a`.`{$foreignKey}` = `b`.`{$otherKey}` ");

          $this->table = 'b';

          $this->setWhere('AND', "`a`.`$foreignKey` = ? ");

          $this->addValues($foreignKey, $foreignKeyValue);

          $stmt = $this->executeQuery();

          $data = $stmt->fetch();
          // $this->setAllowdMethod('update' , 'delete' , 'save');

          if ($data)
          {
               return $this->arrayToAttributes($data);
               
          } else
          {
               return null;
          }

     }




     // many to many
               protected function blongsToMany($model, $commonTable , $localKey , $middleForeignKey , $middleRelation , $foreignKey) {

          if ($this->{$this->primaryKey})
          {
               $modelObject = new $model();
               return $modelObject->getBelongsToManyRelation($this->table, $commonTable , $localKey , $this->$localKey , $middleForeignKey ,
                $middleRelation, $foreignKey);
          }

     }


     public function getBelongsToManyRelation($table, $commonTable , $localKey , $localKeyValue , $middleForeignKey , $middleRelation , $foreignKey) {

     
          $this->setSql("SELECT `c`.* FROM ( SELECT `b`.* FROM `{$table}` AS `a` JOIN `{$commonTable}` AS `b` on `a`.`{$localKey}` = `b`.`{$middleForeignKey}` WHERE 
          `a`.`{$localKey}` = ?  ) AS `relation` JOIN " . $this->getTableName() . " AS `c` ON `relation`.`{$middleRelation}` = `c`.`{$foreignKey}`");

          $this->addValues("{$table}_{$localKey}", $localKeyValue);

          $this->table = `c`;

          return $this;

     }




}