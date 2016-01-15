<?php

namespace BenStuijts\Middleware;

use Slim\Middleware;

class BeforeMiddleware extends Middleware {
    
    public function call() {
        
        $this->app->hook('slim.before', [$this, 'run']);
        
        $this->next->call();
    }
    
    public function run() {
        
        /* Volgens mij wordt de site langzamer wanneer deze check plaatsvindt... 
        $route = $this->app->request->getResourceUri();
        $article = $this->app->post->getArticle($url);
        
        if($article != '/' ) {
            $this->app->render('posts/singlepost.php');
        }
        */
        
        if(isset($_SESSION[$this->app->config->get('auth.session')])) {
            $this->app->auth = $this->app->user->where('id', $_SESSION[$this->app->config->get('auth.session')])->first();
        }
        
        $this->checkRememberMe();
        
        $this->app->view()->appendData([
            'auth' => $this->app->auth,
            'baseUrl' => $this->app->config->get('app.url'),
            'name' => $this->app->config->get('app.name'),
            'pendingComments' => $this->app->comment->where('status', 0)->get()->count(),
            'info' => $this->app->user,
        ]);
        
        
    }
    
    protected function checkRememberMe() {
        
        if($this->app->getCookie($this->app->config->get('auth.remember')) && !$this->app->auth) {
            $data = $this->app->getCookie($this->app->config->get('auth.remember'));
            $credentials = explode('___', $data);
            
            if(empty(trim($data)) || count($credentials) !== 2 ) {
                $this->app->response->redirect($this->app->urlFor('home'));
            } else {
                $identifier = $credentials[0];
                $token = $this->app->hash->createHash($credentials[1]);
                
                $user = $this->app->user
                ->where('remember_identifier', $identifier)
                ->first();
                
                if($user) {
                    if($this->app->hash->hashCheck($token, $user->remember_token)) {
                        // Log the user in.
                        $_SESSION[$this->app->config->get('auth.session')] = $user->id;
                        $this->app->auth = $this->app->user
                            ->where('id', $user->id)
                            ->first();
                    } else {
                        $user->removeRememberCredentials();
                    }
                }
                
            }
            
        }
        
    }
    
}

