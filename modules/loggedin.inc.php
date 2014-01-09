<?php

    class loggedin extends module {
        
        public $allowedMethods = array();
        
        public function constructModule() {
            
			echo $b;
			
            $news = $this->db->prepare("SELECT * FROM gameNews ORDER BY GN_date DESC LIMIT 0, 5");
            $news->execute();
            
            while ($newsArticle = $news->fetch(PDO::FETCH_ASSOC)) {
                
                $author = new user($newsArticle['GN_author']);
                
                $articleInfo = array(
                    $newsArticle['GN_title'],
                    $author->name,
                    $this->date($newsArticle['GN_date']),
                    $newsArticle['GN_text']
                );
                
                $this->html .= $this->page->buildElement('newsArticle', $articleInfo);
                
            }
		
        }
        
    }

?>