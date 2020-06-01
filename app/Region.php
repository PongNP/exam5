<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use MStaack\LaravelPostgis\Eloquent\PostgisTrait;
use MStaack\LaravelPostgis\Geometries\Point;

class Region extends Model
{
    use PostgisTrait;

    protected $table = 'regions';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'area_id',
        'province_id',
        'district_id',
        'sub_district_id',
        'province_name',
        'province_name_th',
        'district_name',
        'district_name_th',
        'sub_district_name',
        'sub_district_name_th',
        'postcode',
        'polygon'
    ];

    protected $postgisFields = [
        'polygon'
    ];

    protected $postgisTypes = [
        'polygon' => [
            'geomtype' => 'geography',
            'srid' => 4326
        ]
    ];
}