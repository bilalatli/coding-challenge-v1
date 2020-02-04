<?php

namespace App\Libs\Dynamics;


use Exception;
use JsonSerializable;
use Illuminate\Contracts\Support\Arrayable;

/**
 * @author  : Bilal ATLI
 * @date    : 30.03.2019 15:44
 * @mail    : <bilal@sistemkoin.com>, <ytbilalatli@gmail.com>
 * @phone   : +90 0-542-433-09-19
 *
 * @package App\Libs\Dynamics;
 */
class Category implements Arrayable, JsonSerializable
{
    /**
     * @var integer
     */
    public $id;
    /**
     * @var string
     */
    public $name;

    /**
     * @var int|null
     */
    public $parentCategoryId;

    /**
     * Compare Category Name Name
     *
     * @param string $name
     *
     * @return bool
     */
    public function compareName($name)
    {
        return mb_strtolower($this->name, 'utf-8') === mb_strtolower($name, 'utf-8');
    }

    /**
     * @param Category[]   $categories
     * @param string[]     $categoryNames
     * @param integer|null $parentCategoryId
     */
    public static function addCategory(&$categories, $categoryNames, $parentCategoryId = null)
    {
        if (count($categoryNames) === 0) {
            return;
        }
        $firstKey = array_keys($categoryNames)[0];
        $firstCategoryName = $categoryNames[$firstKey];
        foreach ($categories as $key => $category) {
            if ($category === null) {
                continue;
            }
            if ($category->compareName($firstCategoryName)) {
                try {
                    unset($categoryNames[$firstKey]);
                } catch (Exception $e) {
                    // Do Nothing
                }
                self::addCategory($categories, $categoryNames, $category->id);
                return;
            }
        }
        foreach ($categoryNames as $categoryName) {
            $newCategory = new Category();
            $newCategory->id = count($categories) + 1;
            $newCategory->name = $categoryName;
            $newCategory->parentCategoryId = $parentCategoryId;
            $categories[] = $newCategory;
            $parentCategoryId = $newCategory->id;
        }
    }


    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'id'       => $this->id,
            'name'     => $this->name,
            'parentID' => $this->parentCategoryId,
        ];
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @link  https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
