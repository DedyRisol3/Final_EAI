<?php

namespace Database\Seeders;

// Import Model Product
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    
    public function run(): void
    {
        

        // Data Produk
        $products = [
            [
                'name' => 'Jahit Kemeja Formal Pria',
                'slug' => 'kemeja-formal',
                'description' => 'Bahan katun premium, jahitan presisi. Cocok untuk kerja atau acara resmi.',
                'price' => 250000,
                'image_url' => 'https://www.atalon.id/cdn/shop/files/LIF_7038_NAVY.jpg?v=1712075389&width=1000',
                'category' => 'kemeja',
            ],
            [
                'name' => 'Desain & Jahit Gaun Pesta',
                'slug' => 'gaun-pesta',
                'description' => 'Wujudkan gaun impian Anda. Konsultasi desain gratis. Bahan brokat, satin, dll.',
                'price' => 800000,
                'image_url' => 'https://asset-a.grid.id/crop/0x0:0x0/x/photo/2023/08/21/vallinajpg-20230821023146.jpg',
                'category' => 'gaun',
            ],
            [
                'name' => 'Sablon Kaos Komunitas',
                'slug' => 'kaos-sablon',
                'description' => 'Minimal 12 pcs. Bahan Cotton Combed 30s. Sablon DTF atau Plastisol.',
                'price' => 85000,
                'image_url' => 'https://dagadu.co.id/cdn/shop/files/id-11134207-7rbk8-m73e223kjjexa5.jpg?v=1744257593&width=1445',
                'category' => 'kaos',
            ],
            [
                'name' => 'Pembuatan Seragam Kantor',
                'slug' => 'seragam-kantor',
                'description' => 'Kemeja PDH/PDL, wearpack, almamater. Termasuk bordir logo perusahaan.',
                'price' => 0,
                'image_url' => 'https://werpak.id/wp-content/uploads/2022/12/Seragam-kerja-lapangan-wanita.jpg',
                'category' => 'seragam',
            ],
            [
                'name' => 'Jahit Kemeja Batik Custom',
                'slug' => 'kemeja-batik',
                'description' => 'Jahit batik pria/wanita dengan furing atau tanpa furing. Model slim-fit modern.',
                'price' => 275000,
                'image_url' => 'https://www.elfs-shop.com/~img/kfbj_batik_katun_8f17007_bt_0-c93b8-3073_3214-t2494_81.webp',
                'category' => 'batik',
            ],
            [
                'name' => 'Jasa Permak & Repair',
                'slug' => 'permak',
                'description' => 'Potong celana, kecilkan pinggang, ganti ritsleting, dan perbaikan lainnya.',
                'price' => 20000,
                'image_url' => 'https://www.mokapos.com/blog/_next/image?url=http%3A%2F%2Fwp.mokapos.com%2Fwp-content%2Fuploads%2F2024%2F05%2F228203101_l_normal_none.jpg&w=1200&q=75',
                'category' => 'permak',
            ],
        ];

        
        foreach ($products as $productData) {
            Product::create($productData);
        }
    }
}