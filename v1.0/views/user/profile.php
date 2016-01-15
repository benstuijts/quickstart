{% extends 'templates/default.php' %}
{% block title %}Profiel | {{user.getFullNameOrUsername() }}{% endblock %}

{% import "user/macros/macros.php" as macro %}

{% block content %}
<div class="container">
    
    <div class="row">
        <div class="col-xs-4 col-sm-3 col-md-2">
            <a href="{{ urlFor('user.avatar.update') }}" title="klik hier om avatar te wijzigen">
                <img src="{{user.getAvatarUrl({size: 128, baseUrl: baseUrl}) }}" class="img-thumbnail img-responsive " alt="{{user.getFullNameOrUsername() }}">    
            </a>
            
        </div>
        <div class="col-xs-8 col-sm-9 col-md-10">
            <h1>{{user.getFullNameOrUsername() }}
            {% if user.username == auth.username %}
            <a href="{{urlFor('user.profile.update')}}" class="pull-right"><i class="fa fa-cog"></i></a></h1>
            {% endif %}
            <h2><small>{{user.club}}, {{user.job}}</small></h2>
            <h3<small>{{user.city}}</small></h3>
            <p>
                <i class="fa fa-tag"></i>
                {% if user.getTags()[0] == '' %}
                    <i class="fa fa-info-circle"></i> Vul je profiel verder aan door op <a href="{{urlFor('user.profile.update')}}"><i class="fa fa-cog"></i></a> te klikken.
                {% else %}
                {% for tag in user.getTags() %}
                <a href="">{{tag}}</a>
                {% endfor %}
                {% endif %}
            </p>
        </div>
    </div>
    <hr>
    
    
{% if option == '' %}

    <div class="row">
        <h2><span class="glyphicon glyphicon-heart" aria-hidden="true"></span> Mijn opgeslagen artikelen</h2>
        <ul class="list-group">
        
        {% if myFavoritePosts == null %}
            <p>Nog geen artikelen opgeslagen.</p>
        {% endif %}
            
        {% for post in myFavoritePosts  %}
            <li class="list-group-item">
                
                <div class="pull-right">
                    <a type="button" href="{{urlFor('post.unlike')}}?id={{auth.id}}&art={{post.id}}$url={{baseUrl}}/{{post.url}}" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></a>
                </div>
                
                <a href="{{ baseUrl}}/{{ favo.url }}">{{ post.getTitle() }}</a>
            
            </li>
            
        {% endfor %}
        </ul>
        
        <a href="{{ urlFor('posts.all') }}">Bekijk hier alle artikelen.</a>
        
    </div>
{% endif %}


{% if option == 'user.avatar.update' %}

    <div class="row">
        <h2>Nieuwe avatar uploaden</h2>
        <hr>
        
        <form id="avatar_form" enctype="multipart/form-data" method="post" action="{{ urlFor('user.avatar.upload')}}">
            <div class="form-group">
                <input type="file" name="avatar" required>
            </div>
            
            <input class="btn btn-default" type="submit" value="Upload">
            <a href="{{urlFor('user.profile')}}/{{auth.username}}" type="submit" class="btn btn-warning">Cancel</a>
            {{ macro.scrfField(csrf_key, csrf_token) }}
        </form>
    </div>
  
{% endif %}
    
{% if option == 'user.profile.update' %}

    <h2>Profiel gegevens wijzigen van {{user.getFullNameOrUsername()}}</h2>
    <hr>
    <form method="post" action="{{urlFor('user.profile.update.post')}}" class="form-horizontal">    
    {{ macro.textField('first_name', 'Voornaam', user.first_name) }}
    {{ macro.textField('last_name', 'Achternaam', user.last_name) }}
    {{ macro.textField('email', 'Email', user.email) }}
    {{ macro.textField('username', 'Gebruikersnaam', user.username) }}
    {{ macro.textField('city', 'Woonplaats', user.city) }}
    <div class="form-group">
    <label class="col-sm-2 control-label" for="{{name}}">Geslacht</label>
    <div class="col-sm-10">
        <fieldset>
            <input type="radio" name="gender" value="{{preset}}" />man<br />
            <input type="radio" name="gender" value="{{preset}}" />vrouw<br />
        </fieldset>
    </div>
    </div>
    
    {{ macro.textField('club', 'Club', user.club) }}
    {{ macro.textField('job', 'Functie', user.job) }}
    {{ macro.textField('tags', 'Tags', user.tags) }}
    
    {{ macro.scrfField(csrf_key, csrf_token) }}
     <button type="submit" class="btn btn-success">Opslaan</button>
     <a href="{{urlFor('user.profile')}}/{{auth.username}}" type="submit" class="btn btn-warning">Cancel</a>
    </form>                    
{% endif %}                 
                                    
</div>
    

    <!-- CONTENT -->

{% endblock %}