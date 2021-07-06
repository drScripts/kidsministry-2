<?php 
namespace App\Controllers;

class CobaController extends BaseController{

    public function index(){
        return view('template/dashboard_content');
    }
}
