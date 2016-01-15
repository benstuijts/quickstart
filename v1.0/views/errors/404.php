{% extends 'templates/default.php' %}

{% block title %}404 error{% endblock %}
{% block content %}
<div class="row">
    <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1 col-sm-12">
        <div class="alert alert-warning" data-alert='alert'>
            <h3><i class="fa fa-exclamation-triangle fa-3x"></i> Pagina niet gevonden. </h3>
            <a href="{{ urlFor('home') }}" class="btn btn-primary btn-lg">Klik hier om naar <b>{{ baseUrl }}</b> te gaan.</a>
        </div>
    </div>
</div>
{% endblock %}