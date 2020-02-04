<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Categories
 *
 * @property int         $id
 * @property string      $category
 * @property int         $parent_id
 * @property Carbon      $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 * @package App
 */
class Categories extends Model
{
    use SoftDeletes;

    /**
     * Table Name
     *
     * @var string
     */
    protected $table = 'categories';

    /**
     * Cast Field Definitions
     *
     * @var array
     */
    protected $casts = [
        'parent_id' => 'int',
        'category'  => 'string',
    ];

    /**
     * Fillable Fields
     *
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'category',
    ];
}
