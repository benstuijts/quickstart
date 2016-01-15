{% extends 'templates/default.php' %}
{% block title %}Profiel | {{user.getFullNameOrUsername() }}{% endblock %}

{% import "user/macros/macros.php" as macro %}

{% block content %}
<div class="container">
    <h1>Alle leden ({{ users.count }})</h1> 
    {% for user in users %}
    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
        <a href="{{urlFor('user.profile')}}/{{user.username}}" class="row">
            <div class="col-xs-3">
                <img src="{{user.getAvatarUrl({baseUrl:baseUrl}) }}" width="48" height="48" class="img-thumbnail" alt="{{user.getFullNameOrUsername() }}">
            </div>
            <div class="col-xs-9">
                <h4>{{ user.getFullNameOrUsername() }}</h4>
            </div>
        </a>
        
        
        
        
       
    </div>
    {% endfor %}
</div>
{% endblock %}