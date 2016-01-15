{% extends 'templates/default.php' %}

{% block title %}CMS | overview{% endblock %}

{% block content %}
<div class="container">
    <h1><i class="fa fa-lock"></i> Overzicht van alle artikelen</h1>
    <table class="table table-hover">
    
    {% for post in posts %}
        <tr>
            <td>
                {{ post.title }}
            </td>
            <td><img src="{{post.image}}" width="64"></td>
            
            <td>
                {{ auth.getNameFromId(post.author) }}
            </td>
            <td>
                <form class="pull-right">
                    <a href="{{post.url}}" class="btn btn-sm btn-default">Bekijken</a>
                    <a href="{{urlFor('cms.delete')}}/{{post.id}}" class="btn btn-sm btn-default">Delete</a>
                    {% if post.status == 1 %}
                    <a href="{{urlFor('cms.publish')}}/{{post.id}}" class="btn btn-sm btn-info">Publiceer</a>
                    {% endif %}
                    {% if post.status == 2 %}
                    <a href="{{urlFor('cms.mute')}}/{{post.id}}" class="btn btn-sm btn-default">Mute</a>
                    {% endif %}
                    {% if post.status == 3 %}
                    <a href="{{urlFor('cms.mute')}}/{{post.id}}" class="btn btn-sm btn-default">Restore</a>
                    {% endif %}
                    
                    <a href="{{post.url}}?edit=1" class="btn btn-sm btn-success">Edit</a>
                </form>
            </td>
        </tr>
    {% endfor %}
    </table>
</div>
    
{% endblock %}

