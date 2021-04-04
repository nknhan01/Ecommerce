<?php

namespace Database\Seeders;

use Platform\Base\Supports\BaseSeeder;
use Platform\SimpleSlider\Models\SimpleSlider;
use Platform\SimpleSlider\Models\SimpleSliderItem;

class SimpleSliderSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->uploadFiles('sliders');

        SimpleSlider::truncate();
        SimpleSliderItem::truncate();

        SimpleSlider::create([
            'name'        => 'Home slider',
            'key'         => 'home-slider',
            'description' => 'The main slider on homepage',
        ]);

        $items = [
            [
                'title'       => 'Woman Fashion',
                'description' => 'Get up to 50% off Today Only!',
            ],
            [
                'title'       => 'Man Fashion',
                'description' => '50% off in all products',
            ],
            [
                'title'       => 'Summer Sale',
                'description' => 'Taking your Viewing Experience to Next Level',
            ],
        ];

        foreach ($items as $index => $item) {
            $item['order'] = $index + 1;
            $item['simple_slider_id'] = 1;
            $item['image'] = 'sliders/' . ($index + 1) . '.jpg';
            $item['link'] = '/products';

            SimpleSliderItem::create($item);
        }
    }
}
