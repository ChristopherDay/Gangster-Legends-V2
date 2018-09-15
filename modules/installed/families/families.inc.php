<?php

    class families extends module {
        
        public $allowedMethods = array("id" => array("type"=>"get"));
		
		public $pageName = 'All Families';
        
        public function constructModule() {
            $gangs = $this->db->prepare("
            	SELECT
            		G_id as 'id', 
            		G_name as 'name', 
            		COUNT(US_id) as 'members'
            	FROM 
            		gangs
            		INNER JOIN userStats ON (US_gang = G_id)
            	GROUP BY 
            		G_id
            ");

            $gangs->execute();

            debug($gangs);

            $this->html .= $this->page->buildElement('families', array(
            	"gangs" => $gangs->fetchAll(PDO::FETCH_ASSOC)
            ));


        }
        
    }

?>