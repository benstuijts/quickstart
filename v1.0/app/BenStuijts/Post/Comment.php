<?php

namespace BenStuijts\Post;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Comment extends Eloquent 
{
    protected $table = 'comments';
    
    protected $fillable = [
        'post_id',
        'user_id',
        'body',
        'status'
    ];
    
    public function test() {
        echo 'testing the public function of the Comment Class.';
    }
}

?>