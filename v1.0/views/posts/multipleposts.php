{% extends 'templates/default.php' %}
{% import "posts/macros/macros.php" as macro %}

{% block title %}Artikelen{% endblock %}

{% block content %}

<div class="container">

    {{ breadcrumb.group }}
    
<!--
{% for page in posts %}
    {% for post in page %}
        {{ dump(post.title) }}
    {% endfor %}
{% endfor %}
-->
    
    
    {% for post in attribute(posts, currentPage-1) %}

    <div class="row">
        <h1>{{ post.title }}</h1>
        <h2><small>{{ post.subtitle }}</small></h2>
        {{ macro.tags(post) }}
        
        <p class="clearfix">{{ post.getFirstParagraph()|raw }}</p>
        
        {{ macro.socialbuttons(baseUrl, post, auth) }}
        <a class="btn btn-default" href="{{post.url}}">verder lezen...</a>
        {% if auth.isAdmin() %}
        <hr>
        <a href="{{post.url}}?edit=1" class="btn btn-sm btn-success">Edit</a>
        {% endif %}
    </div>

    {% endfor %}
    
    
    <ul class="pagination">
        {% if currentPage > 1 %}
        <li class="previous"><a href="{{urlFor('posts.all')}}?pagina={{currentPage-1}}&number={{postsPerPage}}" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
        {% endif %}
        {% for page in 1..numberOfPages %}
            {% if page == currentPage %}
            <li class="active"><a href="{{urlFor('posts.all')}}?pagina={{page}}&number={{postsPerPage}}">{{page}}</a></li>
            {% else %}
        <li><a href="{{urlFor('posts.all')}}?pagina={{page}}&number={{postsPerPage}}">{{page}}</a></li>
            {% endif %}
        {% endfor %}
        {% if currentPage < numberOfPages %}
        <li class="next"><a href="{{urlFor('posts.all')}}?pagina={{currentPage+1}}&number={{postsPerPage}}" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
        {% endif %}
    </ul>
    
</div>

{% endblock %}
