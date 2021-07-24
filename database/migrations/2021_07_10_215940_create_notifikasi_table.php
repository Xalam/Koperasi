<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotifikasiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_notifikasi', function (Blueprint $table) {
            $table->id();
            $table->integer('id_anggota');
            $table->string('title');
            $table->string('content');
            $table->integer('status')->default(0)->comment('0 = unread, 1 = read');
            $table->integer('type')->default(0)->comment('0 = Mobile, 1 = Web')->nullable();
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
        Schema::dropIfExists('tb_notifikasi');
    }
}
