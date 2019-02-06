<?php

    class forum extends module {
        
        public $allowedMethods = array(
            "id" => array( "type" => "GET" ),
            "type" => array( "type" => "GET" ),
        	"pageNumber" => array( "type" => "GET" ),
        	"subject" => array( "type" => "POST" ),
        	"body" => array( "type" => "POST" ),
            "submit" => array( "type" => "POST" ), 
        	"time" => array( "type" => "POST" ), 
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
                FROM 
                    posts 
                WHERE P_id = :id
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

            switch ((int) $topic["T_type"]) {
                case 2: $type = "Important:"; break;
                case 1: $type = "Sticky:"; break;
                default: $type = ""; break;
            }

            $output = array(
                "forum" => $this->getForum($topic["T_forum"]),
                "subject" => $topic["T_subject"], 
                "topic" => $topic["T_id"], 
                "locked" => $topic["T_status"] == 1, 
                "posts" => $posts, 
                "type" => $type
            );

            return $output;

        }

        public function getForum($id) {

            $forum = $this->db->prepare("SELECT 
                F_id as 'id', 
                F_name as 'name' 
            FROM 
                forums 
            WHERE
                F_id = :id
            ");
            $forum->bindParam(":id", $id);
            $forum->execute();
            $forum = $forum->fetch(PDO::FETCH_ASSOC);

            if ($forum["id"]) {
                return $forum;
	        }

	        return false;

        }

        public function getTopics($forum) {

            $page = 1;
            if (isset($this->methodData->pageNumber)) $page = abs(intval($this->methodData->pageNumber));
            $perPage = 10;
            $from = ($page - 1) * $perPage;

            $count = $this->db->prepare("
                SELECT CEIL(COUNT(*) / $perPage) as 'count' FROM topics WHERE T_forum = :forum
            ");
            $count->bindParam(":forum", $forum);
            $count->execute();
            $topics = $this->db->prepare("
                SELECT 
                    T_id as 'id', 
                    T_date as 'time', 
                    T_user as 'user', 
                    T_status as 'locked', 
                    T_subject as 'subject', 
                    (
                        CASE T_type
                        WHEN 2 THEN 'Important:'
                        WHEN 1 THEN 'Sticky:'
                        ELSE '' END
                    )
                    as 'type'
                FROM topics
                WHERE
                    T_forum = :forum
                ORDER BY T_type DESC, T_date DESC
                LIMIT $from, $perPage
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

            return array(
                "topics" => $topics,
                "pages" => $this->buildPages($page, $count->fetch(PDO::FETCH_ASSOC)["count"], $forum)
            ); 
        }

        /*<li>
            <a href="#" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
        <li>
            <a href="#" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>*/

        public function buildPages($page, $pages, $id) {
            $i = 1;
            $rtn = array(
                array(
                    "name" => "&laquo;", 
                    "page" => $page>1?$page-1:1, 
                    "id" => $id, 
                    "active" => 0
                )
            );
            while ($i <= $pages) {
                $rtn[] = array(
                    "name" => $i, 
                    "page" => $i, 
                    "id" => $id, 
                    "active" => $i == $page
                );
                $i++;
            }
            $rtn[] = array(
                "name" => "&raquo;", 
                "page" => $page==$pages?$pages:$page+1, 
                "id" => $id, 
                "active" => 0
            );
            return $rtn;
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

            $topics = $this->getTopics($this->methodData->id);

        	$this->html .= $this->page->buildElement("topics", array(
        		"topics" => $topics["topics"], 
                "pages" => $topics["pages"],
                "forumInfo" => $this->getForum($this->methodData->id),
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

                if (!$this->user->checkTimer("forumMute")) {
                    $this->error("You have been forum muted!");
                    $error = true;
                }
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

            $topic = $this->getTopic($this->methodData->id);

            if (isset($this->methodData->submit)) {

                $params["body"] = $this->methodData->body;

                $error = false;

                if (!$this->user->checkTimer("forumMute")) {
                    $this->error("You have been forum muted!");
                    $error = true;
                }

                if ($topic["locked"] == 1) {
                    $this->error("This topic is locked!");
                    $error = true;
                }

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

        public function method_lock() {

            $topic = $this->getTopic($this->methodData->id);

            if (!$this->user->hasAdminAccessTo("forum")) {
                return $this->html .= $this->error("You dont have permission to delete this topic");
            }

            $status = $topic["locked"]?0:1;

            $update = $this->db->prepare("
                UPDATE topics SET T_status = $status WHERE T_id = :id;
            ");
            $update->bindParam(":id", $this->methodData->id);
            $update->execute();

            header("Location:?page=forum&action=topic&id=" . $this->methodData->id);

        }

        public function method_type() {

            $topic = $this->getTopic($this->methodData->id);

            if (!$this->user->hasAdminAccessTo("forum")) {
                return $this->html .= $this->error("You dont have permission to delete this topic");
            }

            $update = $this->db->prepare("
                UPDATE topics SET T_type = :type WHERE T_id = :id;
            ");
            $update->bindParam(":id", $this->methodData->id);
            $update->bindParam(":type", $this->methodData->type);
            $update->execute();

            header("Location:?page=forum&action=topic&id=" . $this->methodData->id);

        }

        public function method_mute() {

            if (!$this->user->hasAdminAccessTo("forum")) {
                return $this->html .= $this->error("You dont have permission to delete this post");
            }

            $user = new User($this->methodData->id);

            if (isset($this->methodData->submit)) {
                $time = $this->methodData->time;
                $user->updateTimer("forumMute", $time, true);
                $this->html .= $this->page->buildElement("success", array(
                    "text" => "This user has been muted until " . $this->date(time() + $time)
                ));
            }

            $this->html .= $this->page->buildElement(
                "mute", 
                array(
                    "user" => $user->user
                )
            );
        }
        
    }

?>