<?php

    class forum extends module {
        
        public $allowedMethods = array(
        	"id" => array( "type" => "GET" ),
        	"subject" => array( "type" => "POST" ),
        	"body" => array( "type" => "POST" ),
        	"submit" => array( "type" => "POST" ), 
            "quote" => array("type" => "GET" )
        );
		
		public $pageName = '';
        
        public function constructModule() {}

        public function error($text) {
        	$this->html .= $this->page->buildElement("error", array(
    			"text" => $text
    		));
        }

        public function getPost($id) {
            $post = $this->db->prepare("
                SELECT
                    P_id as 'id', 
                    P_date as 'time', 
                    P_user as 'user', 
                    P_topic as 'topic', 
                    P_body as 'body'
                FROM posts WHERE P_id = :id
            ");
            $post->bindParam(":id", $id);
            $post->execute();
            $post = $post->fetch(PDO::FETCH_ASSOC);

            $user = new User($post["user"]);
            $post["user"] = $user->user;
            $post["date"] = $this->date($post["time"]);
            return $post;
        }

        public function getTopic($id) {
            $topic = $this->db->prepare("
                SELECT * FROM topics WHERE T_id = :id
            ");
            $topic->bindParam(":id", $id);
            $topic->execute();
            $topic = $topic->fetch(PDO::FETCH_ASSOC);

            $posts = $this->db->prepare("
                SELECT 
                    P_id as 'id', 
                    P_date as 'time', 
                    P_user as 'user', 
                    P_topic as 'topic', 
                    P_body as 'body'
                FROM posts
                WHERE
                    P_topic = :topic
                ORDER BY P_date ASC
            ");

            $posts->bindParam(":topic", $id);
            $posts->execute();
            $posts = $posts->fetchAll(PDO::FETCH_ASSOC);

            $userIsAdmin = $this->user->hasAdminAccessTo("forum");

            $i = 0;

            foreach ($posts as $key => $post) {
                $post["firstPost"] = $i == 0;
                $post["canEdit"] = (
                    $post["user"] == $this->user->id || 
                    $userIsAdmin
                );
                $post["isAdmin"] = $userIsAdmin;
                $user = new User($post["user"]);
                $post["user"] = $user->user;
                $post["date"] = $this->date($post["time"]);
                $posts[$key] = $post;
                $i++;
            }

            $output = array(
                "forum" => $this->getForum($topic["T_forum"]),
                "subject" => $topic["T_subject"], 
                "topic" => $topic["T_id"], 
                "posts" => $posts
            );

            return $output;

        }

        public function getForum($id) {

        	$forum = $this->db->prepare("SELECT F_id as 'id', F_name as 'name' FROM forums WHERE F_id = :id");
        	$forum->bindParam(":id", $id);
        	$forum->execute();
        	$forum = $forum->fetch(PDO::FETCH_ASSOC);

	        if ($forum["id"]) {
	        	return $forum;
	        }

	        return false;

        }

        public function getTopics($forum) {
            $topics = $this->db->prepare("
                SELECT 
                    T_id as 'id', 
                    T_date as 'time', 
                    T_user as 'user', 
                    T_subject as 'subject'
                FROM topics
                WHERE
                    T_forum = :forum
                ORDER BY T_date DESC
            ");

            $topics->bindParam(":forum", $forum);
            $topics->execute();
            $topics = $topics->fetchAll(PDO::FETCH_ASSOC);

            foreach ($topics as $key => $topic) {
                $user = new User($topic["user"]);
                $topic["user"] = $user->user;
                $topic["date"] = $this->date($topic["time"]);
                $topics[$key] = $topic;
            }

            return $topics; 
        }

        public function newTopic($forum, $user, $subject, $body) {
        	$insert = $this->db->prepare("
        		INSERT INTO topics (
        			T_date, 
        			T_user, 
        			T_subject, 
        			T_forum
        		) VALUES (
	        		UNIX_TIMESTAMP(), 
	        		:user, 
	        		:subject, 
	        		:forum
        		);
        	");
        	$insert->bindParam(":user", $user);
        	$insert->bindParam(":subject", $subject);
        	$insert->bindParam(":forum", $forum);
        	$insert->execute();

        	$topic = $this->db->lastInsertId();

        	$this->newPost($topic, $user, $body);

        	return $topic;

        }

        public function newPost($topic, $user, $body) {
        	$insert = $this->db->prepare("
        		INSERT INTO posts (
        			P_date, 
        			P_user, 
        			P_body, 
        			P_topic
        		) VALUES (
	        		UNIX_TIMESTAMP(), 
	        		:user, 
	        		:body, 
	        		:topic
        		);
        	");
        	$insert->bindParam(":user", $user);
        	$insert->bindParam(":body", $body);
        	$insert->bindParam(":topic", $topic);
        	$insert->execute();

        	return $this->db->lastInsertId();
        	
        }

        public function method_forum() {
        	$forum = $this->getForum($this->methodData->id);

        	if (!$forum) {
        		return $this->error("This forum does not exist!");
        	}

        	$this->html .= $this->page->buildElement("topics", array(
        		"topics" => $this->getTopics($this->methodData->id), 
        		"forum" => $this->methodData->id
        	));
        }

        public function method_new() {
        	$forum = $this->getForum($this->methodData->id);

        	$params = array(
        		"forum" => $this->methodData->id
        	);

        	if (!$forum) {
        		return $this->error("This forum does not exist!");
        	}

        	if (isset($this->methodData->submit)) {

        		$params["subject"] = $this->methodData->subject;
        		$params["body"] = $this->methodData->body;

        		$error = false;
        		if (strlen($this->methodData->subject) < 6) {
        			$this->error("The subject must be atleast 6 characters");
        			$error = true;
        		}
        		if (strlen($this->methodData->body) < 12) {
        			$this->error("The topic content must be atleast 12 characters");
        			$error = true;
        		}

        		if (!$error) {
        			$topic = $this->newTopic(
        				$this->methodData->id, 
        				$this->user->id, 
        				$this->methodData->subject, 
        				$this->methodData->body
        			);
        			header("Location:?page=forum&action=topic&id=" . $topic);
        		}

        	}

        	$this->html .= $this->page->buildElement("newTopic", $params);
        }

        public function method_topic() {

            if (isset($this->methodData->submit)) {

                $params["body"] = $this->methodData->body;

                $error = false;
                if (strlen($this->methodData->body) < 6) {
                    $this->error("The reply must be atleast 6 characters");
                    $error = true;
                }

                if (!$error) {
                    $topic = $this->newPost(
                        $this->methodData->id, 
                        $this->user->id, 
                        $this->methodData->body
                    );
                    header("Location:?page=forum&action=topic&id=" . $this->methodData->id);
                }

            }

            $topic = $this->getTopic($this->methodData->id);

            if (isset($this->methodData->quote)) {
                foreach ($topic["posts"] as $post) {
                    if ($post["id"] == $this->methodData->quote) {
                        $topic["quote"] = $post;
                        break;
                    }
                }
            }

            $this->html .= $this->page->buildElement(
                "topic", 
                $topic
            );
        }

        public function method_edit() {

            $post = $this->getPost($this->methodData->id);

            if (
                $post["user"] != $this->user->id &&
                !$this->user->hasAdminAccessTo("forum")
            ) {
                return $this->html .= $this->error("You dont have permission to edit this post");
            }

            if (isset($this->methodData->submit)) {

                $error = false;
                if (strlen($this->methodData->body) < 6) {
                    $this->error("The reply must be atleast 6 characters");
                    $error = true;
                }

                if (!$error) {
                    $update = $this->db->prepare("
                        UPDATE posts SET P_body = :body WHERE P_id = :id
                    ");
                    $update->bindParam(":body", $this->methodData->body);
                    $update->bindParam(":id", $this->methodData->id);
                    $update->execute();

                    header("Location:?page=forum&action=topic&id=" . $post["topic"]);
                }

            }

            $this->html .= $this->page->buildElement(
                "edit", 
                $post
            );
        }

        public function method_delete() {

            $post = $this->getPost($this->methodData->id);

            if (!$this->user->hasAdminAccessTo("forum")) {
                return $this->html .= $this->error("You dont have permission to delete this post");
            }

            if (isset($this->methodData->submit)) {

                $error = false;

                if (!$error) {
                    $update = $this->db->prepare("
                        DELETE FROM posts WHERE P_id = :id
                    ");
                    $update->bindParam(":id", $this->methodData->id);
                    $update->execute();

                    header("Location:?page=forum&action=topic&id=" . $post["topic"]);
                }

            }

            $this->html .= $this->page->buildElement(
                "delete", 
                $post
            );
        }

        public function method_deleteTopic() {

            $topic = $this->getTopic($this->methodData->id);

            if (!$this->user->hasAdminAccessTo("forum")) {
                return $this->html .= $this->error("You dont have permission to delete this topic");
            }

            if (isset($this->methodData->submit)) {

                $error = false;

                if (!$error) {
                    $update = $this->db->prepare("
                        DELETE FROM topics WHERE T_id = :id;
                        DELETE FROM posts WHERE P_topic = :id;
                    ");
                    $update->bindParam(":id", $this->methodData->id);
                    $update->execute();

                    header("Location:?page=forum&action=forum&id=" . $topic["forum"]["id"]);
                }

            }

            $topic["body"] = $topic["posts"][0]["body"];
            $topic["user"] = $topic["posts"][0]["user"];
            $topic["date"] = $topic["posts"][0]["date"];

            $this->html .= $this->page->buildElement(
                "deleteTopic", 
                $topic
            );
        }
        
    }

?>