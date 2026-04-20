<?php

namespace App\Services\DataPokok;

class ProduksiService
{
    public function hitungProduksi(array $data, array $parameter): float
    {
        $populasi = $data['populasi'];

        if ($parameter['tipe_rumus'] === 'rumus_1') {

            return round(
                $populasi *
                ($parameter['pemotongan_terhadap_populasi'] / 100) *
                $parameter['berat_karkas'] *
                (1 + ($parameter['jerohan_terhadap_karkas'] / 100)),
                2
            );

        } elseif ($parameter['tipe_rumus'] === 'rumus_2') {

            return round(
                $populasi *
                (1 - ($parameter['deplesi'] / 100)) *
                $parameter['bobot_panen_livebird'] *
                ($parameter['konversi_livebird_ke_karkas'] / 100),
                2
            );
        }

        return 0;
    }
}
