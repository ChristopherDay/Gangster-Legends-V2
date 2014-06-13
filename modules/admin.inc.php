<?php

    class admin extends module {
		
		public $pageName = 'Admin';
        
        public $allowedMethods = array();
        
        public function constructModule() {
            
            /* Redirect the user to the home page if they are a user */
            if ($this->user->info->U_userLevel == 1) {
                header("Location:/");
                exit;
            }
            
            /* Now we know the user is a staff member lets load the navigation */
            $path = dirname(__FILE__)."/admin/";

            /* Make the firs link a link back to the game */
            $link = '<li><a href="/">Back To the game</a></li>';

            if ($handle = opendir($path)) {
                while (false !== ($file = readdir($handle))) {
                    if ('.' === $file) continue;
                    if ('..' === $file) continue;

                    $fileDir = dirname(__FILE__)."/admin/".$file."/functions.php";

                    if (file_exists($fileDir)) {

                        include $fileDir;

                        $num = 0;
                        $nLink = '<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="?page=admin&module='.$file.'">'.$adminPage.'</a><ul class="dropdown-menu">';

                            foreach ($adminLinks as $l => $a) {

                                if ($this->user->info->U_userLevel >= $a['userLevel']) {
                                    $nLink .= '<li><a href="?page=admin&module='.$file.'&action='.$a['link'].'">'.$l.'</a></li>';
                                    $num++;
                                }

                            }

                        $nLink .= '</ul></li>';

                        if ($num >0) {
                            $link .= $nLink;
                        }
                    }

                }
                closedir($handle);
            }

            /* Bind Linkjs to the template */
            $this->page->addToTemplate("adminLinks", $link);
            
        }
        
    }

?>
