<?php

namespace App\models\acl;

use Illuminate\Database\Eloquent\Model;

class AclList extends Model
{
    protected $table = 'aclLists';
    public $timestamps = false;
    protected $connection = 'mysql2';
}
