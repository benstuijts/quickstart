{% extends 'templates/default.php' %}
{% import "posts/macros/macros.php" as macro %}

{% block title %}Artikelen zoeken{% endblock %}

{% block content %}

<div class="container">

    <div class="row">
        <h2>Resultaten voor "{{ query }}" <small>(gevonden: {{ numberOfPosts }})</small></h2>
        <hr>
    </div>
    
    {% for post in attribute(posts, currentPage-1) %}

    <div class="row">
        <h1>{{ post.getTitle(query)|raw  }}</h1>
        <h2><small>{{ post.getSubtitle(query)|raw }}</small></h2>
        {{ macro.tags(post) }}
        
        <p>{{ post.getTextSnippitFromBody(query)|raw }}</p>
        {{ macro.socialbuttons(baseUrl, post, auth) }}
        <a class="btn btn-default" href="{{post.url}}">verder lezen...</a>
        {% if auth.isAdmin() %}
        <hr>
        <a href="{{post.url}}?edit=1" class="btn btn-sm btn-success">Edit</a>
        {% endif %}
    </div>

    {% endfor %}

    {% if numberOfPosts == 0 %}
        <p>Geen artikelen gevonden.</p>
        
    {% endif %}


</div>

{% endblock %}