<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'Restaurant'
 *
 * @property integer $id
 * @property integer $cityId
 * @property string $name
 * @property string $address
 * @property string $site
 * @property string $phone
 * @property string $description
 *
 * @property boolean $food_irani
 * @property boolean $food_mahali
 * @property boolean $food_farangi
 * @property boolean $coffeeshop
 * @property boolean $tarikhi
 * @property boolean $markaz
 * @property boolean $hoome
 * @property boolean $shologh
 * @property boolean $khalvat
 * @property boolean $tabiat
 * @property boolean $kooh
 * @property boolean $darya
 * @property boolean $class
 *
 * @property boolean $modern
 * @property boolean $sonnati
 * @property boolean $ghadimi
 * @property boolean $mamooli
 * @property boolean $kind_id
 *
 * @property string $file
 *
 * @property boolean $pic_1
 * @property boolean $pic_2
 * @property boolean $pic_3
 * @property boolean $pic_4
 * @property boolean $pic_5
 *
 * @property float $C
 * @property float $D
 *
 * @property string $meta
 * @property string $alt1
 * @property string $alt2
 * @property string $alt3
 * @property string $alt4
 * @property string $alt5
 *
 * @property string $tag1
 * @property string $tag2
 * @property string $tag3
 * @property string $tag4
 * @property string $tag5
 * @property string $tag6
 * @property string $tag7
 * @property string $tag8
 * @property string $tag9
 * @property string $tag10
 * @property string $tag11
 * @property string $tag12
 * @property string $tag13
 * @property string $tag14
 * @property string $tag15
 *
 * @property string $keyword
 * @property string $h1
 *
 * @property boolean $authorized
 * @property integer $author
 * 
 * @method static \Illuminate\Database\Query\Builder|\App\models\Restaurant whereCityId($value)
 */

class Restaurant extends Model {

    protected $table = 'restaurant';
    public $timestamps = false;

    public static function whereId($value) {
        return Restaurant::find($value);
    }
}
