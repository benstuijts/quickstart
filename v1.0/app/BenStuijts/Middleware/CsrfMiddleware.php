<?php

namespace BenStuijts\Middleware;

use Slim\Middleware;

class CsrfMiddleware extends Middleware {
    
    protected $key;
    
    public function call() {
        $this->key = $this->app->config->get('csrf.key');
        $this->app->hook('slim.before', [$this, 'check']);
        $this->next->call();
    }
    
    public function check() {
        
        if (!isset($_SESSION[$this->key])) {
            $_SESSION[$this->key] = $this->app->hash->createHash(
                $this->app->randomlib->generateString(128)
            );
        }
        
        $token = $_SESSION[$this->key];
        
        // get url variables
        if($this->app->request()) {
            $query_string = $this->app->request();
            
            
            
            //var_dump($query_string->get());
        }
        
        
        if(in_array($this->app->request()->getMethod(), ['POST', 'PUT', 'DELETE'])) {
            $submittedToken = $this->app->request()->post($this->key) ?: '';
            
            if(!$this->app->hash->hashCheck($token, $submittedToken)) {
                $this->app->flash('danger', 'NO scrf match!' . $submittedToken . '|' . $token);
                $this->app->response->redirect($this->app->urlFor('home'));
            } 
            
        }
        
        $this->app->view()->appendData([
            'csrf_key' => $this->key,
            'csrf_token' => $token
        ]);
    }
    
}

function decrypt($string, $key) {
    return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, base64_decode($string), MCRYPT_MODE_ECB));   
}

function encrypt($string, $key) {
    return rtrim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $string, MCRYPT_MODE_ECB)));
}
