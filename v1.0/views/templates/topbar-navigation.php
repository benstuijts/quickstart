<nav class="navbar navbar-default">
    {% if auth.isAdmin %}
    <div class="container-fluid bg-warning">
    {% else %}
    <div class="container-fluid">
    {% endif %}
    <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        {% if auth %}
        <a class="navbar-brand" href="{{ urlFor('user.profile', {username: auth.username}) }}">
            <img src="{{ auth.getAvatarUrl({size: 24, baseUrl:baseUrl}) }}" width="24" height="24" alt="{{ auth.getFirstNameOrUsername() }}" title="{{ auth.getFirstNameOrUsername() }}">
        </a>
        {% else %}
        <a class="navbar-brand" href="{{ urlFor('home') }}">Welkom</a>
        {% endif %}
    </div>
    
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
        {% if auth %}
            <li><a href="{{ urlFor('home') }}">Home</a></li>
            <li><a href="{{ urlFor('posts.all') }}">Artikelen</a></li>
            <li><a href="{{ urlFor('user.profile') }}/{{auth.username}}">Profiel</a></li>
            <li><a href="{{ urlFor('users') }}">Alle leden</a></li>
            <li><a href="{{ urlFor('logout') }}">Uitloggen</a></li>
            
            {% if auth.isAdmin %}
            
            {% if pendingComments > 0 %}
            <li role="presentation"><a href="{{ urlFor('cms.comments.pending')}}"><i class="fa fa-commenting-o"></i> <span class="badge">{{ pendingComments }}</span></a></li>
            {% endif %}
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-lock"></i> CMS <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="{{ urlFor('cms') }}">CMS overview</a></li>
            <li><a href="{{ urlFor('cms.new') }}">Nieuw artikel</a></li>
            <li><a href="{{ urlFor('cms.comments.pending')}}">Reacties <span class="badge">{{ pendingComments }}</span></a></li>
            
            <li role="separator" class="divider"></li>
            <li><a href="{{ urlFor('cms.image.upload')}}">Afbeeldingen uploaden</a></li>
            <li><a href="{{ urlFor('cms.images')}}" target="_blank">Afbeeldingen overzicht</a></li>
            
            <li role="separator" class="divider"></li>
            <li><a href="{{ urlFor('cms.dashboard')}}">Dashboard</a></li>
            
          </ul>
        </li>
            {% endif %}
            
        {% else %}
            <li><a href="{{ urlFor('home') }}">Home</a></li>
            <li><a href="{{ urlFor('posts.all') }}">Artikelen</a></li>
            <li><a href="{{ urlFor('register') }}">Registreren</a></li>
            <li class="active"><a href="{{ urlFor('login') }}">Log in</a></li>
            
        {% endif %}
        </ul>
        
        <form class="navbar-form navbar-right" method="get" action={{ urlFor('posts.search') }} role="search">
        <div class="form-group">
          <input type="text" class="form-control" placeholder="Zoeken" name="query">
        </div>
        <button type="submit" class="btn btn-default">ok</button>
      </form>
        
        
    </div>
    
    </div>
</nav>