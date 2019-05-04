<?php


namespace App\models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;


/**
 * An Eloquent Model: 'User'
 *
 * @property integer $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $phone
 * @property string $username
 * @property string $password
 * @property string $invitationCode
 * @property string $link
 * @property string $picture
 * @property integer $cityId
 * @property integer $level
 * @property boolean $uploadPhoto
 * @property boolean $status
 * @method static \Illuminate\Database\Query\Builder|\App\models\User whereUserName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\models\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\models\User whereLevel($value)
 * @method static \Illuminate\Database\Query\Builder|\App\models\User whereInvitationCode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\models\User wherePhone($value)
 */

class User extends Authenticatable{

    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */


    protected $table = 'users';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */

    protected $fillable = [
        'username', 'password'
    ];

    protected $hidden = array('password', 'remember_token');

    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    public function getAuthIdentifier() {
        return $this->getKey();
    }
    public function getAuthPassword() {
        return $this->password;
    }

    public static function whereId($value) {
        return User::find($value);
    }
}