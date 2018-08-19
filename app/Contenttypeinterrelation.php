<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Contenttypeinterrelation
 *
 * @property int $id
 * @property string|null $name نام
 * @property string|null $displayName نام قابل نمایش
 * @property string|null $description توضیح
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Contenttypeinterrelation onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contenttypeinterrelation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contenttypeinterrelation whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contenttypeinterrelation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contenttypeinterrelation whereDisplayName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contenttypeinterrelation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contenttypeinterrelation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Contenttypeinterrelation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Contenttypeinterrelation withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Contenttypeinterrelation withoutTrashed()
 * @mixin \Eloquent
 */
class Contenttypeinterrelation extends Model
{
    use SoftDeletes;

    /**      * The attributes that should be mutated to dates.        */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $fillable = [
        "name",
        "displayName",
        "description"
    ];

}
