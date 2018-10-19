<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IndulgeValues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create( 'indulge_values', function (Blueprint $table) {
            $table->increments('id');
            $table->string('table', 30)->default('')->comment('所属表名');
            $table->integer('model_id')->unsigned()->default(0)->comment('数据模型id');
            $table->integer('field_id')->unsigned()->default(0)->comment('扩展字段id');
            $table->string('value')->default('')->comment('值');

            $table->index( 'model_id', 'model_id' );
            $table->index( 'field_id', 'field_id' );
            $table->index( 'table', 'table' );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('indulge_values');
    }
}
