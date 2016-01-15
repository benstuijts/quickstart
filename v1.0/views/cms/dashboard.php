{% extends 'templates/default.php' %}
{% import "posts/macros/macros.php" as macro %}

{% block title %}CMS Dashboard{% endblock %}

{% block content %}
<div class="container">
    <h1><i class="fa fa-lock"></i> Dashboard</h1>
    <table class="table table-hover">
    <tr>
        <th>titel</th><th>url</th><th>auteur</th><th>views</th><th>likes</th><th>favoriet</th>
    </tr>
    {% for post in posts %}
        <tr>
            <td>
                {{ post.title }}
            </td>
            <td>
                {{ post.url }}
            </td>
            <td>
                {{ auth.getNameFromId(post.author) }}
            </td>
            <td>
                {{ post.views }}
            </td>
            <td>
                {{ post.likes }}
            </td>
            <td>
                {{ post.favorite }}
            </td>
            <td>
                <a href="{{post.url}}" class="btn btn-sm btn-default">Bekijken</a>
            </td>
        </tr>
    {% endfor %}
    </table>
</div>
{% endblock %}