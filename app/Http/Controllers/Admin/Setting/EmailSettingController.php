<?php
namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Admin\AdminController;

use App\MailTemplate;

class EmailSettingController extends AdminController{
    public function index(){
        return view('admin.setting.email.index');
    }

    public function template($type)
        {
        switch ($type) {
            case 'resset_password':
                $mail_template = MailTemplate::where('name' , 'resset_password')->get();
                return view('admin.setting.email.template' , compact('mail_template'));
            }
        }
}