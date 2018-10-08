<?php

    class news extends module {
        
        public $allowedMethods = array();
		
		public $pageName = 'Welcome back';
        
        public function constructModule() {

            
            $usersOnline = $this->db->prepare("
                SELECT COUNT(*) as 'count' FROM users
            ");
            $usersOnline->execute();
            $users = $this->db->prepare("
                SELECT COUNT(*) as 'count' FROM userTimers WHERE UT_desc = 'laston' AND UT_time > ".(time()-900)."
            ");
            $users->execute();

            $this->page->addToTemplate("usersOnline", number_format($usersOnline->fetch(PDO::FETCH_ASSOC)["count"]));
            $this->page->addToTemplate("users", number_format($users->fetch(PDO::FETCH_ASSOC)["count"]));
			
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