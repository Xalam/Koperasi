<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaldoTarikTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_saldo_tarik', function (Blueprint $table) {
            $table->id();
            $table->integer('id_saldo');
            $table->date('tanggal');
            $table->integer('nominal');
            $table->integer('status')->comment('0 = Waiting, 1 = Proses, 2 = Sukses')->default(0);
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
        Schema::dropIfExists('tb_saldo_tarik');
    }
}
