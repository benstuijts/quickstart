<?php

require INC_ROOT . '/app/routes/home.php';
require INC_ROOT . '/app/routes/errors/404.php';

/* Login functionality */
require INC_ROOT . '/app/routes/auth/register.php';
require INC_ROOT . '/app/routes/auth/activate.php';
require INC_ROOT . '/app/routes/auth/login.php';
require INC_ROOT . '/app/routes/auth/logout.php';

require INC_ROOT . '/app/routes/auth/password/change.php';
require INC_ROOT . '/app/routes/auth/password/recover.php';
require INC_ROOT . '/app/routes/auth/password/reset.php';

require INC_ROOT . '/app/routes/auth/facebook/facebook.php';



/* User functionality */
require INC_ROOT . '/app/routes/user/profile.php';

/* CMS (Content Management System) */
require INC_ROOT . '/app/routes/cms/cms.php';

/* Posts (laatste route!) */
require INC_ROOT . '/app/routes/posts/posts.php';


/* Available routes | views:

route                   view                    name
/                       home.php                home
notFound                errors/404.php

    AUTH
------------------------------------------------------------------
/register               auth/register.php       register
/register (post)        auth/register.php       register.post
/activate               home                    activate
/login                  auth/login.php          login
/login (post)           auth/login.php          login.post
/logout                 home                    logout



    USER
------------------------------------------------------------------

/change-password        user/profile.php        password.change
/change-password (post) home                    password.change.post
/recover-password       auth/password/recover.php password.recover
/recover-password (post) home                   password.recover.post
/password-reset         auth/password/reset.php password.reset
/password-reset (post)  auth/password/reset.php password.reset.post

/user                   user/profile.php        user.profile
/users                  user/users.php          users
/user/profile/update    user/profile.php        user.profile.update
/user/profile/update (post) user/profile.php    user.profile.update.post

    POSTS
------------------------------------------------------------------  
/posts                  posts/multipleposts.php posts.all
/posts-views            posts/multipleposts.php posts.mostViews
/posts-populair         posts/multipleposts.php posts.mostPopular
/posts-new              posts/multipleposts.php posts.mostNew
/posts-search=:query    posts/searchresults.php posts.search

/:url                   posts/singlepost.php    
/post-like (post)       posts/singlepost.php    post.like
/post-edit (post)       posts/singlepost.php    post.edit
/post-delete (post)     posts/singlepost.php    post.delete
/post-mute (post)       posts/singlepost.php    post.mute (*)

    CMS
------------------------------------------------------------------ 
/cms                    cms/overview.php        cms
/cms/new                cms/new.php             cms.new
/cms/edit/:id           posts/singlepost.php    cms.edit
/cms/dashboard          cms/dashboard.php       cms.dashboard

*/
