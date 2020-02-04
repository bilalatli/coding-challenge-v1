<?php

namespace Tests\Feature\Libs\Dynamics;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class Category extends TestCase
{
    /**
     * Test Dynamic Category
     *
     * @return void
     */
    public function testDynamicCategory()
    {
        $categories = [
            [ 'A', 'A1' ],
            [ 'A', 'B', ],
            [ 'D' ],
        ];

        $expected = [
            'A'  => [
                'id'       => 1,
                'parentID' => null,
            ],
            'A1' => [
                'id'       => 2,
                'parentID' => 1,
            ],
            'B'  => [
                'id'       => 3,
                'parentID' => 1,
            ],
            'D'  => [
                'id'       => 4,
                'parentID' => null,
            ],
        ];

        $categoryItems = [];
        foreach ($categories as $categoryBlock) {
            \App\Libs\Dynamics\Category::addCategory($categoryItems, $categoryBlock, null);
        }

        foreach ($categoryItems as $item) {
            /**
             * @var \App\Libs\Dynamics\Category $item
             */
            $this->assertTrue(property_exists($item, 'name'), 'Category dynamic class hasn\'t name attribute');
            $this->assertTrue(property_exists($item, 'id'), 'Category dynamic class hasn\'t id attribute');
            $this->assertTrue(property_exists($item, 'parentCategoryId'), 'Category dynamic class hasn\'t parentCategoryId attribute');

            $exp = $expected[$item->name];
            $this->assertEquals($item->id, $exp['id'], 'Formatted category id doesn\t match');
            $this->assertEquals($item->parentCategoryId, $exp['parentID'], 'Formatted parent category id doesn\t match');
        }
    }
}
