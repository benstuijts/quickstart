<?php



$app->get('/posts', function() use ($app) {
    
    $request = $app->request;
    $pagina = ($request->get('pagina')) ? $request->get('pagina') : 1;
    $number = ($request->get('number')) ? $request->get('number') : 12;
    
    $posts_per_page = $number;
    $posts = $app->post->where('status', 2)->orderBy('created_at', 'DESC')->get();
    $number_of_posts = count($posts);

    $counter = 0;
    $page = [];
    $pageNumber = 0;
    $page[$pageNumber] = [];
    foreach($posts as $post) {

        array_push($page[$pageNumber], $post);
        
        $t++; $counter++;
        
        if($counter == $posts_per_page && $t<$number_of_posts) {
            array_push($page,[]);
            $pageNumber++;
            $counter=0;
        }
        

    }
    if($pagina>count($page)) $pagina = count($page);
    if($pagina<1) $pagina = 1;
    
    
    $app->render('posts/multipleposts.php', [
        'posts' => $page,
        'numberOfPosts' => $number_of_posts,
        'numberOfPages' => count($page),
        'currentPage' => $pagina,
        'postsPerPage' => $posts_per_page
    ]);
})->name('posts.all');



$app->get('/editpost:post_id', $admin(), function($post_id) use ($app){
    $app->render('posts/editpost.php', [
        'post' => $app->post->where('id', $post_id)->get()->first()
    ]);
})->name('editpost');



$app->get('posts-views', function() use ($app){
    $app->render('posts/multipleposts.php', [
        'posts' => $app->post->where('status', 2)->orderBy('views', 'DESC')->take(5)->get()
    ]);
})->name('posts.mostViews');

$app->get('/posts-populair', function() use ($app){
    $app->render('posts/multipleposts.php', [
        'posts' => $app->post->where('status', 2)->where('likes', '!=', '')->orderBy('likes', 'ASC')->take(5)->get()
    ]);
})->name('posts.mostPopular');

$app->get('/posts-new', function() use ($app){
    $app->render('posts/multipleposts.php', [
        /* aanvullen */
    ]);
})->name('posts.mostNew');

// BETER: /posts-search en dan $request = $app->request; $query = $request->get('query');
$app->get('/search', function() use($app){
    
    $pagina = 1; $number = 10;
    
    $query = $app->request->get('query');
    $posts = $app->post->searchFor($query);
    $number_of_posts = count($posts);
    
    $counter = 0;
    $page = [];
    $pageNumber = 0;
    $page[$pageNumber] = [];
    foreach($posts as $post) {

        array_push($page[$pageNumber], $post);
        
        $t++; $counter++;
        
        if($counter == $posts_per_page && $t<$number_of_posts) {
            array_push($page,[]);
            $pageNumber++;
            $counter=0;
        }
        

    }
    if($pagina>count($page)) $pagina = count($page);
    if($pagina<1) $pagina = 1;
    
    $app->render('posts/searchresults.php', [
        'title' => 'zoekresultaten van ' . $query,
        'description' => 'Je hebt gezocht op ' . $query . ' en er zijn ' .$number_of_posts . ' relevante artikelen gevonden.' ,
        'keywords' => 'hockey artikelen zoeken, hockey artikelen vinden, zoeken, vinden',
        'author' => $app->config->get('app.author'),
        'query' => $query,
        'posts' => $page,
        'numberOfPosts' => $number_of_posts,
        'numberOfPages' => count($page),
        'currentPage' => $pagina,
        'postsPerPage' => $posts_per_page
    ]);
    
    //$app->response->redirect($app->urlFor('searchresults'));
    
})->name('posts.search');


/* Like a post */

$app->get('/post-like', $authenticated(), function() use ($app){
    
    $request = $app->request;
    $id = $request->get('id');
    $art = $request->get('art');
    $app->post->likePost($art,$id);
    
    $app->flash('success', 'Bedankt dat je het artikel leuk vindt!');
    
    $app->response->redirect($app->urlFor('user.profile'));
    
})->name('post.like');

$app->get('/post-unlike', $authenticated, function() use ($app){
    
    $request = $app->request;
    $id = $request->get('id');
    $art = $request->get('art');
    $url = $request->get('url');
    $app->post->unlikePost($art,$id);
    
    $app->flash('success', 'Artikel uit favoriete lijst verwijderd.');
    $app->response->redirect($app->urlFor('user.profile'));
    
})->name('post.unlike');

/* END like a post */

/* Comments */

$app->get('/comment-mute(/:id_post)(/:id_comment)(/:url)', $admin(), function($id_post,$id_comment, $url) use ($app) {
    if($url == 'cms' || !$url) {
        $url = 'cms/comments/pending';
    }
    if($app->post->muteComment($id_post, $id_comment)) {
        $app->flash('success', 'Comment muted.');
    } else {
        $app->flash('warning', 'Comment NOT muted.');
    };
    $app->response->redirect($app->config->get('app.url'). '/' . $url);
})->name('comment.mute');

$app->get('/comment-delete(/:id_post)(/:id_comment)(/:url)', $authenticated(), function($id_post,$id_comment, $url) use ($app) {
    
    
    if($url == 'cms' || !$url) {
        $url = 'cms/comments/pending';
    }
    
    
    
    if($app->post->deleteComment($id_post, $id_comment)) {
        $app->flash('success', 'De reactie is verwijderd.');
    } else {
        $app->flash('warning', 'Reactie niet verwijderd.');
    };
    $app->response->redirect($app->config->get('app.url'). '/' . $url);
})->name('comment.delete');

