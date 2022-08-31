<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePph extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payroll_pph', function (Blueprint $table) {
            $table->bigInteger('batas_bawah')->nullable()->change();
            $table->bigInteger('batas_atas')->nullable()->change();
        });

        \DB::STATEMENT("TRUNCATE payroll_pph");
        \DB::STATEMENT("INSERT INTO payroll_pph(id, batas_bawah, batas_atas, tarif, pajak_minimal, akumulasi_pajak, kondisi_lain) VALUES (1, 0, 60000000, 5, 0, 0, NULL)");
        \DB::STATEMENT("INSERT INTO payroll_pph(id, batas_bawah, batas_atas, tarif, pajak_minimal, akumulasi_pajak, kondisi_lain) VALUES (2, 60000000, 250000000, 15, 3000000, 3000000, NULL)");
        \DB::STATEMENT("INSERT INTO payroll_pph(id, batas_bawah, batas_atas, tarif, pajak_minimal, akumulasi_pajak, kondisi_lain) VALUES (3, 250000000, 500000000, 25, 28500000, 31500000, NULL)");
        \DB::STATEMENT("INSERT INTO payroll_pph(id, batas_bawah, batas_atas, tarif, pajak_minimal, akumulasi_pajak, kondisi_lain) VALUES (4, 500000000, 5000000000, 30, 62500000, 94000000, NULL)");
        \DB::STATEMENT("INSERT INTO payroll_pph(id, batas_bawah, batas_atas, tarif, pajak_minimal, akumulasi_pajak, kondisi_lain) VALUES (5, 5000000000, 5000000000, 35, 1350000000, 1444000000, '>')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
