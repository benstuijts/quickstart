{% extends 'templates/default.php' %}
{% import "posts/macros/macros.php" as macro %}

{% block title %}{{post.title}} | {{post.author }}{% endblock %}

{% block content %}
<div class="container">
    
    <div class="row">
        <div class="col-md-6">
            {% if previousArticle %}
            <p class=""><a href="{{baseUrl}}/{{previousArticle.url}}"><i class="fa fa-arrow-circle-o-left"></i> {{ previousArticle.title }}</a></p>
            {% endif %}
        </div>
        <div class="col-md-6">
            {% if nextArticle %}
            <p class="pull-right"><a href="{{baseUrl}}/{{nextArticle.url}}">{{ nextArticle.title }} <i class="fa fa-arrow-circle-o-right"></i></a></p>
            {% endif %}
        </div>
        
    </div>    

    
    
    {{ macro.postnav() }}
<!-- Normale post -->
{% if edit == '' %}
    {{ macro.socialbuttons(baseUrl, post, auth) }}
    <h1>id {{ post.id }}{{post.title|capitalize}}</h1>
    <h2 class="text-muted">{{post.subtitle|capitalize}}</h2>
    
    
    
    {{ macro.tags(post) }}
    {{ macro.metadata(post, auth) }}
    <hr>
    <img src="{{post.image}}" class="img-responsive">
    <p class="clearfix">
        {{post.renderBody()|raw}}
    </p>
    <hr>
    {{ macro.socialbuttons(baseUrl, post, auth) }}
    {% if auth.isAdmin() %}
    <a href="{{post.url}}?edit=1" class="btn btn-sm btn-success ">Edit</a>
    {% endif %}
    
    <hr>
    <h3><i class="fa fa-comment"></i> Reageer</h3>

<div class="row">
    <div class="col-xs-12 col-sm-8 col-md-6">
        <form action="{{ urlFor('comment.new') }}" method="post">
        {% if auth %}
            <input type="hidden" name="user_id" value="{{auth.id}}">
        {% else %}
            <input type="hidden" name="user_id" value="guest">
        {% endif %}
            <input type="hidden" name="post_id" value="{{post.id}}">
            <input type="hidden" name="url" value="{{post.url}}">
        <textarea name="body" rows="4" cols="50">
        </textarea>
        <br>
        <button type="submit" class="btn btn-default pull-right">stuur reactie in</button>
        {{ macro.scrfField(csrf_key, csrf_token) }}
        </form>
    </div>
</div>
    
    
    
    
    
    
    
    <!-- Betere look comments: http://bootsnipp.com/snippets/featured/user-comment-example -->
    
    <h3><i class="fa fa-comments"></i> Reacties:</h3>
    {% for comment in comments %}
    
        {% if comment.status == 0 and auth.isAdmin() %}
        
        <div class="row">
            <div class="col-xs-1 col-md-1 hidden-xs">
                <img src="{{auth.getAvatarUrl({baseUrl:baseUrl, id: comment.user_id}) }}" width="72" height="72" class="img-thumbnail" alt="{{user.getFullNameOrUsername() }}">
            </div>
            <div class="col-xs-11 col-md-11">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <i class="fa fa-exclamation-circle"></i> 
                        {{ auth.getNameFromId(comment.user_id)}} (waiting to approve)<small class="pull-right">{{comment.created_at}}</small></h3>
                </div>
                <div class="panel-body">
                    {{comment.body }}
                    {% if auth.isAdmin() %}
                    <a href="{{urlFor('comment.accept', { id_post: post.id, id_comment: comment.id, url: post.url }) }}" class="btn btn-success pull-right">Accept</a>
                {% endif %}
                </div>
            </div>
            </div>
            
        </div>
        
        {% endif %}
        
        {% if comment.status == 1 and auth %}
        <div class="row">
    
            <div class="col-xs-1 col-md-1 hidden-xs">
                <img src="{{auth.getAvatarUrl({baseUrl:baseUrl, id: comment.user_id}) }}" width="72" height="72" class="img-thumbnail" alt="{{user.getFullNameOrUsername() }}">
            </div>
            
            <div class="col-xs-11 col-md-11">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ auth.getNameFromId(comment.user_id)}} <small class="pull-right">{{comment.created_at}}</small></h3>
                </div>
                <div class="panel-body">
                    {{comment.body }}
                    {% if auth.isAdmin() %}
                    <a href="{{urlFor('comment.mute', { id_post: post.id, id_comment: comment.id, url: post.url }) }}" class="btn btn-default pull-right">Mute</a>
                    {% endif %}
                    
                    {% if auth.id == comment.user_id %}
                    <a href="{{urlFor('comment.delete', { id_post: post.id, id_comment: comment.id, url: post.url }) }}" class="btn btn-danger pull-right">Delete</a>
                    {% endif %}
                    
                </div>
            </div>
            </div>
            
        </div>
        {% endif %}
    
        {% if comment.status == 1 and not auth %}
        <div class="row">
    
            
            
            <div class="col-xs-12 col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ auth.getNameFromId(comment.user_id)}} <small class="pull-right">{{comment.created_at}}</small></h3>
                </div>
                <div class="panel-body">
                    {{comment.body }}
                    {% if auth.isAdmin() %}
                    <a href="{{urlFor('comment.mute', { id_post: post.id, id_comment: comment.id, url: post.url }) }}" class="btn btn-default pull-right">Mute</a>
                    {% endif %}
                </div>
            </div>
            </div>
            
        </div>
        {% endif %}
        
        
    {% endfor %}
    
    


{% endif %}
<!-- Einde normale post -->

<!-- post Nieuw -->
{% if new %}
    <form method="post" action="{{urlFor('cms.new')}}" class="form-horizontal">
        <h1><i class="fa fa-lock"></i> Nieuwe Post</h1>
    </form>
{% endif %}

<!-- Einde nieuwe post -->

<!-- post Edit -->
{% if edit %}
    <form method="post" action="{{urlFor('cms.edit')}}" class="form-horizontal">
    <h1><i class="fa fa-lock"></i> Edit Post</h1>
    
    <div class="alert alert-info" role="alert">
    {{ macro.textfield('title', 'Titel', post.title) }}
    {{ macro.textfield('subtitle', 'Subtitel', post.subtitle) }}
    {{ macro.textfield('url', 'url', post.url) }}
    {{ macro.textfield('tags', 'tags', post.tags) }}
    {{ macro.textfield('audience', 'Publiek | groep', post.audience) }}
    {{ macro.textfield('image', 'Titel image', post.image) }}
    {{ macro.textfield('status', 'status (1 = concept, 2=live, 3=deleted)', post.status) }}
    </div>
    <div class="form-group">
        <input type="submit" value=" opslaan" class="btn btn-primary">
    </div>
    <div class="form-group">
        <textarea id="tinymce" class="mce" name="body" style="width:100%">{{post.getTextFromBody() }}</textarea>
    </div>
    
    <div class="alert alert-info" role="alert">
    {{ macro.textfield('footer', 'footer', post.footer) }}
    </div>
    <div class="form-group">
        <input type="submit" value=" opslaan" class="btn btn-primary">
    </div>
    <input type="hidden" name="id" value="{{ post.id }}">
    {{ macro.scrfField(csrf_key, csrf_token) }}
    </form>

{% endif %}
<!-- Einde post Edit -->

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
        {title: '{{ name }}', value: 'mydog.jpg'},
        {title: 'Cat', value: 'mycat.gif'}
    ]
});
</script>


{% endblock %}