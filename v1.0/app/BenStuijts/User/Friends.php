<?php

namespace BenStuijts\User;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Friends extends Eloquent {
    
    protected $table = 'friends';
    
    protected $fillable = [
        'user_id',
        'friend_id',
        'accepted'
    ];
    
}

?>