<?php
namespace System\Database\DBBuilder;
use System\Config\Config;
use System\Database\DBConnection\DBConnection;

class DBBuilder
{

    public function __construct() {
        $this->createTables();
        die('migrations run success...');
    }

//---------------------------------------------------------------

// اولین جداول برای ساخت یعنی جداولی که جدید اضافه شدن و برای اولین بار قراره اضافه بشوند
    private function getMigrations() {

        $oldMigrationsArray = $this->getOldMigration();
        $migratinsDirectorys = Config::get('app.BASE_DIR') . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR ;

        $allMigrationsArray = glob($migratinsDirectorys . "*.php");
        
        $newMigrationsArray = array_diff($allMigrationsArray , $oldMigrationsArray);

        $this->putOldMigration($allMigrationsArray);
        $sqlCodeArray = [];
        foreach($newMigrationsArray as $fileName){

            $sqlCode = require $fileName;
            array_push($sqlCodeArray , $sqlCode[0]);
        }

        return $sqlCodeArray;

    }

// دریافت جداولی که قبلا ساخته شدند
    private function getOldMigration(){

        $data = file_get_contents(__DIR__ . '/oldTables.db');
        return empty($data) ? [] : unserialize($data);

    }

//اضافه کردن جداولی که اضافه شدند به دیتابیس به فایل oldTables.db
    private function putOldMigration($value){

        file_put_contents(__DIR__.'/oldTables.db' , serialize($value) );

    }
//------------------------------------------------------------

    private function createTables() {
        $migrations = $this->getMigrations();
        $pdoInstance = DBConnection::getDBConnectionInst();

        foreach ($migrations as $migration)
        {
            $stmt = $pdoInstance->prepare($migration);
            $stmt->execute();
        }
        return true;

    }


}









