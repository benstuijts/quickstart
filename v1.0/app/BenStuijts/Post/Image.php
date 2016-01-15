<?php

namespace BenStuijts\Post;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Image extends Eloquent 
{
    protected $table = 'images';
    
    protected $fillable = [
        'url',
        'filename',
        'description',
        'slug',
        'status'
    ];
    
    public function test() {
        echo 'testing the public function of the Comment Class.';
    }
}

?>