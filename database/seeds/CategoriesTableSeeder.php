<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'name' => 'Books',
                'children' => [
                    [
                        'name' => 'Comic Book',
                        'children' => [
                            ['name' => 'Marvel Comic Book'],
                            ['name' => 'DC Comic Book'],
                            ['name' => 'Action comics'],
                        ],
                    ],
                    [
                        'name' => 'Textbooks',
                        'children' => [
                            ['name' => 'Business'],
                            ['name' => 'Finance'],
                            ['name' => 'Computer Science'],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Electronics',
                'children' => [
                    [
                        'name' => 'TV',
                        'children' => [
                            ['name' => 'LED'],
                            ['name' => 'Blu-ray'],
                        ],
                    ],
                    [
                        'name' => 'Mobile',
                        'children' => [
                            ['name' => 'Samsung'],
                            ['name' => 'iPhone'],
                            ['name' => 'Xiomi'],
                        ],
                    ],
                ],
            ],
        ];
        foreach($categories as $category)
        {
            \App\Category::create($category);
        }
    }
}
