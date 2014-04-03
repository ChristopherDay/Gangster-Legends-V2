<?php

    class usersOnline extends module {
        
        public $allowedMethods = array();
		
		public $pageName = 'Users Online';
        
        public function constructModule() {
			
			$times = array(
				'Last 15 Minutes'=>900, 
				'Last 24 Hours'=>86400, 
			);
			
			$html = '';
			
			foreach ($times as $title=>$intval) {
			
				unset($users);
				
				$users = array();

				$online = $this->db->prepare("SELECT * FROM userTimer WHERE UT_desc = 'laston' AND UT_time > ".(time()-$intval)." ORDER BY UT_time DESC");
				$online->execute();

				while ($row = $online->fetch(PDO::FETCH_ASSOC)) {

					$userOnline = new user($row['UT_user']);

					$users[] = $this->page->buildElement("user", array(
						$userOnline->info->U_name,
						$userOnline->info->U_id
					));

				}

				$html .= $this->page->buildElement("usersOnline", array(implode(" - ", $users), $title));

			}
			
			$this->html .= $this->page->buildElement("usersOnlineHolder", array($html));
            
        }
        
    }

?>