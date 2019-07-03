<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('menu_items', function (Blueprint $table) {
            $table->increments('id');

            $table->string('path');
            $table->integer('parent')->unsigned()->default(0);

            $table->integer('menu_id')->unsigned()->default(1);
            $table->foreign('menu_id')->references('id')->on('menus');

            $table->timestamps();
        });

        Schema::create('menu_item_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('menu_item_id')->unsigned();
            $table->string('locale')->index();

            $table->string('title');

            $table->unique(['menu_item_id','locale']);
            $table->foreign('menu_item_id')->references('id')->on('menu_items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_items');

        if (Schema::hasTable('menu_item_translations')){
            Schema::drop('menu_item_translations');
        }
        if (Schema::hasTable('menu_items')){
            Schema::drop('menu_items');
        }
    }
}
