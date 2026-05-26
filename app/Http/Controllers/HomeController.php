<?php
namespace App\Http\Controllers;

use System\Database\DBBuilder\DBBuilder;


class HomeController extends Controller
{

     public function index() {
        // view('admin.index');
     }

     public function create() {
        // view('admin.index');
     }

     public function edit($id) {
          echo '<h1>this is edit : ' . $id . '</h1>';
     }

     public function store() {
          echo '<h1>this is store</h1>';
     }

     public function update($id) {
          echo '<h1>this is update : ' . $id . '</h1>';
     }

     public function destroy($id) {
          echo '<h1>this is destroy : .' . $id . '</h1>';
     }

}