<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsItemLinksNotesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            $table->text('value');
            $table->text('description')->nullable();
            $table->integer('user_id')->unsigned();
            $table->integer('itemable_id')->unsigned()->nullable();
            $table->string('itemable_type')->nullable();
            $table->timestamps();
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('user_id')->unsigned()->nullable();
            $table->timestamps();
        });

        Schema::create('item_tag', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_id')->unsigned()->nullable();
            $table->integer('tag_id')->unsigned()->nullable();
        });

        Schema::create('links', function (Blueprint $table) {
            $table->increments('id');
            $table->text('url');
            $table->text('title')->nullable();
            $table->text('photo')->nullable();
            $table->text('photo_url')->nullable();
            $table->timestamps();
        });

        Schema::create('notes', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('notes');
        Schema::drop('links');
        Schema::drop('item_tag');
        Schema::drop('tags');
        Schema::drop('items');
    }
}
