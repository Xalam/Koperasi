<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJurnalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_jurnal', function (Blueprint $table) {
            $table->id();
            $table->string('kode_jurnal');
            $table->integer('id_akun');
            $table->date('tanggal');
            $table->string('keterangan');
            $table->double('debet')->default(0);
            $table->double('kredit')->default(0);
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
        Schema::dropIfExists('tb_jurnal');
    }
}
