<?php

namespace Encore\Stickies\Http\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Encore\Stickies\Http\Models\StickyModel
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $path
 * @property mixed $sticky
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Encore\Stickies\Http\Models\StickyModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Encore\Stickies\Http\Models\StickyModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Encore\Stickies\Http\Models\StickyModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Encore\Stickies\Http\Models\StickyModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Encore\Stickies\Http\Models\StickyModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Encore\Stickies\Http\Models\StickyModel whereSticky($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Encore\Stickies\Http\Models\StickyModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Encore\Stickies\Http\Models\StickyModel whereUserId($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\Encore\Stickies\Http\Models\StickyModel whereName($value)
 */
class StickyModel extends Model
{
    protected $fillable = ['name', 'user_id', 'sticky', 'path'];

    public static $methodColor = [
        'GET'       => 'green',
        'POST'      => 'yellow',
        'PUT'       => 'blue',
        'DELETE'    => 'red',
        'PATCH'     => 'black',
        'OPTIONS'   => 'grey',
    ];

    /**
     * Settings constructor.
     *
     * @param array $attributes
     */
    public function __construct($attributes = [])
    {
        parent::__construct($attributes);

        $this->setConnection(config('admin.database.connection') ?: config('database.default'));

        $this->setTable(config('admin.extensions.stickies.table', 'stickies'));
    }

}
