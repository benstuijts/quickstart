{% extends 'templates/default.php' %}
{% import "posts/macros/macros.php" as macro %}

{% block title %}CMS Image Upload{% endblock %}

{% block content %}
<div class="container">
    <h1><i class="fa fa-lock"></i> Opgeslagen Afbeeldingen</h1>
    
    <table class="table">
        <tr><th>thumbnail</th><th>url</th><th>Omschrijving</th><th>slug</th></tr>
        {% for image in images %}
        
            <tr>
                <td><img src="{{image.url}}" class="img-thumbnail" width="128"></td>
                <td>{{image.url}}</td>
                <td>{{image.description}}</td>
                <td>{{image.slug}}</td>
            </tr>
        
        {% endfor %}
    </table>

</div>
{% endblock %}