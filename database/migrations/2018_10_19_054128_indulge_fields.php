<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IndulgeFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('indulge_fields', function (Blueprint $table) {
            $table->increments('id');
            $table->string('table', 30)->default('')->comment('字段所属的表名');
            $table->string('name', 30)->default('')->comment('字段名称');
            $table->string('label', 30)->default('')->comment('字段Label');
            $table->integer('type')->unsigned()->default(0)->comment('字段类型');
            $table->string('form_type', 30)->default('')->comment('表单类型');
            $table->integer('option_id')->unsigned()->default(0)->comment('选项值父ID');
            $table->string('require')->default('')->comment('验证规则');
            $table->string('default')->default('')->comment('默认值');
            $table->string('description', 300)->default('')->comment('描述');
            $table->tinyInteger('showable')->default(1)->comment('是否首页:1.是,0.否');
            $table->tinyInteger('writeable')->default(1)->comment('是否可写:1.是,0.否');
            $table->integer('weight')->unsigned()->default(0)->comment('权重');

            $table->softDeletes();
            $table->timestamps();

            $table->index( 'weight', 'weight' );
            $table->index( 'table', 'table' );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists( 'indulge_fields' );
    }
}