$app->get('/comment-accept(/:id_post)(/:id_comment)(/:url)', $admin(), function($id_post,$id_comment, $url) use ($app) {
    if($url == 'cms' || !$url) {
        $url = 'cms/comments/pending';
    }
    if($app->post->acceptComment($id_post, $id_comment)) {
        $app->flash('success', 'Comment accepted.');
    } else {
        $app->flash('warning', 'Comment NOT accepted.');
    };
    $app->response->redirect($app->config->get('app.url'). '/' . $url);
})->name('comment.accept');

$app->get('/comment-restore(/:id_post)(/:id_comment)(/:url)', $admin(), function($id_post,$id_comment, $url) use ($app) {
    if($url == 'cms' || !$url) {
        $url = 'cms/comments/pending';
    }
    if($app->post->restoreComment($id_post, $id_comment)) {
        $app->flash('success', 'Comment restored.');
    } else {
        $app->flash('warning', 'Comment NOT restored.');
    };
    $app->response->redirect($app->config->get('app.url'). '/' . $url);
})->name('comment.restore');

$app->post('/comment-new', function() use ($app){
    $request = $app->request;
    
    $user_id = $request->post('user_id');
    $post_id = $request->post('post_id');
    $body = $request->post('body');
    $url = $request->post('url');
    
    $app->comment->test();
    
    
    
    if($user_id == 'guest') {
        if($app->comment->create([
        'post_id' => $post_id,    
        'user_id' => 0,
        'body' => protect($body),
        'status' => 0
        ])){
            $app->flash('success', 'Reactie ontvangen. De reactie zal eerst beoordeeld worden door de administrator voordat de reactie geplaatst wordt.');
        } else {
            $app->flash('warning', 'Er is iets mis gegaan...');
        };
    } else {
        if($app->comment->create([
        'post_id' => $post_id,    
        'user_id' => $user_id,
        'body' => protect($body),
        'status' => 1
        ])){
            $app->flash('success', 'Bedankt voor jouw reactie!');
        } else {
            $app->flash('warning', 'Er is iets mis gegaan...');
        };
    }
    $app->response->redirect($app->config->get('app.url'). '/' . $url);
    
})->name('comment.new');


/* end Comments */


$app->get('/:url', function($url) use ($app){
    
    $article = $app->post->where('url', $url)->where('status',2)->get()->first();
    
    if(!$article) {
        $app->notFound();
    }
    
    $request = $app->request;

    if(!$request->get('edit') || !$app->auth) {
        $app->render('posts/singlepost.php', [
        'title' => $article->getTitle(),
        'description' => $article->getFirstParagraph() ,
        'keywords' => $article->tags,
        'author' => $app->user->getNameFromId($article->author),
        'post' => $article,
        'edit' => false,
        'comments' => $app->post->getAllCommentsOfPost($article->id),
        'nextArticle' => $article->getNextArticle(),
        'previousArticle' => $article->getPreviousArticle()
        ]);
    } elseif ($request->get('edit') == 1) {
        
        if(!$app->auth || !$app->auth->isAdmin()) {
            $article = $app->post->where('url', $url)->get()->first();
            $app->render('posts/singlepost.php', [
                'title' => $article->getTitle(),
                'description' => $article->getFirstParagraph() ,
                'keywords' => $article->tags,
                'author' => $app->auth->getNameFromId($article->author),
                'post' => $article,
                'edit' => $app->auth->isAdmin(),
                'comments' => $app->post->getAllCommentsOfPost($article->id),
                'nextArticle' => $article->getNextArticle(),
                'previousArticle' => $article->getPreviousArticle()
            ]);
        } else {
            $app->render('posts/singlepost.php', [
                'title' => $article->getTitle(),
                'description' => $article->getFirstParagraph() ,
                'keywords' => $article->tags,
                'author' => $app->auth->getNameFromId($article->author),
                'post' => $article,
                'edit' => $app->auth->isAdmin(),
                'comments' => $app->post->getAllCommentsOfPost($article->id),
                'nextArticle' => $article->getNextArticle(),
                'previousArticle' => $article->getPreviousArticle()
            ]);
        }
        
    }
    
});

$app->post('/searcharticle', function() use ($app){
    $request = $app->request;
    $query = $request->post('searcharticle');
    
    echo 'SEARCHING FOR ARTICLES: ' . $query;
    
    $posts = $app->post->searchFor($query);
    
    echo '...found: ...' . count($posts);
    
    $app->render('posts/searchresults.php', [
        'query' => $query,
        'posts' => $posts
    ]);
    
    
    
})->name('searcharticle');

$app->post('/post-edit', $admin(), function() use ($app){
    $request = $app->request;
    
    $id = $request->post('id');
    $body = $request->post('body');
    
    $post = $app->post->where('id', $id)->get()->first();
    if($post->update([
        'title' => $request->post('title'),
        'subtitle' => $request->post('subtitle'),
        'url' => $request->post('url'),
        'image' => $request->post('image'),
        'tags' => $request->post('tags'),
        'status' => $request->post('status'),
        'body' => htmlspecialchars($body)
    ])) {
        $app->flash('success', 'Artikel is succesvol opgeslagen');  
    } else {
        $app->flash('danger', 'Artikel is NIET opgeslagen');    
    }
    
    $app->response->redirect($app->urlFor('home'));
    
    
})->name('post.edit');

$app->post('/post-delete', $admin(), function() use ($app){
    /* aanvullen */
})->name('post.delete');

$app->post('/post-mute', $admin(), function() use ($app){
    /* aanvullen */
})->name('post.mute');





function protect($string) {
    return (trim(strip_tags(addslashes($string))));
}

?>