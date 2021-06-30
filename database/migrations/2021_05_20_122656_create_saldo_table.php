<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaldoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_saldo', function (Blueprint $table) {
            $table->id();
            $table->integer('id_anggota');
            $table->integer('saldo');
            $table->integer('jenis_simpanan')->comment('1 = pokok, 2 = wajib, 3 = sukarela');
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
        Schema::dropIfExists('tb_saldo');
    }
}
