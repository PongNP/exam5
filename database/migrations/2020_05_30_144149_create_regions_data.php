<?php

use Illuminate\Database\Migrations\Migration;
use MStaack\LaravelPostgis\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegionsData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('regions', function(Blueprint $table)
        {
            $table->integer('id');
            $table->string('area_id', 12);
            $table->integer('province_id');
            $table->integer('district_id');
            $table->integer('sub_district_id');
            $table->string('province_name');
            $table->string('province_name_th');
            $table->string('district_name');
            $table->string('district_name_th');
            $table->string('sub_district_name');
            $table->string('sub_district_name_th');
            $table->string('postcode', 6);
            $table->polygon('polygon');
            
            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('regions');
    }
}
