<?php

namespace BenStuijts\Helpers;

class Hash 
{
    protected $config;
    
    public function __construct($config) {
        $this->config = $config;
    }    
    
    public function password($password) {
        return password_hash($password, 
            $this->config->get('app.hash.algo'),
            ['cost' => $this->config->get('app.hash.cost')]
        );
    }
    
    public function passwordCheck($password, $hash) {
        return password_verify($password, $hash);    
    }
    
    public function Hash($input) {
        createHash($input);
    }
    
    public function createHash($input) {
        return hash('sha256', $input);
    }

    public function hashCheck($known, $user) {
        
        if(strcmp($known, $user) == 0) {
            return true;
        } else {
            return false;
        }
        
        
        // Deze functie werkt alleen met PHP version > 5.6.0 (cloud9IDE werkt met 5.5.9!!!)
        //return hash_equals($known, $user);
    }

}