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

        public $userName = '<a href="?page=profile&view={user.id}" class="user user-status-{user.status} user-level-{user.userLevel}" style="color: {user.color};">
            {user.name}
        </a>';

        public $timer = '
            <div class="alert alert-danger game-timer">
                <{text}> <br />
                Time remaining: <span data-remove-when-done data-timer-type="inline" data-timer="{time}"></span>
            </div>
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

?>