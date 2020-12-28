<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $table = config('region.table', 'areas');
        $prefix = config('region.prefix', '');
        Schema::create($prefix . $table, function (Blueprint $table) {
            $table->increments('id');
            $table->string('area_name', 32)->default('')->comment('地区名称');
            $table->unsignedInteger('area_parent_id', false)->default(0)->comment('父级id');
            $table->unsignedTinyInteger('area_level', false)->default(0)->comment('级别');
            $table->string('area_path', 64)->default('')->comment('名称路径');
            $table->unsignedTinyInteger('status', false)->default(1)->comment('状态 1可用0不可');
            $table->timestamps();
            $table->index(['area_parent_id'], 'idx_areas_parent_id');
            $table->index(['area_parent_id','area_level'], 'idx_areas_parent_level');
            $table->index(['status'], 'idx_user_areas_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $prefix = config('region.prefix', '');
        $table = config('services.region.table', 'areas');
        Schema::dropIfExists($prefix . $table);
    }
}
