{% extends 'templates/default.php' %}
{% import "posts/macros/macros.php" as macro %}

{% block title %}CMS | nieuw artikel{% endblock %}

{% block content %}

<div class="container">
    <ul>
    {% for image in imageList %}
        <li>{{image}}</li>    
    {% endfor %}
    </ul>
    
    <form method="post" action="{{urlFor('cms.new.post')}}" class="form-horizontal">
        <h1><i class="fa fa-lock"></i> Nieuwe Post</h1>
        <div class="alert alert-info" role="alert">
            {{ macro.textfield('title', 'Titel', '') }}
            {{ macro.textfield('subtitle', 'Subtitel', '') }}
            {{ macro.textfield('newsgroup', 'Nieuwsgroep', '') }}
            {{ macro.textfield('url', 'url', '') }}
            {{ macro.textfield('tags', 'tags', '') }}
            {{ macro.textfield('audience', 'Publiek | groep', '') }}
            {{ macro.textfield('image', 'Titel image', '') }}
            {{ macro.textfield('status', 'status (1 = concept, 2=live, 3=deleted)', 1) }}
        </div>
        <div class="form-group">
        <textarea id="tinymce" class="mce" name="body" style="width:100%"></textarea>
    </div>
    
    <div class="alert alert-info" role="alert">
    {{ macro.textfield('footer', 'footer', '') }}
    </div>
    <div class="form-group">
        <input type="submit" value=" opslaan" class="btn btn-primary">
    </div>
    
    {{ macro.scrfField(csrf_key, csrf_token) }}
    </form>
    
</div>

<script>
    tinymce.init({
    selector: ".mce",
    plugins: [
        "advlist autolink lists link charmap print preview anchor",
        "searchreplace visualblocks code fullscreen",
        "insertdatetime media table contextmenu paste",
        "autoresize image"
    ],
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | image",
    image_list: [
        {% for image in imageList %}
        {title: '{{ image.name }}', value: '{{image.name}}.jpg'},
        {% endfor %}
    ]
});
</script>
    
    
{% endblock %}