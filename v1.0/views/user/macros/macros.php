{% macro scrfField(csrf_key, csrf_token) %}
    <input type="hidden" name="{{ csrf_key }}" value="{{ csrf_token }}">
{% endmacro %}

{% macro textfield(name, label, preset) %}
<div class="form-group">
    <label class="col-sm-2 control-label" for="{{name}}">{{label|capitalize}}</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="{{name}}" name="{{name}}" value="{{preset}}">
    </div>
</div>
{% endmacro %}

{% macro radiofield(name, label, preset) %}
<div class="form-group">
    <label class="col-sm-2 control-label" for="{{name}}">{{label|capitalize}}</label>
    <div class="col-sm-10">
        <fieldset>
            <input type="radio" name="gender" value="{{preset}}" />man<br />
            <input type="radio" name="gender" value="{{preset}}" />vrouw<br />
        </fieldset>
    </div>
</div>
{% endmacro %}
