<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\City
 *
 * @property int $id
 * @property int|null $province_id آی دی مشخص کننده استان این شهر
 * @property string|null $name نام شهر
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read \App\Province|null $province
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\City onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\City whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\City whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\City whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\City whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\City whereProvinceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\City whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\City withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\City withoutTrashed()
 * @mixin \Eloquent
 */
class City extends Model
{
    use SoftDeletes;
    /**      * The attributes that should be mutated to dates.        */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * @var array
     */
    protected $fillable = [
        'province_id',
        'name'
    ];

    public function province()
    {
        return $this->belongsTo('\App\Province');
    }
}
