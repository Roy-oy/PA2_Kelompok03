<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FaqCategory;
use Illuminate\Support\Str;

class FaqCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kategori yang diminta: umum, pendaftaran, layanan, dan pembayaran
        $categories = [
            'Umum',
            'Pendaftaran',
            'Layanan',
            'Pembayaran'
        ];

        foreach ($categories as $category) {
            FaqCategory::create([
                'name' => $category,
                'slug' => Str::slug($category)
            ]);
        }
    }
}