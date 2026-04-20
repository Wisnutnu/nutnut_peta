<?php

namespace App\Services;

class DataPokokCalculator
{

    public static function hitung($jenis_ternak_id, $input, $parameter)
   {
    if (in_array($jenis_ternak_id,[1,2,3,4,5])) {
        return self::hitungPotongBesar($input,$parameter);
    }

    // if (in_array($jenis_ternak_id,[6,7,8])) {
    //     return self::hitungTernakSusu($input,$parameter);
    // }

    // if (in_array($jenis_ternak_id,[9,10,11,12,13])) {
    //     return self::hitungUnggasPetelur($input,$parameter);
    // }

    // if (in_array($jenis_ternak_id,[14,15])) {
    //     return self::hitungUnggasPedaging($input,$parameter);
    // }
}  

    public static function hitungPotongBesar($input, $parameter)
    {

        $pemotongan_total =
            $input['pemotongan_di_RPH_TPH'] +
            $input['pemotongan_diluar_RPH_TPH'] +
            $input['pemotongan_tidak_tercatat'];

        $karkas =
            $pemotongan_total * $parameter['berat_karkas'];

        $daging_murni =
            $pemotongan_total * $parameter['berat_daging_murni'];

        $jeroan =
            $pemotongan_total * $parameter['berat_jeroan'];

        $daging_variasi =
            $pemotongan_total * $parameter['persentase_berat_daging_variasi'];

        $karkas_jeroan_daging_variasi =
            $karkas + $jeroan + $daging_variasi;

        $dagingmurni_jeroan_daging_variasi =
            $daging_murni + $daging_variasi + $karkas_jeroan_daging_variasi;

        return [
            'pemotongan_total' => $pemotongan_total,
            'karkas' => $karkas,
            'daging_murni' => $daging_murni,
            'jeroan' => $jeroan,
            'daging_variasi' => $daging_variasi,
            'karkas_jeroan_daging_variasi' => $karkas_jeroan_daging_variasi,
            'dagingmurni_jeroan_daging_variasi' => $dagingmurni_jeroan_daging_variasi
        ];
    }
}