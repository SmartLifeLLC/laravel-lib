<?php
/**
 * class ProductController
 * @package App\Http\Controllers
 * @Author : Sinyu Jung
 * Copyright :  WonderPlanet Inc. All rights reserved.
 * Last Modified  2018/01/14
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getList(Request $request){
        var_dump();
        var_dump($request->get('category'));
        var_dump($request->get('sort'));
        $keyword = base64_decode($request->get('keyword'));

    }
}