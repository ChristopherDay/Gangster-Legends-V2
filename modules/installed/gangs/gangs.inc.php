<?php

    class gangs extends module {
        
        public $allowedMethods = array(
            "id" => array( "type" => "GET" ),
            "type" => array( "type" => "GET" ),
            "time" => array( "type" => "GET" ),
            "name" => array( "type" => "POST" ),
            "password" => array( "type" => "POST" ),
            "text" => array( "type" => "POST" ),
            "user" => array( "type" => "POST" ),
            "location" => array( "type" => "POST" )
        );
        
        public $pageName = '';

        public function constructModule() {

            $settings = new Settings();

            $invites = $this->db->prepare("
                SELECT 
                    GI_id as 'id', 
                    G_id as 'gangID', 
                    G_name as 'name'
                FROM gangInvites INNER JOIN gangs ON G_id = GI_gang WHERE GI_user = :u
            ");
            $invites->bindParam(":u", $this->user->id);
            $invites->execute();

            $locations = $this->db->prepare("
                SELECT
                    L_id as 'locationID', 
                    L_name as 'locationName', 
                    G_id as 'gangID',
                    G_boss as 'boss',
                    G_name as 'gangName', 
                    G_level + 4 as 'maxMembers'
                FROM locations
                LEFT OUTER JOIN gangs ON (G_location = L_id)
                WHERE G_id IS NOT NULL
            ");
            $locations->execute();
            $allLocations = $locations->fetchAll(PDO::FETCH_ASSOC);


            $available = $this->db->prepare("
                SELECT
                    L_id as 'locationID', 
                    L_name as 'locationName'
                FROM locations
                LEFT OUTER JOIN gangs ON (G_location = L_id)
                WHERE G_id IS NULL
            ");
            $available->execute();

            $availableLocations = $available->fetchAll(PDO::FETCH_ASSOC);

            foreach ($allLocations as $key => $value) {
                $members = count($this->db->selectAll("
                    SELECT * FROM userStats where US_gang = :gang
                ", array(
                    ":gang" => $value["gangID"]
                )));
                $user = new User($value["boss"]);
                $allLocations[$key]["user"] = $user->user;
                $allLocations[$key]["members"] = $members;
                $allLocations[$key]["percent"] = $members / $value["maxMembers"] * 100;
            }

            $this->html .= $this->page->buildElement("gangs", array(
                "invites" => $invites->fetchAll(PDO::FETCH_ASSOC), 
                "user" => $this->user->user, 
                "availableLocations" => $availableLocations, 
                "locations" => $allLocations, 
                "gangCost" => $settings->loadSetting("gangCost", true, 1000000)
            ));
        }

        public function method_manage() {
            if (!$this->user->info->US_gang) return;
            $this->construct = false;

            $gang = new Gang($this->user->info->US_gang);
            
            $roles = new Hook("gangPermission");
            $roles = $roles->run($this->user);

            $user = new User($this->methodData->user);

            if ($user->info->US_gang != $this->user->info->US_gang) {
                $this->error("This user is not part of this gang!");
                return $this->method_home();
            }

            if ($user->id == $gang->gang["boss"] || $user->id == $gang->gang["underboss"]) {
                $this->error("You cant modify the leadership!");
                return $this->method_home();
            } 

            if (isset($this->methodData->name)) {
                $role = $this->methodData->name;
                if ($gang->can($role, $user)) {
                    $query = $this->db->prepare("
                        DELETE FROM gangPermissions WHERE GP_user = :u AND GP_access = :r
                    ");
                } else {
                    $query = $this->db->prepare("
                        INSERT INTO gangPermissions (GP_user, GP_access) VALUES (:u, :r);
                    ");
                }
                $query->bindParam(":u", $user->id);
                $query->bindParam(":r", $role);
                $query->execute();
                $this->error("User roles updated!", "success");

                $newRole = "";
                foreach ($roles as $r) {
                    if ($r["key"] == $role) $newRole = $r["name"];
                }

                $gang->log("granted " . $user->info->U_name . " the ability to " . strtolower($newRole));
            }

            foreach ($roles as $key => $role) {
                $roles[$key]["allowed"] = $gang->can($role["key"], $user);
            }

            $this->html .= $this->page->buildElement("gangPermission", array(
                "user" => $user->id,
                "roles" => $roles
            ));
        }

        public function method_disband() {
            if (!$this->user->info->US_gang) return;
            $g = new Gang($this->user->info->US_gang);
            $gang = $g->gang;

            if (!$g->can("canDisband")) {
                return $this->error("You dont have permission to do this!");
            }

            $this->construct = false;

            if (isset($this->methodData->password)) {
                $pass = $this->user->encrypt(
                    $this->user->id . $this->methodData->password
                );

                if ($pass != $this->user->info->U_password) {
                    $this->error("Incorrect Password!");
                } else {
                    $this->db->delete("DELETE FROM gangs WHERE G_id = :id", array(
                        ":id" => $this->user->info->US_gang
                    ));
                    foreach ($gang["members"] as $member) {
                        $u = new User($member["user"]["id"]);
                        $u->newNotification("Your crew was disbanded!");
                        $u->set("US_gang", 0);
                    }
                    $this->error("You have disbanded your crew!", "success");
                    return $this->constructModule();
                }

            }

            $this->html .= $this->page->buildElement("disband");
        }

        public function method_logs() {
            if (!$this->user->info->US_gang) return;
            $g = new Gang($this->user->info->US_gang);
            $gang = $g->gang;

            if (!$g->can("viewLogs")) {
                return $this->error("You dont have permission to do this!");
            }
            $this->construct = false;
            $today = strtotime("00:00:00");
            if (isset($this->methodData->time)) {
                $start = abs(intval($this->methodData->time));
            } else {
                $start = $today;
            }
            $end = $start + 86400;
            $users = array();

            $logs = $this->db->prepare("
                SELECT 
                    GL_time as 'time', 
                    GL_user as 'uID', 
                    GL_log as 'log'
                FROM 
                    gangLogs 
                WHERE 
                    GL_time BETWEEN :s AND :e AND 
                    GL_gang = :g 
                ORDER BY 
                    GL_time DESC
            ");
            $logs->bindParam(":s", $start);
            $logs->bindParam(":e", $end);
            $logs->bindParam(":g", $this->user->info->US_gang);
            $logs->execute();

            $logs = $logs->fetchAll(PDO::FETCH_ASSOC);

            foreach ($logs as $key => $log) {
                if (!isset($users[$log["uID"]])) {
                    $users[$log["uID"]] = new User($log["uID"]);
                }
                $log["date"] = $this->date($log["time"]);
                $log["user"] = $users[$log["uID"]]->user;
                $logs[$key] = $log;
            }

            $this->html .= $this->page->buildElement("logs", array(
                "next" => array(
                    "date" => $this->date($end),
                    "time" => $end
                ), 
                "prev" => array(
                    "date" => $this->date($start - 86400),
                    "time" => $start - 86400
                ), 
                "today" => $today, 
                "logs" => $logs
            ));

        }

        public function method_home() {
            if (!$this->user->info->US_gang) return;

            $this->construct = false;

            $g = new Gang($this->user->info->US_gang);
            $gang = $g->gang;

            $invites = $this->db->prepare("
                SELECT 
                    GI_id as 'id', 
                    G_id as 'gangID', 
                    G_name as 'name'
                FROM gangInvites INNER JOIN gangs ON G_id = GI_gang WHERE GI_gang = :g
            ");
            $invites->bindParam(":g", $g->id);
            $invites->execute();

            $gang["invites"] = $invites->fetchAll(PDO::FETCH_ASSOC);
            $gang["canKick"] = $g->can("kick");
            $gang["canInvite"] = $g->can("invite");
            $gang["editInfo"] = $g->can("editInfo");
            $gang["canEditUser"] = $g->can("editUser");
            $gang["canUpgrade"] = $g->can("upgrade");
            $gang["editProfile"] = $g->can("editProfile");
            $gang["editPermissions"] = $g->can("editPermissions");
            $gang["canDisband"] = $g->can("canDisband");
            $gang["isBoss"] = $g->gang["boss"] == $this->user->id;
            $gang["canEditUsers"] = $gang["canKick"] || $gang["editPermissions"] || $gang["isBoss"];

            $gang["nextCapacity"] = $gang["maxMembers"] + 1;
            $gang["upgradeCost"] = $gang["level"] * 250000;

            $this->html .= $this->page->buildElement("gangHome", $gang);
        }

        public function method_view() {
            $this->construct = false;
            $id = $this->methodData->id;
            $g = new Gang($id);
            $gang = $g->gang;
            $this->html .= $this->page->buildElement("gangOverview", $gang);
        }

        public function method_setUnderboss() {
            if (!$this->user->info->US_gang) return;
            $this->construct = false;
            $gang = new Gang($this->user->info->US_gang);
            
            if ($this->user->id != $gang->gang["boss"]) {
                $this->error("You dont have permission to do this!");
                return $this->method_home();
            }

            $id = $this->methodData->user;

            $newUB = new User($id);

            $oldUB = new User($gang->gang["underboss"]);

            if ($newUB->id && $newUB->info->US_gang != $this->user->info->US_gang) {
                $this->error("This user is not part of this gang!");
                return $this->method_home();
            }

            $update = $this->db->prepare("UPDATE gangs SET G_underboss = :ub WHERE G_id = :id");
            $update->bindParam(":ub", $newUB->id);
            $update->bindParam(":id", $gang->id);
            $update->execute();

            if ($newUB->id != 0) {
                $newUB->newNotification("You have been promoted to the underboss of " . $gang->gang["name"]);
            }

            if ($oldUB->id != 0) {
                $oldUB->newNotification("You have been demoted from underboss of " . $gang->gang["name"]);
            }

            $this->error("Underboss updated", "success");
            if ($newUB->id != 0) {
                $gang->log("is now the underboss", $newUB);
            } else {
                $gang->log("was removed as the underboss", $oldUB);
            }
            $this->method_home();

        }

        public function method_manageInvite() {
            if (!isset($this->methodData->id)) {
                return $this->error("No invite found!");
            }

            $id = $this->methodData->id;

            $invite = $this->db->prepare("
                SELECT * FROM gangInvites INNER JOIN gangs ON G_id = GI_gang WHERE GI_id = :id
            ");
            $invite->bindParam(":id", $id);
            $invite->execute();

            $invite = $invite->fetch(PDO::FETCH_ASSOC);

            $gang = new Gang($invite["GI_gang"]);

            if ($invite["GI_user"] != $this->user->id) {
                return $this->error("This is not your invite!");
            }

            if (count($gang->gang["members"]) >= $gang->gang["maxMembers"]) {
                return $this->error("This gang is full!");
            }

            if ($this->methodData->type == "accept") {
                $this->user->set("US_gang", $invite["GI_gang"]);
                $this->error("You have accepted the invite!", "success");
                $gang->log("joined the gang");
                $hook = new Hook("joinGang");
                $hook = $hook->run($this->user);

                $access = $this->db->delete("
                    DELETE FROM gangPermissions WHERE GP_user = :user 
                ", array(
                    ":user" => $this->user->id
                ));

            } else {
                $this->error("You have declined the invite!", "warning");
                $gang->log("declined the invite to join the gang");
            }

            $remove = $this->db->prepare("
                DELETE FROM gangInvites WHERE GI_id = :id
            ");
            $remove->bindParam(":id", $id);
            $remove->execute();

        }

        public function method_kick() {
            if (!$this->user->info->US_gang) return;
            $this->construct = false;
            $gang = new Gang($this->user->info->US_gang);
            if (!$gang->can("kick")) {
                return $this->error("You dont have permission to do this!");
            }

            $id = $this->methodData->user;

            $user = new User($id);

            if ($user->info->US_gang != $this->user->info->US_gang) {
                $this->error("This user is not part of this gang!");
                return $this->method_home();
            }

            if ($user->id == $gang->gang["boss"] || $user->id == $gang->gang["underboss"]) {
                $this->error("You cant kick the leadership!");
                return $this->method_home();
            } 

            $user->set("US_gang", 0);
            $user->newNotification("You have been kicked from " . $gang->gang["name"]);
            
            $permissions = $this->db->prepare("DELETE FROM gangpermissions WHERE GP_user = :u");
            $permissions->bindParam(":u", $user->id);
            $permissions->execute();

            $this->error("You have kicked " . $user->info->U_name . " from the gang!", "success");

            $gang->log("kicked " . $user->info->U_name . " from the gang!");

            $this->method_home();
        }

        public function method_leave() {
            if (!$this->user->info->US_gang) return;

            $this->construct = false;

            if (!isset($this->methodData->type)) {
                return $this->html .= $this->page->buildElement("leaveSure");
            }

            $gang = new Gang($this->user->info->US_gang);

            $gang->log("left the gang");

            $this->db->update("
                UPDATE gangs SET G_underboss = 0 WHERE G_underboss = :uid and G_id = :id;
                UPDATE gangs SET G_coLeader = 0 WHERE G_coLeader = :uid and G_id = :id;
                UPDATE gangs SET G_LHM = 0 WHERE G_LHM = :uid and G_id = :id;
                UPDATE gangs SET G_RHM = 0 WHERE G_RHM = :uid and G_id = :id;
            ", array(
                ":uid" => $this->user->id,
                ":id" => $this->user->info->US_gang
            ));

            $this->user->set("US_gang", 0);

            $this->error("You have left your gang!", "success");
            return $this->constructModule(); 

        }

        public function method_upgrade() {
            if (!$this->user->info->US_gang) return;
            $this->construct = false;
            

            $g = new Gang($this->user->info->US_gang);
            if (!$g->can("upgrade")) {
                return $this->error("You dont have permission to do this!");
            } 

            $gang = $g->getgang();

            $cost = $gang["level"] * 250000;

            if ($gang["G_money"] < $cost) {
                $this->error("Your gang can't afford to do this!");
                return $this->method_home();
            }   

            $this->db->update("
                UPDATE `gangs` SET G_level = :level, G_money = :money WHERE G_id = :id 
            ", array(
                ":level" => $gang["level"] + 1, 
                ":money" => $gang["G_money"] - $cost,
                ":id" => $gang["id"] 
            ));

            $this->error("You have upgraded your gang capacity for ".$this->money($cost)."!", "success");

            $g->log("upgraded the gang capacity for ".$this->money($cost)."!");

            $this->method_home();
        }
            
        public function method_invite() {
            if (!$this->user->info->US_gang) return;
            $this->construct = false;
            
            $gang = new Gang($this->user->info->US_gang);
            
            if (!$gang->can("invite")) {
                return $this->error("You dont have permission to do this!");
            }

            $invitedUser = new User(false, $this->methodData->name);

            if (!isset($invitedUser->info->U_id)) {
                $this->error("This user does not exist!");
                return $this->method_home();
            }

            if ($invitedUser->info->US_gang == $this->user->info->US_gang) {
                $this->error("This user is already part of this gang!");
                return $this->method_home();
            }

            $existingInvites = $this->db->prepare("SELECT * FROM gangInvites WHERE GI_user = :u AND GI_gang = :g");
            $existingInvites->bindParam(":u", $invitedUser->info->U_id);
            $existingInvites->bindParam(":g", $gang->gang["id"]);
            $existingInvites->execute();

            $existingInvites = $existingInvites->fetch(PDO::FETCH_ASSOC);

            if (isset($existingInvites["GI_id"])) {
                $this->error("This user is already invited to this gang!");
                return $this->method_home();
            }

            $this->error($invitedUser->info->U_name . " has been invited!", "success");
            $gang->log("invited " . $invitedUser->info->U_name . " to join the gang");

            $invitedUser->newNotification($this->page->buildElement("gangInvite", array(
                "user" => $this->user->info->U_name, 
                "gang" => $gang->gang["name"], 
                "id" => $gang->gang["id"]
            )));

            $invite = $this->db->prepare("INSERT INTO gangInvites (GI_user, GI_gangUser, GI_gang) VALUES (:u, :gu, :g)");
            $invite->bindParam(":u", $invitedUser->info->U_id);
            $invite->bindParam(":gu", $this->user->id);
            $invite->bindParam(":g", $gang->gang["id"]);
            $invite->execute();

            $this->method_home();
        }

        public function method_editInfo() {
            if (!$this->user->info->US_gang) return;
            $this->construct = false;

            $gang = new Gang($this->user->info->US_gang);

            if (!$gang->can("editInfo")) {
                return $this->error("You dont have permission to do this!");
            }

            if (isset($this->methodData->text)) {
                $update = $this->db->prepare("
                    UPDATE gangs SET G_info = :info WHERE G_id = :id
                ");
                $update->bindParam(":id", $gang->id);
                $update->bindParam(":info", $this->methodData->text);
                $update->execute();
                $gang->gang["info"] = $this->methodData->text;
                $this->error("Gang information has been updated", "success");
                $gang->log("updated the gang information");
            }


            $this->html .= $this->page->buildElement("editInfo", array(
                "info" => $gang->gang["info"]
            ));
        }

        public function method_editProfile() {
            if (!$this->user->info->US_gang) return;
            $this->construct = false;

            $gang = new Gang($this->user->info->US_gang);

            if (!$gang->can("editProfile")) {
                return $this->error("You dont have permission to do this!");
            }

            if (isset($this->methodData->text)) {
                $update = $this->db->prepare("
                    UPDATE gangs SET G_desc = :desc WHERE G_id = :id
                ");
                $update->bindParam(":id", $gang->id);
                $update->bindParam(":desc", $this->methodData->text);
                $update->execute();
                $gang->gang["desc"] = $this->methodData->text;
                $this->error("Gang profile has been updated", "success");
                $gang->log("updated the gang profile");
            }

            $this->html .= $this->page->buildElement("editProfile", array(
                "desc" => $gang->gang["desc"]
            ));
        }

        public function method_new() {

            if (
                !isset($this->methodData->name) ||
                !isset($this->methodData->location)
            ) {
                return;
            }

            $name = $this->methodData->name;
            $location = $this->methodData->location;
            $settings = new Settings();
            $cost = $settings->loadSetting("gangCost", true, 1000000);

            if ($this->user->info->US_gang) {
                return $this->error("You are already part of a gang, please leave that before starting a new gang!");
            }

            if ($this->user->info->US_money < $cost) {
                return $this->error("You need " . $this->money($cost) . " to start a gang!");
            }

            if (strlen($name) < 4 || strlen($name) > 24) {
                return $this->error("Your gang name needs to be between 4-24 characters!");
            }

            $gangExists = $this->db->prepare("
                SELECT * FROM gangs WHERE G_location = :location
            ");
            $gangExists->bindParam(":location", $location);
            $gangExists->execute();

            $gangExists = $gangExists->fetch(PDO::FETCH_ASSOC);

            if (isset($gangExists["G_id"])) {
                return $this->error("A gang already exists in this location");
            }

            $this->user->set("US_money", $this->user->info->US_money - $cost);

            $insert = $this->db->prepare("
                INSERT INTO gangs (
                    G_name, 
                    G_boss,
                    G_location
                ) VALUES (
                    :name, 
                    :boss, 
                    :location
                );
            ");
            $insert->bindParam(":name", $name);
            $insert->bindParam(":location", $location);
            $insert->bindParam(":boss", $this->user->id);
            $insert->execute();

            $id = $this->db->lastInsertId();

            $this->user->set("US_gang", $id);

        }
        
        public function error($text, $type = "error") {
            $this->alerts[] = $this->page->buildElement($type, array(
                "text" => $text
            ));
        }
        
    }

