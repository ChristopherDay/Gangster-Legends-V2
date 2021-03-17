<?php

    class hospital extends module {
            
        public $allowedMethods = array();

        public function getHospitalInfo () {

            $settings = new Settings();
            $timeToFull = $settings->loadSetting("hospitalTimeUntillFull", true, 7200);
            $moneyToFull = $settings->loadSetting("hospitalmoneyUntillFull", true, 25000);
            
            $rank = $this->user->getRank();
            $health = $this->user->info->US_health;

            $healthPerc = round(($rank->R_health - $health) / $rank->R_health * 100, 2);

            if ($healthPerc) {
                $time = round($timeToFull / 100 * $healthPerc);
                $money = round($moneyToFull / 100 * $healthPerc);
            } else {
                $time = 0;
                $money = 0;
            }

            $users = $this->db->selectAll("
                SELECT
                    UT_user as 'user',
                    UT_time as 'time'
                FROM userTimers 
                INNER JOIN userStats ON (UT_user = US_id)
                WHERE US_location = :location AND UT_desc = 'hospital' AND UT_time > UNIX_TIMESTAMP()
            ", array(
                ":location" => $this->user->info->US_location
            ));

            foreach ($users as $key => $value) {
                $u = new User($value["user"]);
                $users[$key]["user"] = $u->user;
            }

            return array(
                "money" => $money,
                "time_t" => $time,
                "time" => $this->timeLeft($time),
                "healthPerc" => $healthPerc,
                "health" => $health,
                "location" => $this->user->getLocation(), 
                "users" => $users
            );

        }
        
        public function constructModule() {
            $info = $this->getHospitalInfo();

            if ($this->user->checkTimer("hospital")) {
                $this->html .= $this->page->buildElement('hospital', $info);
            } else {
                $time = $this->user->getTimer('hospital');
                $crimeError = array(
                    "timer" => "hospital",
                    "text"=>'You are recovering in hospital!',
                    "time" => $this->user->getTimer("hospital")
                );

                $this->html .= $this->page->buildElement('timer', $crimeError);
                $this->html .= $this->page->buildElement('inHospital', $info);
                
            }

            
        }

        public function method_checkin() {
            $info = $this->getHospitalInfo();
            
            if (!$info["health"]) {
                return $this->error("You are full health!");
            }

            if ($info["money"] > $this->user->info->US_money) {
                return $this->error("You can't afford this!");
            }

            $this->user->set("US_money", $this->user->info->US_money - $info["money"]);
            $this->user->set("US_health", 0);
            $this->user->updateTimer("hospital", time() + $info["time_t"]);

        }
        
    }

?>