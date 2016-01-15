{% extends 'templates/default.php' %}

{% block title %}Register{% endblock %}
{% block content %}
<div class="row vertical-center">
    <div class="col-lg-6 col-lg-offset-3 col-md-10 col-md-offset-1 col-sm-12">
        
        <div class='alert alert-info fade in' data-alert='alert'>
            <img src="images/register.svg" class="float-left-100px">
            <h3>Registreren is <strong>gratis</strong> en <strong>gemakkelijk</strong>!</h3>
            <p>
                Vul hieronder je emailadres, username en password twee maal in. 
                Daarna ontvang je op het opgegeven emailadres een mail, met
                daarin de activatielink van jouw account.
            </p>
            <hr>
            <form role="form" action="{{ urlFor('register.post') }}" method="POST" autocomplete="off">
                
                <div class="form-group">
                    <label class="control-label" for="email">Email address</label>
                    <input type="email" class="form-control" name="email" id="email"{% if request.post('email') %} value="{{ request.post('email') }}"{%endif%}>
                    {% if errors.first('email') %}
                        <p class="help-block alert alert-danger">
                            <i class="fa fa-exclamation-circle"></i> {{ errors.first('email') }}
                        </p>
                    {% endif %}
                </div>
                
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" name="username" id="username"{% if request.post('username') %} value="{{ request.post('username') }}"{%endif%}>
                    {% if errors.first('username') %}
                        <p class="help-block alert alert-danger">
                            <i class="fa fa-exclamation-circle"></i> {{ errors.first('username') }}
                        </p>
                    {% endif %}
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" name="password" id="password">
                    {% if errors.first('password') %}
                        <p class="help-block alert alert-danger">
                            <i class="fa fa-exclamation-circle"></i> {{ errors.first('password') }}
                        </p>
                    {% endif %}
                </div>
                
                <div class="form-group">
                    <label for="password_confirm">Confirm password</label>
                    <input type="password" class="form-control" name="password_confirm" id="password_confirm">
                    {% if errors.first('password_confirm') %}
                        <p class="help-block alert alert-danger">
                            <i class="fa fa-exclamation-circle"></i> {{ errors.first('password_confirm') }}
                        </p>
                    {% endif %}
                </div>
                <button type="submit" class="btn btn-primary btn-lg">Klik hier om de registratie af te ronden.</button>
                <a href="{{ urlFor('login') }}" class="btn btn-default btn-lg">Ik heb al een account...</a>
                <input type="hidden" name="{{ csrf_key }}" value="{{ csrf_token }}">
            </form>
        </div>
        
        
        
        
        
        
    </div>
</div>
{% endblock %}