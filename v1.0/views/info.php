{% extends 'templates/default.php' %}
{% block title %}Home{% endblock %}

{% block content %}



<div class="container">
    
    <a class="btn btn-default btn-lg" href="https://hockeytips-stuijts.c9.io/phpmyadmin/" target="_blank">PHP My Admin</a>
    
    <h1>Website Template with php Slim engine | infopage</h1>
    
    <div class="row">
        <div class="col-sm-12 col-md-6">
            <h2>Dependencies</h2>
            <ul>
                <li>"slim/slim": "~2.0",</li>
                <li>"slim/views": "0.1.*",</li>
                <li>"twig/twig": "~1.0",</li>
                <li>"hassankhan/config": "0.8.*",</li>
                <li>"illuminate/database": "~5.0",</li>
                <li>"alexgarrett/violin": "2.*",</li>
                <li>"ircmaxell/random-lib": "~1.1",</li>
                <li>"mailgun/mailgun-php": "^1.8",</li>
                <li>"facebook/php-sdk": "dev-master"</li>
            </ul>    
        </div>
        <div class="col-sm-12 col-md-6">
            <h2>Werkend</h2>
            <ul>
                <li>User kan zich registreren</li>
                <li>User krijgt activatie email</li>
                <li>User kan account activeren</li>
                <li>Flash Message (plus sluiten)</li>
                <li>Content Management System (CMS)</li>
                <li>Update your own Avatar as user</li>
                <li>Update user account gegevens</li>
                <li>Artikelen zoeken</li>
                <li>Post add favourite as user</li>
                <li>Share post by email</li>
                <li>Share post by facebook</li>
                <li>Share post by twitter</li>
                <li>Share post by google plus</li>
                <li>Share post by linkedin</li>
                <li>Reactie plaatsen bij een artikel als gast</li>
                <li>Reactie plaatsen bij een artikel als user</li>
                <li>User kan eigen reactie verwijderen</li>
                <li>Administrator kan reactie muten, accepteren</li>
                
            </ul>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-12 col-md-6">
            <h2>To do</h2>
            <ul>
                <li>CMS: Images uploaden en opslaan in database, plus opnemen in de imagelist van TinyMCE</li>
                <li>User routes toevoegen</li>
                
                
                <li>Login with facebook account</li>
                
                <li>Friends system</li>
                
                
            </ul> 
        </div>
        <div class="col-sm-12 col-md-6">
            <h2>Ideeën</h2>
            <ul>
                <li>Customer Relations System (CRS - module)</li>
                <li>Facturen aanmaken (Billing module)</li>
                <li>Forum</li>
                <li>Breadcrumbs</li>
            </ul>
        </div>
    </div>
    
    
    
    
    
    
    
    <h2>Routes</h2>
    <table class="table">
        <tr>
            <th>route</th><th>view</th><th>name</th>
        </tr>
        <tr class="info"><td>Basic</td></tr>
        <tr>
            <td>/</td><td>home.php</td><td>home</td>
        </tr>
        <tr>
            <td>notFound</td><td>errors/404.php</td><td>-</td>
        </tr>
        <tr class="danger"><td>Authenticate</td></tr>
        <tr><td>/register</td><td>auth/register.php</td><td>register</td></tr>
        <tr><td>/register (post)</td><td>auth/register.php</td><td>register.post</td></tr>
        <tr><td>/activate</td><td>home</td><td>activate</td></tr>
        <tr><td>/login</td><td>auth/login.php</td><td>login</td></tr>
        <tr><td>/login (post)</td><td>auth/login.php</td><td>login.post</td></tr>
        <tr><td>/logout </td><td>home</td><td>logout</td></tr>
        
        <tr class="info"><td>User</td></tr>
        <tr><td>/change-password</td><td>user/profile.php</td><td>password.change</td></tr>
        <tr><td>/change-password (post)</td><td>home</td><td>password.change.post</td></tr>
        <tr><td>/recover-password</td><td>auth/password/recover.php</td><td>password.recover</td></tr>
        <tr><td>/recover-password (post)</td><td>home</td><td>password.recover.post</td></tr>
        <tr><td>/password-reset</td><td>auth/password/reset.php</td><td>password.reset</td></tr>
        <tr><td>/password-reset (post)</td><td>auth/password/reset.php</td><td>password.reset.post</td></tr>
        <tr><td>/user</td><td>user/profile.php</td><td>user.profile</td></tr>
        <tr><td>/users</td><td>user/users.php</td><td>users</td></tr>
        <tr><td>/user/profile/update</td><td>user/profile.php</td><td>user.profile.update</td></tr>
        <tr><td>/user/profile/update (post)</td><td>user/profile.php</td><td>user.profile.update.post</td></tr>
        <tr><td>/user/avatar/upload (post)</td><td>user/profile.php</td><td>user.avatar.upload</td></tr>
                                   
            
     
        
        <tr class="success"><td>/posts</td></tr>
        <tr><td>Posts/:page/:posts_per_page</td><td>posts/multipleposts.php</td><td>posts.all</td></tr>
        <tr><td>/posts-views</td><td>posts/multipleposts.php</td><td>posts.mostViews</td></tr>
        <tr><td>/posts-populair</td><td>posts/multipleposts.php</td><td>posts.mostPopular</td></tr>
        <tr><td>/posts-new</td><td>posts/multipleposts.php</td><td>posts.mostNew</td></tr>
        <tr><td>/posts-search=:query</td><td>posts/searchresults.php</td><td>posts.search</td></tr>
        <tr><td>/:url</td><td>posts/singlepost.php</td></tr>
        <tr><td>/post-like </td><td>posts/singlepost.php</td><td>post.like</td></tr>
        <tr><td>/post-unlike </td><td>posts/singlepost.php</td><td>post.unlike</td></tr>
        <tr><td>/post-edit (post)</td><td>posts/singlepost.php</td><td>post.edit</td></tr>
        <tr><td>/post-delete (post)</td><td>posts/singlepost.php</td><td>post.delete (*)</td></tr>
        <tr><td>/post-mute (post)</td><td>posts/singlepost.php</td><td>post.mute (*)</td></tr>
        
        <tr class="success"><td>/comments</td></tr>
        <tr><td>/comment-new (post)</td><td>posts/singlepost.php</td><td>comment.new</td></tr>
        <tr><td>/comment-accept(/:id_post)(/:id_comment)(/:url)</td><td>posts/singlepost.php</td><td>comment.accept</td></tr>
        <tr><td>/comment-mute(/:id_post)(/:id_comment)(/:url)</td><td>posts/singlepost.php</td><td>comment.mute</td></tr>
        <tr><td>/comment-delete(/:id_post)(/:id_comment)(/:url)</td><td>posts/singlepost.php</td><td>comment.delete</td></tr>
        <tr><td>/comment-restore(/:id_post)(/:id_comment)(/:url)</td><td>posts/singlepost.php</td><td>comment.restore</td></tr>
        
        <tr class="info"><td><i class="fa fa-lock"></i> CMS</td></tr>
        <tr><td>/cms</td><td>cms/overview.php</td><td>cms</td></tr>
        <tr><td>/cms/edit (post)</td><td>cms/overview.php</td><td>cms.edit</td></tr>
        <tr><td>/cms/new</td><td>cms/new.php</td><td>cms.new</td></tr>
        <tr><td>/cms/new (post)</td><td>cms/overview.php</td><td>cms.new.post</td></tr>
        <tr><td>/cms/publish(/:id)</td><td>cms/overview.php</td><td>cms.publish</td></tr>
        <tr><td>/cms/mute(/:id)</td><td>cms/overview.php</td><td>cms.mute</td></tr>
        <tr><td>/cms/delete(/:id)</td><td>cms/overview.php</td><td>cms.delete</td></tr>
    </table>
                          
               
                                   
                     
                      
             
          
               
     

                       
           
           
         
                             
        
                    
          
                          
                     
                                    
</div>
    

    <!-- CONTENT -->

{% endblock %}
