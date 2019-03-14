<?php

    class Gang {

        public $id;

        public $gang;

        public function __construct ($gangID) {
            global $user, $db;

            $this->id = $gangID;
            $this->user = $user;
            $this->db = $db;
            $this->gang = $this->getGang();
        }

        public function log($text, $user = false) {
            if (!$user) $user = $this->user;

            $log = $this->db->prepare("
                INSERT INTO gangLogs (GL_gang, GL_time, GL_user, GL_log) VALUES (:g, UNIX_TIMESTAMP(), :u, :t);
            ");
            $log->bindParam(":g", $this->id);
            $log->bindParam(":u", $user->id);
            $log->bindParam(":t", $text);
            $log->execute();
        }

        public function can($doWhat, $user = false) {

            if (!$user) $user = $this->user;

            if (!$this->id) return false;
            if ($this->id != $user->info->US_gang) return false;
            if (($doWhat == "destroy" || $doWhat == "changeBoss") && $user->id != $this->gang["boss"]) return false;
            if ($user->id == $this->gang["boss"] || $user->id == $this->gang["underboss"]) return true;

            $access = $this->db->prepare("
                SELECT * FROM gangPermissions WHERE GP_user = :user AND GP_access = :doWhat 
            ");
            $access->bindParam(":user", $user->id);
            $access->bindParam(":doWhat", $doWhat);
            $access->execute();

            $access = $access->fetch(PDO::FETCH_ASSOC);

            return !!$access["GP_user"];

        }

        public function getGang() {

            if (!$this->id) return;

            $gang = $this->db->prepare("
                SELECT 
                    G_id as 'id',
                    G_name as 'name',
                    G_desc as 'desc',
                    G_info as 'info',
                    G_boss as 'boss',
                    G_underboss as 'underboss',
                    G_location as 'location'
                FROM
                    gangs
                    INNER JOIN locations ON (G_location = L_id)
                WHERE 
                    G_id = :id
            ");

            $gang->bindParam(":id", $this->id);
            $gang->execute();

            $gang = $gang->fetch(PDO::FETCH_ASSOC);

            $gang["members"] = $this->getGangMembers($gang);

            return $gang;
        }

        public function getGangMembers($gang) {
            
            $members = array();

            $users = $this->db->prepare("
                SELECT * FROM users INNER JOIN userStats ON (US_id = U_id) WHERE US_gang = :gang 
            ");
            $users->bindParam(":gang", $gang["id"]);
            $users->execute();

            $users = $users->fetchAll(PDO::FETCH_ASSOC);

            foreach ($users as $user) {
                $u = new User($user["U_id"]);

                $canBeKicked = true;
                $gangRank = "Member";

                if ($gang["boss"] == $u->id) {
                    $gangRank = "Boss";
                    $canBeKicked = false;
                }

                if ($gang["underboss"] == $u->id) {
                    $gangRank = "Underboss";
                    $canBeKicked = false;
                }

                $members[] = array(
                    "user" => $u->user, 
                    "gangRank" => $gangRank,
                    "canBeKicked" => $canBeKicked
                );
            }

            return $members;
        }
    }

?>