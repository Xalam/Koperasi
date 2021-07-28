<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSimpananTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_simpanan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_simpanan');
            $table->integer('id_anggota');
            $table->date('tanggal');
            $table->integer('jenis_simpanan')->comment('1 = pokok, 2 = wajib, 3 = sukarela');
            $table->integer('nominal');
            $table->string('image')->nullable();
            $table->string('keterangan')->nullable();
            $table->integer('status')->comment('0 = waiting, 1 = sukses')->default(0);
            $table->integer('type')->default(0)->comment('0 = Mobile, 1 = Web')->nullable();
            $table->string('kode_jurnal')->nullable();
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
        Schema::dropIfExists('tb_simpanan');
    }
}
