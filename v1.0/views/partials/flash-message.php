<div class="flash-message" style="width: 320px; position: fixed; top: 64px; left: 15px">

{% if flash.global %}
    <div class="global alert alert-info">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        {{ flash.global }}
    </div>
{% endif %}
{% if flash.info %}
    <div class="alert alert-info">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <i class="fa fa-info-circle"></i> {{ flash.info }}
    </div>
{% endif %}
{% if flash.success %}
    <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <i class="fa fa-thumbs-o-up"></i> {{ flash.success }}
    </div>
{% endif %}
{% if flash.warning %}
    <div class="alert alert-warning">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <i class="fa fa-exclamation-triangle"></i> {{ flash.warning }}
    </div>
{% endif %}
{% if flash.danger %}
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <i class="fa fa-hand-paper-o"></i> {{ flash.danger }}
    </div>
{% endif %}    
    
</div>