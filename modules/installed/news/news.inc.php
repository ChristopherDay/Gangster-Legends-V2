<?php

    class news extends module {
        
        public $allowedMethods = array();
        
        public $pageName = 'Welcome back';
        
        public function constructModule() {
            
            $news = $this->db->prepare("
                SELECT * FROM gameNews INNER JOIN users ON (GN_author = U_id) ORDER BY GN_date DESC LIMIT 0, 10
            ");
            $news->execute();
            $articleInfo = array();
            while ($newsArticle = $news->fetch(PDO::FETCH_ASSOC)) {
                
                $author = new user($newsArticle['GN_author']);
                
                $articleInfo[] = array(
                    "title" => $newsArticle['GN_title'],
                    "authorID" => $newsArticle['U_id'],
                    "user" => $author->user,
                    "date" => $this->date($newsArticle['GN_date']),
                    "text" => $newsArticle['GN_text']
                );
                
            }
            
            $this->html .= $this->page->buildElement('newsArticle', array(
                "news" => $articleInfo
            ));
        
        }
        
    }

?>