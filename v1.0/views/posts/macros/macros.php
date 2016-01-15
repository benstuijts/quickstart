{% macro metadata(post, auth) %}
<p class="meta-data">
    <div class="pull-right">
        <i class="fa fa-heart"></i>
        {{post.getNumberOfLikes()}}
    
        <i class="fa fa-eye"></i>
        {{post.getNumberOfViews()}}
    </div>
            
    <span class="text-primary">
        <i class="fa fa-user"></i>
        <a href="{{urlFor('user.profile', {username: auth.getUsernameFromId(post.author) })}}">
            {{auth.getNameFromId(post.author)}} 
        </a>
    </span>
    <span class="text-muted">
        <i class="fa fa-calendar"></i>
        {{post.created_at}}   
    </span>
</p>
{% endmacro %}

{% macro scrfField(csrf_key, csrf_token) %}
    <input type="hidden" name="{{ csrf_key }}" value="{{ csrf_token }}">
{% endmacro %}

{% macro postnav() %}
<form class="navbar-form" role="search" method="get" action="{{urlFor('posts.search')}}">
        <a href="{{urlFor('posts.all')}}" class="btn btn-info">Overzicht van alle artikelen.</a>
        <a href="{{urlFor('posts.mostPopular')}}" class="btn btn-warning">Bekijk de populairste artikelen.</a>
        <a href="{{urlFor('posts.mostViews')}}" class="btn btn-success">Bekijk de meest bekeken artikelen.</a>
        <div class="input-group">
            <input type="search" class="form-control" name="query">
            <div class="input-group-btn">
                <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
            </div>
        </div>
        <input type="hidden" name="{{ csrf_key }}" value="{{ csrf_token }}">
    </form>
    <hr>
{% endmacro %}
{% macro textfield(name, label, preset) %}
<div class="form-group">
    <label class="col-sm-2 control-label" for="{{name}}">{{label|capitalize}}</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="{{name}}" name="{{name}}" value="{{preset}}">
    </div>
</div>
{% endmacro %}
{% macro socialbuttons(baseUrl,post, auth) %}
<p>
{% if auth %}
<!--
<a class="btn btn-default" href="{{urlFor('post.like')}}?id={{auth.id}}&art={{post.id}}$url={{baseUrl}}/{{post.url}}" title="leuk artikel!">
    
    <span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span>
    
</a>
-->
<a class="btn btn-default" href="{{urlFor('post.like')}}?id={{auth.id}}&art={{post.id}}$url={{baseUrl}}/{{post.url}}" title="opslaan in mijn favorieten" >
    <span class="glyphicon glyphicon-heart" aria-hidden="true"></span>
    <span class="badge">{{ post.getNumberOfLikes()}}</span>
</a>
<a class="btn btn-default" href="{{post.url}}" title="aantal reacties" >
    <i class="fa fa-comments"></i>
    <span class="badge">{{ post.getNumberofCommentsOfPost(post.id)}}</span>
</a>
{% endif %}
<a class="btn btn-default" 
    href="mailto:
          ?subject={{post.title}}?
          
          &body=Ik kwam dit interessante artikel tegen op {{baseUrl}}/{{post.url}}%0A%0A{{post.title}}%0A{{post.subtitle}}%0A{{post.getTextSnippitFromBody()|striptags}}%0A%0A%0ALees verder op {{ baseUrl }}!
          " title="mail dit artikel naar iemand">
    <span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>
</a>
<a class="btn btn-default" 
    href="http://www.printfriendly.com/print?url={{baseUrl}}/{{post.url}}" target="_blank" title="print dit artikel">
    <i class="fa fa-print"></i>
</a>

<a class="btn btn-primary" href="http://www.facebook.com/sharer.php?u={{ baseUrl }}/{{post.url}}" title="delen op facebook" target="_blank">
    <i class="fa fa-facebook fa-fw"></i>
</a>
<a class="btn btn-info" href="http://twitter.com/share?url={{ baseUrl }}/{{post.url}}&text=Interessant: {{post.title}}" title="delen op twitter">
    <i class="fa fa-twitter fa-fw"></i>
</a>
<a class="btn btn-danger" href="https://plus.google.com/share?url={{ baseUrl }}/{{post.url}}" title="delen op google plus" target="_blank">
    <i class="fa fa-google fa-fw"></i>
</a>
<a class="btn btn-primary" href="https://www.linkedin.com/shareArticle?mini=true&url={{ baseUrl }}/{{post.url}}" title="delen op linkedin" target="_blank">
    <i class="fa fa-linkedin fa-fw"></i>
</a>


</p>
{% endmacro %}
{% macro tags(post) %}
    <p>
        <span class="glyphicon glyphicon-tags" aria-hidden="true"></span> 
    {% for tag in post.getTags() %}
        <a href="searcharticle={{tag}}">
            {{ tag }}
        </a>
    {% endfor %}
    </p>
{% endmacro %}

