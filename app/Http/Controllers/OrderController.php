<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{

    function index(){
        $result = DB::select(DB::raw("SELECT json_build_object('type', 'FeatureCollection','features', json_agg(ST_AsGeoJSON(t.*)::json)) as dataReturn FROM (SELECT * from orders) as t"));
        if(empty($result)){
            return response()->json([
                'status' => 404,
                'data' => null,
                'error_message' => 'Not found a area data.'
            ]);
        }
        return $result[0]->datareturn;
    }
}
