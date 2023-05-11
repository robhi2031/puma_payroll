<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePajakTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pajak', function (Blueprint $table) {
            $table->id();
            $table->string('pju_bn', 16)->unsigned()->nullable();
            $table->foreign('pju_bn')
                ->references('pju_bn')
                ->on('man_power')
                ->onDelete('cascade');
            $table->integer('overtime_hours', 10)->nullable();
            $table->decimal('overtime', 9, 3);
            $table->string('tunjangan_tetap', 100)->nullable();
            $table->string('tunjangan_tidaktetap', 100)->nullable();
            $table->string('premi', 100)->nullable();
            $table->string('bonus_thr', 100)->nullable();
            $table->string('potongan_absensi', 100)->nullable();
            $table->decimal('bpjs_kes_fcompany', 9, 3); //=> potongan x (basic-potongan absensi) => 4% x (4.500.000 - 0)
            $table->decimal('jkk_fcompany', 9, 3); //=> potongan x (basic-potongan absensi) => 1.74% x (4.500.000 - 0)
            $table->decimal('jkm_fcompany', 9, 3); //=> potongan x (basic-potongan absensi) => 0.3% x (4.500.000 - 0)
            $table->decimal('jht_fcompany', 9, 3); //=> potongan x (basic-potongan absensi) => 3.7% x (4.500.000 - 0)
            $table->decimal('jp_fcompany', 9, 3); //=> potongan x (basic-potongan absensi) => 2% x (4.500.000 - 0)
            $table->decimal('p_bruto', 9, 3); //=> Penghasilan Bruto = Basic + (ttl jumlah tunjangan) - (potongan absensi + Potongan Pajak/bpjs/dll from Company)
            $table->decimal('bpjs_kes_femployee', 9, 3); //=> potongan x (basic-potongan absensi) => 1% x (4.500.000 - 0)
            $table->decimal('jht_femployee', 9, 3); //=> potongan x (basic-potongan absensi) => 2% x (4.500.000 - 0)
            $table->decimal('jp_femployee', 9, 3); //=> potongan x (basic-potongan absensi) => 1% x (4.500.000 - 0)
            $table->decimal('biaya_jabatan', 9, 3); //=> Jika (5% x Penghasilan Bruto) > 500.000 => 500.000 || Jika Tidak maka hasilnya 5% x Penghasilan Bruto
            $table->decimal('penghasilan_net', 9, 3); //=> Penghasilan Bruto - (Potongan bpjs/dll from Employee + biaya_jabatan)
            $table->decimal('net_setahun', 9, 3); //=> Penghasilan Net x 12
            $table->decimal('ptkp', 9, 3); //=> "S/L (Belum Menikah)" => Rp54.000.000 || "M0 (Menikah Tanpa Tanggungan)" => Rp58.500.000 || "M1 (Menikah 1 Tanggungan)" => Rp63.000.000 || "M2 (Menikah 2 Tanggungan)" => Rp67.500.000 || "M3 (Menikah >= 3 Tanggungan)" => Rp72.000.000
            $table->decimal('pkp', 9, 3); //=> Net Tahunan - PTKP
            $table->decimal('pph_teratur', 9, 3);
            /*  => PKP <= 60.000.000 = (PKP x 5%) / 12
                => PKP > 60.000.000 && PKP <= 250.000.000 = ((60.000.000 x 5%) + (PKP - 60.000.000) x 15%) / 12
                => PKP > 250.000.000 && PKP <= 500.000.000 = ((60.000.000 x 5%) + (200.000.000 x 15%) + ((PKP - 250 000.000) x 25%)) / 12
                => PKP > 500.000.000 = ((60.000.000 x 5%) + (200.000.000 x 15%) + (250.000.000 x 25%) + ((PKP - 500.000.000) x 30%)) / 12
            */
            $table->decimal('pph21', 9, 3); // ROUND(IF(PPH Teratur <= 0 = 0 || PPH Teratur) 0)
            $table->integer('user_add');
            $table->integer('user_updated')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('NULL on update CURRENT_TIMESTAMP'))->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('man_power');
    }
}
