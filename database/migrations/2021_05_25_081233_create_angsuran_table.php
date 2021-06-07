<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAngsuranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_angsuran', function (Blueprint $table) {
            $table->id();
            $table->string('kode_angsuran');
            $table->integer('id_pinjaman');
            $table->date('tanggal');
            $table->double('nominal_angsuran');
            $table->double('sisa_angsuran');
            $table->integer('sisa_bayar');
            $table->double('potongan')->default(0);
            $table->integer('status')->default(0)->comment('0 = Waiting, 1 = Accept');
            $table->integer('lunas')->default(0)->comment('0 = Belum Lunas, 1 = Lunas');
            $table->string('keterangan')->nullable();
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
        Schema::dropIfExists('tb_angsuran');
    }
}
