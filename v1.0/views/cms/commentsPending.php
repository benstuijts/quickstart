{% extends 'templates/default.php' %}
{% import "posts/macros/macros.php" as macro %}

{% block title %}CMS Comments{% endblock %}

{% block content %}
<div class="container">
    <h1><i class="fa fa-lock"></i> Reacties wachten op goedkeuring</h1>
    <table class="table table-hover">
    <tr>
        <th>id</th><th>post_id</th><th>auteur</th><th>comment</th>
    </tr>
    
    {% for comment in commentsToAccept %}
        <tr>
            <td>{{ comment.id }}</td>
            <td>
                {{ comment.post_id }}
            </td>
            <td>{{ comment.user_id }}</td>
            <td>{{ comment.body }}</td>
            <td><a href="{{urlFor('comment.accept', { id_post: comment.post_id, id_comment: comment.id, url: 'cms' }) }}" class="btn btn-success pull-right">Accept</a></td>
        </tr>
    {% endfor %}
    </table>

    <h1><i class="fa fa-lock"></i> Reacties van users van de website</h1>
    <table class="table table-hover">
    <tr>
        <th>id</th><th>post_id</th><th>auteur</th><th>comment</th>
    </tr>
    
    {% for comment in acceptedComments %}
        <tr>
            <td>{{ comment.id }}</td>
            <td>{{ comment.post_id }}</td>
            <td>{{ comment.user_id }}</td>
            <td>{{ comment.body }}</td>
            <td>
                <a href="{{urlFor('comment.mute', { id_post: comment.post_id, id_comment: comment.id, url: 'cms' }) }}" class="btn btn-default pull-right">Mute</a>
            </td>
            <td>
                <a href="{{urlFor('comment.delete', { id_post: comment.post_id, id_comment: comment.id, url: 'cms' }) }}" class="btn btn-danger pull-right">Delete</a>
            </td>
        </tr>
    {% endfor %}
    </table>


</div>
{% endblock %}