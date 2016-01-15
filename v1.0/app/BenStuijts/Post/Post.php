<?php

namespace BenStuijts\Post;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Post extends Eloquent 
{
    protected $table = 'posts';
    
    protected $fillable = [
        'title',
        'author',
        'subtitle',
        'newsgroup',
        'audience',
        'image',
        'url',
        'body',
        'footer',
        'tags',
        'status',
        'likes',
        'views'
    ];
    
    public function getTitle($query='') {
        return $this->marktext($this->title, $query);
    }
    
    public function getSubtitle($query='') {
        return $this->marktext($this->subtitle, $query);
    }
    
    public function getArticle($url) {
        $article = $this->where('url', $url)->get()->first();
        if($article) {
            $article->increment('views');
            return $article;
        } else {
            return false;
        }
    }
    
    public function getArticleById($id) {
        $article = $this->where('id', $id)->get()->first();
        if($article) {
            $article->increment('views');
            return $article;
        } else {
            return false;
        }
    }
    
    public function getArticleUrlById($id) {
        $url = $this->where('id', $id)->get->first();
        if($url) {
            return $url;
        } else {
            return '/';
        }
    }
    
    public function getNextArticle() {
        $next = $this->where('status', 2)->where('id', '>', $this->id)->get()->min('id');
        $next = $this->getArticleById($next);
        
        
        if($next) {
            return $next;
        } else {
            return null;
        }
    }
    
    public function getPreviousArticle() {
        $previous = $this->where('status', 2)->where('id', '<', $this->id)->get()->max('id');
        $previous = $this->getArticleById($previous);
        if($previous) {
            return $previous;
        } else {
            return null;
        }
    }
    
    public function getPostsOfNewsGroup($newsgroup) {
        if($newsgroup) {
            return $this->where('status', 2)->where('newsgroup', $newsgroup)->get();
        } else { return false; }
    }
    
    public function getTagsPlainText() {
        return $this->tags;
    }
    
    public function getTags() {
        $tags = explode(',',$this->tags);
        return $tags;
    }
    
    public function getNumberOfLikes() {
        $likes = $this->likes;
        if($likes == '') {
            return false;
        }
        $likes = explode(',', $likes);
        return count($likes);
    }
    
    public function getNumberOfViews() {
        return $this->views;
    }
    
    public function renderBody() {
        $body = htmlspecialchars_decode($this->body);
        
        $blocks = [];        
        $blocks = require(INC_ROOT . '/views/blocks/config.php');
        
        var_dump($blocks);
        
        for($c=0; $c<count($blocks); $c++) {
            preg_match('/\[(.*?)\(/', $blocks[$c], $block);
            $pattern = '/\['.$block[1].'(.*?)\]/';
            
            preg_match($pattern, $body, $data);
            $preg1 = preg_match_all('/var (.*?)=/', $data[0], $key);
            $preg2 = preg_match_all('/=(.*?);/', $data[0], $value);
            $vars = array_combine($key[1], $value[1]);
            
            $template = file_get_contents(INC_ROOT . '/views/blocks/'.strtolower($block[1]).'.php');
            foreach($vars as $key => $value) {
            //    echo $key . ' => ' . $value . '<br>';
                $p = '/{'.$key.'}/';
            //    echo $p.'<br>';
                $template = preg_replace($p, $value, $template);
            }
            
            //var_dump($template);
            $body = preg_replace($pattern, $template, $body);
            //echo $body;
        }
        
        return $body;
  
    }

    public function getTextFromBody() {
        
        return htmlspecialchars_decode($this->body);
    }
    
    /*
    public function getSnippitFromBody($l) {
        return htmlspecialchars_decode(excerpt($this->body, 'a', $l));
    }
    */
    
    public function getTextSnippitFromBody($query = false) {
        if($query) {
            return htmlspecialchars_decode($this->marktext( excerpt($this->body, 'a'),$query  ) );
        } else {
            return htmlspecialchars_decode(excerpt($this->body, 'a'));
        }
    }
    
    public function getFirstParagraph($query = false) {
        
        $firstparagraph = getFirstPara($this->body);
        if($query) {
            return htmlspecialchars_decode($this->marktext( $firstparagraph,$query  ) );
        } else {
            return htmlspecialchars_decode( $firstparagraph );
        }
    }
    
    public function getWordCount() {
        return str_word_count($this->renderBody());
    }

    
    /* Search */
    
    public function searchFor($query) {
        return $this
            ->where('title', 'like', "%{$query}%")
            ->orWhere('subtitle', 'like', "%{$query}%")
            ->orWhere('body', 'like', "%{$query}%")
            ->orWhere('tags', 'like', "%{$query}%")
            ->get();
    }
    
