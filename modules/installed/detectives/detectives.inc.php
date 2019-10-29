<?php

    class detectives extends module {
        
        public $allowedMethods = array(
            "id" => array( "type" => "GET" ),
            "submit" => array( "type" => "POST" ),
            "user" => array( "type" => "POST" ),
            "hours" => array( "type" => "POST" ),
            "detectives" => array( "type" => "POST" )
        );
        
        public $pageName = '';

        public function method_remove() {
            $search = $this->db->prepare("
                SELECT * FROM detectives WHERE D_id = :id
            ");

            $search->bindParam(":id", $this->methodData->id);
            $search->execute();

            $search = $search->fetch(PDO::FETCH_ASSOC);

            if ($search["D_user"] != $this->user->id) {
                return $this->error("This is not yours to remove!");
            }

            $del = $this->db->prepare("
                DELETE FROM detectives WHERE D_id = :id
            ");

            $del->bindParam(":id", $this->methodData->id);
            $del->execute();

            $this->alerts[] = $this->page->buildElement("success", array(
                "text" => "Detective result has been removed"
            ));

        }

        public function method_hire() {

            $settings = new settings();

            $costPerDetective = $settings->loadSetting("detectiveCost");

            if (isset($this->methodData->submit)) {
                if (!strlen($this->methodData->user)) {
                    return $this->error("Who are you looking for?");
                }
                
                $user = new User(false, $this->methodData->user);
    
                if (!isset($user->info->U_id)) {
                    return $this->error("This user does not exist");
                }

                if ($user->info->U_id == $this->user->id) {
                    return $this->error("You cant look for yourself!");
                }

                if (!isset($this->methodData->hours)) {
                    return $this->error("How long do you want to look for?");
                } 

                $hours = $this->methodData->hours;

                if ($hours < 1 || $hours > 5) {
                    return $this->error("Detectices can only search for 1-5 hours");
                }

                if (!isset($this->methodData->detectives)) {
                    return $this->error("How long do you want to look for?");
                } 

                $detectives = $this->methodData->detectives;

                if ($detectives < 1 || $detectives > 5) {
                    return $this->error("You can only hire 1-5 detectives");
                }

                $cost = $costPerDetective * $detectives * $hours;

                if ($cost > $this->user->info->US_money) {
                    return $this->error("You need $".number_format($cost)." to hire " . $detectives . " detective for " . $hours . " hours");
                }

                $success = (mt_rand(1, 100) <= ($detectives * 4) * $hours)?1:0;

                $insert = $this->db->prepare("
                    INSERT INTO detectives (
                        D_user, D_userToFind, D_detectives, D_start, D_end, D_success
                    ) VALUES (
                        :user, :toFind, :dets, :start, :end, :success
                    );
                ");

                $start = time();
                $end = $start + (3600 * $hours);

                $insert->bindParam(":user", $this->user->id);
                $insert->bindParam(":toFind", $user->info->US_id);
                $insert->bindParam(":dets", $detectives);
                $insert->bindParam(":start", $start);
                $insert->bindParam(":end", $end);
                $insert->bindParam(":success", $success);
                $insert->execute();

                $this->user->set("US_money", $this->user->info->US_money - $cost);

                $this->alerts[] = $this->page->buildElement("success", array(
                    "text" => "You hired " . $detectives . " detective for " . $hours . " hours costing you $" . number_format($cost)
                ));

            }             
            
        }
        
        public function constructModule() {

            $settings = new settings();

            $costPerDetective = $settings->loadSetting("detectiveCost", true, 125000);

            $user = "";

            if (isset($this->methodData->user)) {
                $user = $this->methodData->user;
            }

            $active = $this->db->prepare("
                SELECT
                    D_id as 'id', 
                    D_userToFind as 'uid',
                    D_detectives as 'detectives', 
                    D_start as 'start',
                    D_end as 'end',
                    D_end + 3600 as 'expires',
                    D_success as 'success'
                FROM detectives WHERE D_user = :id
                ORDER BY D_start DESC
            ");

            $active->bindParam(":id", $this->user->id);
            $active->execute();

            $hiredDetectives = $active->fetchAll(PDO::FETCH_ASSOC);

            foreach ($hiredDetectives as $key => $value) {
                $u = new User($value["uid"]);

                $value["isSearching"] = $value["end"] > time();
                $value["isExpired"] = $value["expires"] < time();

                $value["success"] = !!$value["success"];
                $value["user"] = $u->user;
                $value["location"] = $u->getLocation();
                $hiredDetectives[$key] = $value;
            }

            $this->html .= $this->page->buildElement("detectives", array(
                "detectiveCost" => $costPerDetective, 
                "hiredDetectives" => $hiredDetectives,
                "user" => $user
            ));
        }

    }

?>