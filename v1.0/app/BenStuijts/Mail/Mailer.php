<?php

namespace BenStuijts\Mail;

class Mailer {
    
    protected $mailer;
    protected $config;
    protected $view;
    
    public function __construct($view, $config, $mailer) {
        $this->view = $view;
        $this->config = $config;
        $this->mailer = $mailer;
    }
    
    public function send($template, $data, $callback) {
        
        $builder = $this->mailer->MessageBuilder();
        
        $message = new Message($builder);
        $message->from($this->config->get('mail.from'));
        
        $this->view->appendData($data);
        
        $message->body($this->view->render($template));
        
        call_user_func($callback, $message);
        
        $domain = $this->config->get('mail.domain');
        $this->mailer->post("{$domain}/messages", $builder->getMessage());
        
    }
    
}

?>