    private function marktext($text, $words) {
        $wordsArray = array();
        $markedWords = array();
        $wordsArray = explode(' ', $words); 
        foreach ($wordsArray as $k => $word) {
          $markedWords[$k]='<mark class="text-primary">'.$word.'</mark>';
        }
        $text = str_ireplace($wordsArray, $markedWords, $text);
        return $text;
    }
    
    /* END Search */
    
    /* Status van de post wijzigen */
    
    private function changeStatus($status) {
        return $this->update(['status' => $status]);
    }
    
    public function mute() {
        return $this->changeStatus(1);
    }
    
    public function publish() {
        return $this->changeStatus(2);
    }
    
    public function delete() {
        return $this->changeStatus(3);
    }
    
    /* END Status van de post wijzigen */
    
    /* LIKE function */
    
    public function isLikedByMe($id) {
        $likes = $this->likes;

        if($likes === null || $likes === '') {
            return false;
        }
        if(in_array($id, explode(',',$likes))) {
            return true;
        }
        return false;
    }
    
    public function likePost($art_id, $id) {
        $article = $this->where('id', $art_id)->get()->first();
        $likes = $article->likes;
        
        if($likes === null || $likes === '' ) {
            $article->update([
                'likes' => $id
            ]);
            return;
        } else {
            $likes = explode(',',$likes);
            if(in_array($id, $likes)) {
                return;
            } else {
                
                array_push($likes, $id);
                $article->update([
                    'likes' => implode(',', $likes)
                ]);
            }
        }
    }
    
    public function unlikePost($art_id, $id) {
        $article = $this->where('id', $art_id)->get()->first();
        $likes = $article->likes;
        
        $likes = explode(',',$likes);
        if(in_array($id, $likes)) {
            $key = array_search($id, $likes);
            unset($likes[$key]);
            $article->update([
                'likes' => implode(',', $likes)
            ]);
        }
        return;
    }
    
    
    
    
    /* END LIKE function */
    
    /* Comments */
    
    public function getCommentsOfPost($id) {
        return $this->find($id)->hasMany('BenStuijts\Post\Comment')->where('status', 1)->get();
    }
    
    public function getPendingCommentsOfPost($id) {
        return $this->find($id)->hasMany('BenStuijts\Post\Comment')->where('status', 1)->get();
    }
    
    public function getAllCommentsOfPost($id) {
        return $this->find($id)->hasMany('BenStuijts\Post\Comment')->orderBy('created_at', 'desc')->get();
    }
    
    public function getNumberofCommentsOfPost($id) {
        return $this->find($id)->hasMany('BenStuijts\Post\Comment')->where('status', 1)->get()->count();
    }
    
    public function getSingleComment($id_post, $id_comment) {
        return $this->find($id_post)
             ->hasMany('BenStuijts\Post\Comment')
             ->where('id', $id_comment);
    }
    
    public function muteComment($id_post, $id_comment) {
         return (
            $this->getSingleComment($id_post, $id_comment)
                 ->update([ 'status' => 0 ])
            );
    }
    public function acceptComment($id_post, $id_comment) {
         return (
            $this->getSingleComment($id_post, $id_comment)
                 ->update([ 'status' => 1 ])
            );
    }
    public function deleteComment($id_post, $id_comment) {
         return (
            $this->getSingleComment($id_post, $id_comment)
                 ->delete()
            );
    }
    public function restoreComment($id_post, $id_comment) {
         return (
            $this->getSingleComment($id_post, $id_comment)
                 ->update([ 'status' => 0 ])
            );
    }
    /* end Comments */
    
}


function excerpt($text, $phrase, $radius = 800, $ending = "...") {
    $phraseLen = strlen($phrase);
    if ($radius < $phraseLen) {
        $radius = $phraseLen;
    }

    $pos = strpos(strtolower($text), strtolower($phrase));

    $startPos = 0;
    if ($pos > $radius) {
        $startPos = $pos - $radius;
    }

    $textLen = strlen($text);

    $endPos = $pos + $phraseLen + $radius;
    $endPos = $pos + $radius;
    if ($endPos >= $textLen) {
        $endPos = $textLen;
    }

    $excerpt = substr($text, $startPos, $endPos - $startPos);
    if ($startPos != 0) {
        $excerpt = substr_replace($excerpt, $ending, 0, $phraseLen);
    }

    if ($endPos != $textLen) {
        $excerpt = substr_replace($excerpt, $ending, -$phraseLen);
    }

    return $excerpt;
}

function getFirstPara($string){
            $string = htmlspecialchars_decode($string);
            $string = substr($string,0, strpos($string, "</p>")+4);
            
            return $string;
        }

?>