<?php

    class locations extends module {
        
        public $allowedMethods = array('id'=>array('type'=>'get'));
		
		public $pageName = 'Locations';
        
        public function constructModule() {
            
            $locations = $this->db->prepare("SELECT * FROM locations");
            $locations->execute();
            
            $locationsArray = array();
            while ($row = $locations->fetchObject()) {
            
                $locationsArray[] = array(
                    "name" => $row->L_name, 
                    "cost" => $row->L_cost, 
                    "bullets" => $row->L_bullets, 
                    "bulletCost" => $row->L_bulletCost, 
                    "cooldown" => $row->L_cooldown, 
                    "id" => $row->L_id,
                    
                
                );
                
            }
            
            $this->html .= $this->page->buildElement('locationsHolder', array(
                "locations" => $locationsArray
            ));
        }
        
        
        
    }

?>
