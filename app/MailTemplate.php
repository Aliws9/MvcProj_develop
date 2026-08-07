<?php
namespace App;

use System\Database\ORM\Model;

class MailTemplate extends Model
    {

    protected $table = "mail_template";

    protected $fillable = ['name', 'subject', 'html', 'status'];

    }