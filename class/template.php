<?php

    class template {
    
        public $page, $dontRun = false;
        
        public function __construct($moduleName = false) {
        
            global $page, $user;
            $this->page = $page;

            if (!$moduleName) return; 

            $moduleInfo = $page->modules[$moduleName];

            $this->page->loadedTheme = $this->page->theme;

            if ($moduleName == "admin") {
                $this->page->loadedTheme = $this->page->adminTheme;
                $this->loadMainPage();
            } else if (!$moduleInfo["requireLogin"]) {
                $this->loadMainPage('login');
            } else {
                $this->loadMainPage('loggedin');
            }
        
        }
        
        private function loadMainPage($pageType = "index") {
        
            if (file_exists('themes/'.$this->page->loadedTheme.'/'.$pageType.'.php')) {
                include 'themes/'.$this->page->loadedTheme.'/'.$pageType.'.php';
                $this->mainTemplate = new mainTemplate();
            } else {
                die("Main template '".$this->page->loadedTheme."' file not found!");
            }

            if (isset($this->mainTemplate->globalTemplates)) {
                foreach ($this->mainTemplate->globalTemplates as $templateName => $templateStructure) {
                    $this->$templateName = $templateStructure;
                }
            }

            $hook = new Hook("alterGlobalTemplate");
            $hook->run($this);
        }
        
        /* Global elements */
        public $success = '<div class="alert alert-success">
            <{text}>
        </div>';

        public $error = '<div class="alert alert-danger">
            <{text}>
        </div>';

        public $info = '<div class="alert alert-info">
            <{text}>
        </div>';

        public $warning = '<div class="alert alert-warning">
            <{text}>
        </div>';

        public $userName = '
            <a href="?page=profile&view={user.id}" class="user user-status-{user.status} user-level-{user.userLevel}" style="color: {user.color};">
                {user.name}
            </a>
        ';

        public $timer = '
            <div class="alert alert-danger game-timer">
                <{text}> <br />
                Time remaining: <span data-remove-when-done data-timer-type="inline" data-timer="{time}"></span>
            </div>
        ';

        public $propertyOwnership = '
            {#unless closed}
                {#if user}
                    Owned by {>userName}
                {/if}
                {#unless user}
                    No Owner - <a href="?page={module}&action=own">Buy property $1,000,000</a>
                {/unless}

                {#if userOwnsThis}
                    <hr />
                    Profit: {profit} <br />
                    <a href="?page=propertyManagement&module={module}">Manage Property</a>  
                {/if}
            {/unless}
        ';

        public $levelUpNotification = '
            You have ranked up, you are now a {rankName}.
            {#if rewards}
                <br />
                <strong>Rewards</strong>
                <br />
                <ul>
                    {#each rewards}
                        <li>
                            <strong>
                                {name}:
                            </strong> {value}
                        </li>
                    {/each}
                </ul>
            {/if}
        ';

    }

    function _ago($tm,$rcs = 0) {
        $cur_tm = time(); $dif = $cur_tm-$tm;
        $pds = array('second','minute','hour','day','week','month','year','decade');
        $lngh = array(1,60,3600,86400,604800,2630880,31570560,315705600);
        for($v = sizeof($lngh)-1; ($v >= 0)&&(($no = $dif/$lngh[$v])<=1); $v--); if($v < 0) $v = 0; $_tm = $cur_tm-($dif%$lngh[$v]);

        $no = floor($no); if($no <> 1) $pds[$v] .='s'; $x=sprintf("%d %s ",$no,$pds[$v]);
        if(($rcs == 1)&&($v >= 1)&&(($cur_tm-$_tm) > 0)) $x .= time_ago($_tm);
        return $x;
    }

?>