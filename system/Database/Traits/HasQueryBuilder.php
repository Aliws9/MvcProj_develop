<?php

namespace System\Database\Traits;

use \System\Database\DBConnection\DBConnection;

// ساخت کویری و درخواست ما به دیتابیس
trait HasQueryBuilder
{
     protected $sql = '';
     protected $where = [];
     private $orderBy = [];
     private $limit = [];

     private $values = [];
     private $bindValues = [];



     //---------------------------------sql-----------------------------
     protected function setSql($query) {
          $this->sql = $query;
          
     }

     protected function getSql() {
          return $this->sql;
     }

     protected function resetSql() {
          $this->sql = '';
     }

     // --------------------------------where--------------------------

     protected function setWhere($operator , $condition) {

          //operator : AND \ OR
          //condition : =  >=  <= > < !=
          $array = ['operator' => $operator, 'condition' => $condition];
          array_push($this->where, $array);

     }


     protected function resetWhere() {
          $this->where = [];
     }

     // ---------------------------------orderBy-------------------------

     protected function setOrderBy($name, $exper) {

          array_push($this->orderBy, $this->getAttributeName($name) . " " . $exper);

     }

     protected function resetOrderBy() {
          $this->orderBy = [];
     }

     // ---------------------------------limit-------------------------

     protected function setLimit($from = null, $number) {
          $this->limit['from'] = (int) $from;
          $this->limit['number'] = (int) $number;

     }

     protected function resetLimit() {
          unset($this->limit['from']);
          unset($this->limit['number']);
     }

     // ---------------------------------values-------------------------

     // protected function addValues($attribute, $val) {

     //     $this->values[$attribute] = $val;
     //     array_push($this->bindValues, $val);
     // }

     protected function addValues($attribute, $val) {

          // attribute = category
          // val = 2
          //$this->values = ['category' => 2 , `article` => 3]
          //bindValues = [[0] => 2 , [1] => 3]

          $this->values[$attribute] = $val;
          array_push($this->bindValues, $val);
     }

     protected function removeValues() {

          $this->values = [];
          $this->bindValues = [];
     }

     //---------------------------------------resetAll-------------------------

     protected function resetQuery() {

          $this->resetSql();
          $this->resetOrderBy();
          $this->resetLimit();
          $this->resetWhere();
          $this->removeValues();
     }

     //----------------------------------executeQuery------------------------

     protected function executeQuery() {

          $query = '';
          $query .= $this->sql;

          //---------WHRE----------
          if (!empty($this->where))
          {
               $whereString = '';

               foreach ($this->where as $where)
               {

                    if ($whereString == '')
                    {

                         $whereString .= $where['condition'];

                    } else
                    {

                         $whereString .= ' ' . $where['operator'] . ' ' . $where['condition'];

                    }
               }

               $query .= ' WHERE ' . $whereString;

          }

          //------------ORDERBY-----------
          if (!empty($this->orderBy))
          {
               $query .= ' ORDER BY ' . implode(', ', $this->orderBy);
          }

          //-----------LIMIT--------------
          if (!empty($this->limit))
          {
               if ($this->limit['from'] != null)
               {

                    $query .= ' LIMIT ' . $this->limit['number'] . ' OFFSET ' . $this->limit['from'];

               } else
               {

                    $query .= ' LIMIT ' . $this->limit['number'];

               }
          }

          $query .= ' ;';

          $pdo_instance = DBConnection::getDBConnectionInst();
          $stmt = $pdo_instance->prepare($query);
           
          sizeof($this->bindValues) > 0 ? $stmt->execute($this->bindValues) : $stmt->execute();
          return $stmt;
     }

     // for pagination -> 1/2/3/4 --- 5/6/7/8
     protected function getCount() {

          $query = '';
          $query .= "SELECT COUNT(*) FROM " . $this->getTableName();

          //---------WHRE----------
          if (!empty($this->where))
          {
               $whereString = '';

               foreach ($this->where as $where)
               {

                    if ($whereString == '')
                    {

                         $whereString .= $where['condition'];

                    } else
                    {

                         $whereString .= ' ' . $where['operator'] . ' ' . $where['condition'];

                    }
               }

               $query .= ' WHERE ' . $whereString;

          }


          $query .= ' ;';
          // echo $query. '<hr>';

          $pdo_instance = DBConnection::getDBConnectionInst();

          $stmt = $pdo_instance->prepare($query);

          if (sizeof($this->bindValues) > sizeof($this->values))
          {

               sizeof($this->bindValues) > 0 ? $stmt->execute($this->bindValues) : $stmt->execute();

          } else
          {
               sizeof($this->values) > 0 ? $stmt->execute(array_values($this->values)) : $stmt->execute();
          }

          return $stmt->fetchColumn();
     }



     protected function getTableName() {
          return ' `' . $this->table . '` ';
     }

     protected function getAttributeName($attribute) {
          return ' `' . $this->table . '`.`' . $attribute . '` ';
     }

}




