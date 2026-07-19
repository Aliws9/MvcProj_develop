<?php
namespace App\Http\Controllers\Admin;

class TestController extends AdminController
    {
        public function view() {
            return view('admin.test.test2');
        }
    }