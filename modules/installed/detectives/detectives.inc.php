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
            $search = $this->db->select("SELECT * FROM detectives WHERE D_id = :id", array(
                ":id" => $this->methodData->id
            ));

            if ($search["D_user"] != $this->user->id) {
                return $this->error("This is not yours to remove!");
            }

            $del = $this->db->delete("DELETE FROM detectives WHERE D_id = :id", array(
                ":id" => $this->methodData->id
            ));

            $this->alerts[] = $this->page->buildElement("success", array(
                "text" => "Detective result has been removed"
            ));

        }

        public function method_hire() {

            $settings = new settings();

            $costPerDetective = $settings->loadSetting("detectiveCost", true, 125000);
            $reportDuration = $settings->loadSetting("detectiveDuration", true, 1);
            $expireTime = $settings->loadSetting("detectiveExpire", true, 600);

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

                $duration = $this->methodData->hours;

                if ($duration < 1 || $duration > 5) {
                    return $this->error("Detectives cant search for this long");
                }

                if (!isset($this->methodData->detectives)) {
                    return $this->error("How long do you want to look for?");
                } 

                $detectives = $this->methodData->detectives;

                if ($detectives < 1 || $detectives > 5) {
                    return $this->error("You can only hire 1-5 detectives");
                }

                $cost = $costPerDetective * $detectives * $duration;

                if ($cost > $this->user->info->US_money) {
                    return $this->error("You need ".$this->money($cost)." to do this!");
                }

                $success = (mt_rand(1, 100) <= ($detectives * 4) * $duration)?1:0;

                $start = time();
                $end = $start + ($reportDuration * $duration);

                $insert = $this->db->insert("
                    INSERT INTO detectives (
                        D_user, D_userToFind, D_detectives, D_start, D_end, D_success
                    ) VALUES (
                        :user, :toFind, :dets, :start, :end, :success
                    );
                ", array(
                    ":user" => $this->user->id,
                    ":toFind" => $user->info->US_id,
                    ":dets" => $detectives,
                    ":start" => $start,
                    ":end" => $end,
                    ":success" => $success
                ));
     
                $actionHook = new hook("userAction");
                $action = array(
                    "user" => $this->user->id, 
                    "module" => "detectives", 
                    "id" => $user->info->US_id, 
                    "success" => true, 
                    "reward" => $cost
                );
                $actionHook->run($action);

                $this->user->set("US_money", $this->user->info->US_money - $cost);

                $this->alerts[] = $this->page->buildElement("success", array(
                    "text" => "You hired the detectives"
                ));

            }             
            
        }

        /* https://stackoverflow.com/questions/1416697/converting-timestamp-to-time-ago-in-php-e-g-1-day-ago-2-days-ago */
        public function timeElapsedString($datetime, $full = false) {
            $now = new DateTime;
            $ago = new DateTime($datetime);
            $diff = $now->diff($ago);

            $diff->w = floor($diff->d / 7);
            $diff->d -= $diff->w * 7;

            $string = array(
                'y' => 'year',
                'm' => 'month',
                'w' => 'week',
                'd' => 'day',
                'h' => 'hour',
                'i' => 'minute',
                's' => 'second',
            );
            foreach ($string as $k => &$v) {
                if ($diff->$k) {
                    $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
                } else {
                    unset($string[$k]);
                }
            }

            if (!$full) $string = array_slice($string, 0, 1);
            return $string ? implode(', ', $string) . '' : 'just now';
        }
        
        public function constructModule() {

            $settings = new settings();

            $costPerDetective = $settings->loadSetting("detectiveCost", true, 125000);
            $reportDuration = $settings->loadSetting("detectiveDuration", true, 1);
            $expireTime = $settings->loadSetting("detectiveExpire", true, 600);

            $user = "";

            if (isset($this->methodData->user)) {
                $user = $this->methodData->user;
            }

            $hiredDetectives = $this->db->selectAll("
                SELECT
                    D_id as 'id', 
                    D_userToFind as 'uid',
                    D_detectives as 'detectives', 
                    D_start as 'start',
                    D_end as 'end',
                    D_end + :expireTime as 'expires',
                    D_success as 'success'
                FROM detectives WHERE D_user = :id
                ORDER BY D_start DESC
            ", array(
                ":id" => $this->user->id,
                ":expireTime" => $expireTime
            ));

            foreach ($hiredDetectives as $key => $value) {
                $u = new User($value["uid"]);

                $value["isSearching"] = $value["end"] > time();
                $value["isExpired"] = $value["expires"] < time();

                $value["success"] = !!$value["success"];
                $value["user"] = $u->user;
                $value["location"] = $u->getLocation();
                $hiredDetectives[$key] = $value;
            }

            $hours = array();
            $i = 1;

            while ($i <= 5) {
                $hour = $i;
                $time = $i * $reportDuration;
                $ts = "@" . (time() - $time);
                $hours[] = array(
                    "duration" => $hour, 
                    "time" => $this->timeElapsedString($ts, true)
                );
                $i++;
            }


            $this->html .= $this->page->buildElement("detectives", array(
                "detectiveCost" => $costPerDetective, 
                "hiredDetectives" => $hiredDetectives,
                "hours" => $hours,
                "user" => $user
            ));
        }

    }

