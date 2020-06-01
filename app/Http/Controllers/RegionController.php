<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Region;
use MStaack\LaravelPostgis\Geometries\Point;
use MStaack\LaravelPostgis\Geometries\LineString;
use MStaack\LaravelPostgis\Geometries\Polygon;

class RegionController extends Controller
{
    function index(){
        $result = DB::select(DB::raw("SELECT json_build_object('type', 'FeatureCollection','features', json_agg(ST_AsGeoJSON(t.*)::json)) as dataReturn FROM (SELECT * from regions) as t"));
        if(empty($result)){
            return response()->json([
                'status' => 404,
                'data' => null,
                'error_message' => 'Not found a area data.'
            ]);
        }
        return $result[0]->datareturn;
    }

    function show($id){
        $id = intval($id);

        $result = DB::select(DB::raw("SELECT * FROM regions WHERE id = {$id} LIMIT 1"));
        if(empty($result)){
            return response()->json([
                'status' => 404,
                'info' => null,
                'error_message' => 'Not found a area data.'
            ]);
        }

        $returnData = array(
            "status" => 200,
            "info" => array(
                "areaId" => $result[0]->area_id,
                "province_id" => intval($result[0]->province_id),
                "province_name" => $result[0]->province_name,
                "province_name_th" => $result[0]->province_name_th,
                "district_id" => intval($result[0]->district_id),
                "district_name" => $result[0]->district_name,
                "district_name_th" => $result[0]->district_name_th,
                "sub_district_id" => intval($result[0]->sub_district_id),
                "sub_district_name" => $result[0]->sub_district_name,
                "sub_district_name_th" => $result[0]->sub_district_name_th,
                "postcode" => $result[0]->postcode
            ),
            "dockets" => array(
                "area" => 0,
                "sub_district" => 0,
                "district" => 0,
                "province" => 0
            ),
            "sales" => array(
                "area" => 0,
                "sub_district" => 0,
                "district" => 0,
                "province" => 0
            ),
            'error_message' => null
        );

        $area_id = $result[0]->area_id;
        $province_id = intval($result[0]->province_id);
        $district_id = intval($result[0]->district_id);
        $sub_district_id = intval($result[0]->sub_district_id);

        $result_area = DB::select(DB::raw(
            "SELECT count(*) as row, sum(total) as total " .
            "FROM orders " .
            "WHERE ST_Within(location::geometry,(SELECT polygon::geometry FROM regions WHERE id = {$id})) = TRUE"
        ));

        if(!empty($result_area)){
            $returnData['dockets']['area'] = intval($result_area[0]->row);
            $returnData['sales']['area'] = intval($result_area[0]->total);
        }

        $result_sub = DB::select(DB::raw(
            "SELECT count(*) as row, sum(total) as total " .
            "FROM orders " .
            "WHERE ST_Within(location::geometry,(SELECT ST_Union(polygon::geometry) as area FROM regions WHERE province_id = {$province_id} AND district_id = {$district_id} AND sub_district_id = {$sub_district_id})) = TRUE"
        ));

        if(!empty($result_sub)){
            $returnData['dockets']['sub_district'] = intval($result_sub[0]->row);
            $returnData['sales']['sub_district'] = intval($result_sub[0]->total);
        }

        $result_dis = DB::select(DB::raw(
            "SELECT count(*) as row, sum(total) as total " .
            "FROM orders " .
            "WHERE ST_Within(location::geometry,(SELECT ST_Union(polygon::geometry) as area FROM regions WHERE province_id = {$province_id} AND district_id = {$district_id})) = TRUE"
        ));

        if(!empty($result_dis)){
            $returnData['dockets']['district'] = intval($result_dis[0]->row);
            $returnData['sales']['district'] = intval($result_dis[0]->total);
        }

        $result_pro = DB::select(DB::raw(
            "SELECT count(*) as row, sum(total) as total " .
            "FROM orders " .
            "WHERE ST_Within(location::geometry,(SELECT ST_Union(polygon::geometry) as area FROM regions WHERE province_id = {$province_id})) = TRUE"
        ));

        if(!empty($result_pro)){
            $returnData['dockets']['province'] = intval($result_pro[0]->row);
            $returnData['sales']['province'] = intval($result_pro[0]->total);
        }

        return response()->json($returnData);
    }

    function store(Request $request){
        
        if (!$request->hasFile('fileKML')) {
            return redirect('?error=1');
        }

        $path = $request->file('fileKML')->storeAs('', "tmp.kml");

        try{
            $feed = file_get_contents(storage_path($path));
            $xml = new \SimpleXMLElement($feed);

            $placemarks = $xml->Document->Folder->Placemark;
            $total = count($placemarks);
            for ($i = 0; $i < $total; $i++) {
                //Info Data
                $data = $placemarks[$i]->ExtendedData->SchemaData;
                $arrData = array();
                foreach($data->children() as $SimpleData){
                    if(
                        (string)$SimpleData['name']=="id" ||
                        (string)$SimpleData['name']=="province_id" ||
                        (string)$SimpleData['name']=="district_id" ||
                        (string)$SimpleData['name']=="sub_district_id"
                    ){
                        $arrData[(string)$SimpleData['name']] = (int)$SimpleData;
                    } else{
                        $arrData[(string)$SimpleData['name']] = (string)$SimpleData;
                    }
                }

                //Polygon Data
                $polygon = $placemarks[$i]->MultiGeometry->Polygon->outerBoundaryIs->LinearRing->coordinates;
                $arrPolygon = explode(' ', $polygon );
                $multipoint = array();
                foreach($arrPolygon as $point){
                    $tmp = explode(',', $point);
                    $multipoint[] = new Point($tmp[1], $tmp[0]);
                }
                $linestring = new LineString($multipoint);

                //Create or Update in regions table
                Region::updateOrCreate(
                    array("id" => $arrData['id']),
                    array_merge($arrData,array('polygon' => new Polygon([$linestring])))
                );
            }
        }catch (Exception $e) {
            return redirect('?error=2');
        }
        return redirect('map');
    }
}
