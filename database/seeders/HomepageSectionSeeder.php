<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PageSection;
use App\Models\PageSectionTranslation;
use App\Models\Language;

class HomepageSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get languages
        $french = Language::where('code', 'fr')->first();
        $arabic = Language::where('code', 'ar')->first();

        if (!$french || !$arabic) {
            $this->command->error('Languages not found. Please run LanguageSeeder first.');
            return;
        }

        // Create homepage sections
        $sections = [
            [
                'type' => 'hero_banner',
                'name' => 'Hero Banner',
                'is_active' => true,
                'sort_order' => 1,
                'settings' => json_encode([
                    'component' => 'Hero',
                    'description' => 'Main hero banner with CTA buttons'
                ]),
                'translations' => [
                    'fr' => [
                        'title' => 'Bannière Hero',
                        'subtitle' => 'Découvrez notre nouvelle collection',
                        'content' => 'Bannière principale avec boutons d\'appel à l\'action'
                    ],
                    'ar' => [
                        'title' => 'لافتة البطل',
                        'subtitle' => 'اكتشف مجموعتنا الجديدة',
                        'content' => 'اللافتة الرئيسية مع أزرار الدعوة للعمل'
                    ]
                ]
            ],
            [
                'type' => 'featured_products',
                'name' => 'Featured Products',
                'is_active' => true,
                'sort_order' => 2,
                'settings' => json_encode([
                    'component' => 'FeaturedProducts',
                    'limit' => 8,
                    'description' => 'Carousel showing featured items'
                ]),
                'translations' => [
                    'fr' => [
                        'title' => 'Produits Vedettes',
                        'subtitle' => 'Nos meilleures sélections',
                        'content' => 'Carrousel affichant les articles vedettes'
                    ],
                    'ar' => [
                        'title' => 'المنتجات المميزة',
                        'subtitle' => 'أفضل اختياراتنا',
                        'content' => 'عرض شرائح يعرض العناصر المميزة'
                    ]
                ]
            ],
            [
                'type' => 'products_grid',
                'name' => 'Products Section',
                'is_active' => true,
                'sort_order' => 3,
                'settings' => json_encode([
                    'component' => 'ProductsSection',
                    'per_page' => 8,
                    'description' => 'Grid of products'
                ]),
                'translations' => [
                    'fr' => [
                        'title' => 'Section Produits',
                        'subtitle' => 'Tous nos produits',
                        'content' => 'Grille de produits'
                    ],
                    'ar' => [
                        'title' => 'قسم المنتجات',
                        'subtitle' => 'جميع منتجاتنا',
                        'content' => 'شبكة المنتجات'
                    ]
                ]
            ],
            [
                'type' => 'categories_grid',
                'name' => 'Categories Grid',
                'is_active' => true,
                'sort_order' => 4,
                'settings' => json_encode([
                    'component' => 'CategoriesSection',
                    'columns' => 2,
                    'description' => 'Shop by category section'
                ]),
                'translations' => [
                    'fr' => [
                        'title' => 'Grille Catégories',
                        'subtitle' => 'Acheter par catégorie',
                        'content' => 'Section magasiner par catégorie'
                    ],
                    'ar' => [
                        'title' => 'شبكة الفئات',
                        'subtitle' => 'تسوق حسب الفئة',
                        'content' => 'قسم التسوق حسب الفئة'
                    ]
                ]
            ]
        ];

        foreach ($sections as $sectionData) {
            $section = PageSection::create([
                'page' => 'home',
                'type' => $sectionData['type'],
                'name' => $sectionData['name'],
                'is_active' => $sectionData['is_active'],
                'sort_order' => $sectionData['sort_order'],
                'settings' => $sectionData['settings']
            ]);

            // Create translations
            foreach ($sectionData['translations'] as $langCode => $translation) {
                $language = Language::where('code', $langCode)->first();
                if ($language) {
                    PageSectionTranslation::create([
                        'page_section_id' => $section->id,
                        'language_id' => $language->id,
                        'title' => $translation['title'],
                        'subtitle' => $translation['subtitle'],
                        'content' => $translation['content']
                    ]);
                }
            }
        }

        $this->command->info('Homepage sections seeded successfully!');
    }
}
