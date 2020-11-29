<?php

namespace App\models\acl;

use Illuminate\Database\Eloquent\Model;

class AclUserRelations extends Model
{
    protected $table = 'aclUserRelations';
    public $timestamps = false;
    protected $connection = 'mysql2';
}
