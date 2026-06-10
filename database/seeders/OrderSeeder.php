<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $orders = [
            [
                'nama_pemesan' => 'Budi Santoso',
                'nomor_wa'     => '081234567890',
                'email'        => 'budi.santoso@gmail.com',
                'nama_produk'  => 'Kopi Arabica Premium 250g',
                'jumlah'       => 2,
                'status'       => 'selesai',
            ],
            [
                'nama_pemesan' => 'Siti Rahayu',
                'nomor_wa'     => '082345678901',
                'email'        => 'siti.rahayu@yahoo.com',
                'nama_produk'  => 'Kopi Arabica Premium 250g',
                'jumlah'       => 1,
                'status'       => 'diproses',
            ],
            [
                'nama_pemesan' => 'Ahmad Fauzi',
                'nomor_wa'     => '083456789012',
                'email'        => 'ahmad.fauzi@gmail.com',
                'nama_produk'  => 'Kopi Robusta Dark Roast 500g',
                'jumlah'       => 3,
                'status'       => 'baru',
            ],
            [
                'nama_pemesan' => 'Dewi Lestari',
                'nomor_wa'     => '084567890123',
                'email'        => 'dewi.lestari@outlook.com',
                'nama_produk'  => 'Kopi Arabica Premium 250g',
                'jumlah'       => 5,
                'status'       => 'selesai',
            ],
            [
                'nama_pemesan' => 'Rizky Pratama',
                'nomor_wa'     => '085678901234',
                'email'        => 'rizky.pratama@gmail.com',
                'nama_produk'  => 'Kopi Luwak Asli 100g',
                'jumlah'       => 1,
                'status'       => 'diproses',
            ],
            [
                'nama_pemesan' => 'Nur Hidayah',
                'nomor_wa'     => '086789012345',
                'email'        => 'nur.hidayah@gmail.com',
                'nama_produk'  => 'Kopi Robusta Dark Roast 500g',
                'jumlah'       => 2,
                'status'       => 'baru',
            ],
            [
                'nama_pemesan' => 'Dimas Ardiansyah',
                'nomor_wa'     => '087890123456',
                'email'        => 'dimas.ardi@gmail.com',
                'nama_produk'  => 'Kopi Arabica Premium 250g',
                'jumlah'       => 4,
                'status'       => 'selesai',
            ],
            [
                'nama_pemesan' => 'Rina Marlina',
                'nomor_wa'     => '088901234567',
                'email'        => 'rina.marlina@yahoo.com',
                'nama_produk'  => 'Kopi Luwak Asli 100g',
                'jumlah'       => 2,
                'status'       => 'baru',
            ],
            [
                'nama_pemesan' => 'Hendra Gunawan',
                'nomor_wa'     => '089012345678',
                'email'        => 'hendra.g@gmail.com',
                'nama_produk'  => 'Kopi Robusta Dark Roast 500g',
                'jumlah'       => 1,
                'status'       => 'diproses',
            ],
            [
                'nama_pemesan' => 'Fitria Wulandari',
                'nomor_wa'     => '081122334455',
                'email'        => 'fitria.w@outlook.com',
                'nama_produk'  => 'Kopi Arabica Premium 250g',
                'jumlah'       => 3,
                'status'       => 'selesai',
            ],
        ];

        foreach ($orders as $order) {
            Order::create($order);
        }
    }
}
