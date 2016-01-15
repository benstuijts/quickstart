{% extends 'templates/default.php' %}
{% import "posts/macros/macros.php" as macro %}

{% block title %}CMS Image Upload{% endblock %}

{% block content %}
<div class="container">
    <h1><i class="fa fa-lock"></i> Afbeeldingen uploaden</h1>
    
    <form id="" enctype="multipart/form-data" method="post" action="{{ urlFor('cms.image.upload.post')}}">
            <div class="form-group">
                <label>Bestand</label>
                <input type="file" class="form-control" name="image" required>
            </div>
            <div class="form-group">
                <label>Omschrijving</label>
                <input type="text" class="form-control" name="description" required>
            </div>
            <div class="form-group">
                <label>Slug</label>
                <input type="text" class="form-control" name="slug" required>
            </div>
            
            <input class="btn btn-default" type="submit" value="Upload">
            <a href="{{urlFor('cms')}}" type="submit" class="btn btn-warning">Cancel</a>
            {{ macro.scrfField(csrf_key, csrf_token) }}
    </form>

</div>
{% endblock %}