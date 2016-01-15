{% extends 'templates/default.php' %}

{% block title %}Login{% endblock %}

{% block content %}



<div class="row">
    <div class="col-lg-6 col-lg-offset-3 col-md-10 col-md-offset-1 col-sm-12">
        <div class='alert alert-info fade in' data-alert='alert'>
            <img src="images/login.svg" class="float-left-100px">
            <h3>Welkom terug!</h3>
            <p>
                Op HockeyTips vindt je de meest actuele tips en trucs voor iedere
                hockeycoach of -trainer over de begeleiding en ontwikkeling van spelers,
                teambuilding, oefeningen, beleidsmatige zaken, tactiek en fysieke ontwikkeling.
            </p>
            <hr>
            <form role="form" action="{{ urlFor('login.post') }}" method="POST" autocomplete="off">
                
                <div class="form-group">
                    <label for="identifier">Username of email</label>
                    <input type="text" class="form-control" name="identifier" id="identifier"{% if request.post('identifier') %} value="{{ request.post('identifier') }}"{%endif%}>
                    
                    {% if errors.first('identifier') %}
                        <p class="help-block alert alert-danger">
                            <i class="fa fa-exclamation-circle"></i> {{ errors.first('identifier') }}
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
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">
                        Remember me
                    </label>
                </div>
                
                <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-log-in" aria-hidden="true"></span> Login</button>
                <hr>
                <div class="form-group">
                    <a href="{{ urlFor('password.recover') }}">Forgot your password?</a>
                </div>
                <input type="hidden" name="{{ csrf_key }}" value="{{ csrf_token }}">
            </form>
        </div>
    </div>
</div>


{% endblock %}