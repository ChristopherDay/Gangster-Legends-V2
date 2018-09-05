<?php

    class usersOnline extends module {
        
        public $allowedMethods = array();
		
		public $pageName = 'Users Online';
        
        public function constructModule() {
			
			$times = array(
				'Last 15 Minutes'=>900, 
				'Last 24 Hours'=>86400, 
			);
			
			$durations = array();
			
			foreach ($times as $title=>$intval) {
			
				unset($users);
				$users = array();

				$online = $this->db->prepare("
					SELECT * FROM userTimers 
					WHERE UT_desc = 'laston' AND UT_time > ".(time()-$intval)." 
					ORDER BY UT_time DESC
				");
				$online->execute();

				while ($row = $online->fetch(PDO::FETCH_ASSOC)) {

					$userOnline = new user($row['UT_user']);

					$users[] = array(
						"name" => $userOnline->info->U_name,
						"id" => $userOnline->info->U_id
					);

				}

				$durations[] = array(
					"title" => $title, 
					"users" => $users
				);

			}
			
			$this->html .= $this->page->buildElement("usersOnline", array("durations" => $durations));

            
        }
        
    }

?>