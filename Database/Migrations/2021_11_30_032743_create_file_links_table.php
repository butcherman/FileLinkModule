<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFileLinksTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('file_links', function (Blueprint $table) {
            $table->id('link_id');
            $table->unsignedBigInteger('user_id');
            $table->text('link_hash');
            $table->text('link_name');
            $table->date('expire');
            $table->longText('instructions')->nullable();
            $table->boolean('allow_upload');
            $table->timestamps();
            $table->foreign('user_id')->references('user_id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('file_links', function(Blueprint $table)
        {
            $table->dropForeign(['user_id', 'cust_id']);
        });
        Schema::dropIfExists('file_links');
    }
}
