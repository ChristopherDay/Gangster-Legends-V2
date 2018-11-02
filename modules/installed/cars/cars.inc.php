<?php

    class cars extends module {
        
        public $allowedMethods = array('id'=>array('type'=>'get'));
		
		public $pageName = 'Cars';
        
        public function constructModule() {
            
            $cars = $this->db->prepare("SELECT * FROM cars");
            $cars->execute();
            
            $carsArray = array();
            while ($row = $cars->fetchObject()) {
            
                $carsArray[] = array(
                    "name" => $row->CA_name, 
                    "id" => $row->CA_id, 
                
                );
                
            }
            
            $this->html .= $this->page->buildElement('carsHolder', array(
                "cars" => $carsArray
            ));
        }
        
        
        
    }

?>
