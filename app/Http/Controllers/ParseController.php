<?php

namespace App\Http\Controllers;

use App\Filters\AdvertFilters;
use App\Models\Parse;
use Illuminate\Http\Request;



class ParseController extends Controller
{

    public function index(AdvertFilters $filter, Request  $request)
    {
          $search = $request->search;
          $price = $request->input('price');
          $data = Parse::filter($filter)->paginate(25);
          return view('adv', compact('data', 'search', 'price'));
    }

    public function show(Parse $adv)
    {
        $adv->desc;
        return view('show', compact('adv'));
    }


}
