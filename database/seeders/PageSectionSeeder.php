<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PageSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = \App\Models\Language::all();
        
        // Create default home page sections
        $sections = [
            [
                'page' => 'home',
                'type' => 'hero_banner',
                'name' => 'Hero Banner',
                'is_active' => true,
                'sort_order' => 1,
                'settings' => [
                    'height' => '600px',
                    'autoplay' => true,
                    'interval' => 5000,
                ],
                'data' => [
                    'banner_ids' => []
                ],
                'translations' => [
                    'en' => [
                        'title' => 'Welcome to Our Store',
                        'subtitle' => 'Discover amazing products at great prices',
                        'button_text' => 'Shop Now',
                        'button_url' => '/products',
                    ],
                    'ar' => [
                        'title' => 'مرحباً بكم في متجرنا',
                        'subtitle' => 'اكتشف منتجات رائعة بأسعار ممتازة',
                        'button_text' => 'تسوق الآن',
                        'button_url' => '/products',
                    ],
                    'fr' => [
                        'title' => 'Bienvenue dans notre magasin',
                        'subtitle' => 'Découvrez des produits incroyables à des prix imbattables',
                        'button_text' => 'Acheter maintenant',
                        'button_url' => '/products',
                    ],
                ]
            ],
            [
                'page' => 'home',
                'type' => 'category_grid',
                'name' => 'Featured Categories',
                'is_active' => true,
                'sort_order' => 2,
                'settings' => [
                    'columns' => 4,
                    'show_count' => 8,
                ],
                'data' => [
                    'category_ids' => [],
                    'auto_display' => true, // Auto-display featured categories
                ],
                'translations' => [
                    'en' => [
                        'title' => 'Shop by Category',
                        'subtitle' => 'Find what you need quickly',
                    ],
                    'ar' => [
                        'title' => 'تسوق حسب الفئة',
                        'subtitle' => 'ابحث عما تحتاجه بسرعة',
                    ],
                    'fr' => [
                        'title' => 'Acheter par catégorie',
                        'subtitle' => 'Trouvez rapidement ce dont vous avez besoin',
                    ],
                ]
            ],
            [
                'page' => 'home',
                'type' => 'product_grid',
                'name' => 'Featured Products',
                'is_active' => true,
                'sort_order' => 3,
                'settings' => [
                    'columns' => 4,
                    'show_count' => 8,
                ],
                'data' => [
                    'product_ids' => [],
                    'auto_display' => true, // Auto-display featured products
                ],
                'translations' => [
                    'en' => [
                        'title' => 'Featured Products',
                        'subtitle' => 'Our best selling items',
                    ],
                    'ar' => [
                        'title' => 'المنتجات المميزة',
                        'subtitle' => 'أفضل منتجاتنا مبيعاً',
                    ],
                    'fr' => [
                        'title' => 'Produits vedettes',
                        'subtitle' => 'Nos articles les plus vendus',
                    ],
                ]
            ],
        ];

        foreach ($sections as $sectionData) {
            $translations = $sectionData['translations'];
            unset($sectionData['translations']);
            
            $section = \App\Models\PageSection::create($sectionData);
            
            // Create translations for each language
            foreach ($languages as $language) {
                $translationData = $translations[$language->code] ?? $translations['en'];
                $translationData['page_section_id'] = $section->id;
                $translationData['language_id'] = $language->id;
                
                \App\Models\PageSectionTranslation::create($translationData);
            }
        }
    }
}
