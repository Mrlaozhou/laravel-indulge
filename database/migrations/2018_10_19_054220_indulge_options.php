<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IndulgeOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create( 'indulge_options', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 60)->default('')->comment('名称');
            $table->string('keywords', 60)->default('')->comment('标识');
            $table->integer('pid')->default(0)->unsigned()->comment('父级id');
            $table->integer('weight')->unsigned()->default(0)->comment('排序权重');

            $table->softDeletes();
            $table->timestamps();

            $table->index( 'keywords', 'keywords' );
            $table->index( 'pid', 'pid' );
            $table->index( 'weight', 'weight' );
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
        Schema::dropIfExists( 'indulge_options' );
    }
}
