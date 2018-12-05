CREATE TABLE IF NOT EXISTS `cars` (
  `CA_id` int(11) NOT NULL AUTO_INCREMENT,
  `CA_name` varchar(255) NOT NULL,
  `CA_value` int(11) NOT NULL,
  `CA_theftChance` int(11) NOT NULL,
  PRIMARY KEY (`CA_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `crimes` (
  `C_id` int(11) NOT NULL AUTO_INCREMENT,
  `C_name` varchar(120) NOT NULL,
  `C_cooldown` int(11) NOT NULL,
  `C_money` int(11) NOT NULL,
  `C_maxMoney` int(11) NOT NULL,
  `C_level` int(11) NOT NULL,
  PRIMARY KEY (`C_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `gameNews` (
  `GN_id` int(11) NOT NULL AUTO_INCREMENT,
  `GN_author` int(11) NOT NULL,
  `GN_title` varchar(120) NOT NULL,
  `GN_text` text NOT NULL,
  `GN_date` int(11) NOT NULL,
  PRIMARY KEY (`GN_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `gangs` (
  `G_id` int(11) NOT NULL AUTO_INCREMENT,
  `G_name` varchar(120) NOT NULL,
  `G_bank` int(11) NOT NULL DEFAULT 0,
  `G_desc` text NOT NULL DEFAULT '',
  `G_level` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`G_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `garage` (
  `GA_id` int(11) NOT NULL AUTO_INCREMENT,
  `GA_uid` int(11) NOT NULL,
  `GA_car` int(11) NOT NULL,
  `GA_damage` int(11) NOT NULL,
  `GA_location` int(11) NOT NULL,
  PRIMARY KEY (`GA_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `locations` (
  `L_id` int(11) NOT NULL AUTO_INCREMENT,
  `L_name` varchar(120) NOT NULL,
  `L_cost` int(11) NOT NULL,
  `L_bullets` int(11) NOT NULL,
  `L_bulletCost` int(11) NOT NULL DEFAULT '100',
  `L_cooldown` int(11) NOT NULL,
  PRIMARY KEY (`L_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `mail` (
  `M_id` int(11) NOT NULL AUTO_INCREMENT,
  `M_time` int(11) NOT NULL,
  `M_uid` int(11) NOT NULL,
  `M_sid` int(11) NOT NULL,
  `M_subject` varchar(120) NOT NULL,
  `M_parent` int(11) NOT NULL,
  `M_text` text NOT NULL,
  `M_type` int(11) NOT NULL,
  `M_read` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`M_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `notifications` (
  `N_id` int(11) NOT NULL AUTO_INCREMENT,
  `N_uid` int(11) NOT NULL,
  `N_time` int(11) NOT NULL,
  `N_text` text NOT NULL,
  `N_read` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`N_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ranks` (
  `R_id` int(11) NOT NULL AUTO_INCREMENT,
  `R_name` varchar(100) NOT NULL,
  `R_exp` int(11) NOT NULL,
  `R_limit` int(11) NOT NULL,
  `R_cashReward` int(11) NOT NULL,
  `R_health` int(11) NOT NULL,
  `R_bulletReward` int(11) NOT NULL,
  PRIMARY KEY (`R_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `settings` (
  `S_id` int(11) NOT NULL AUTO_INCREMENT,
  `S_desc` varchar(255) NOT NULL,
  `S_value` text NOT NULL,
  PRIMARY KEY (`S_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `theft` (
  `T_id` int(11) NOT NULL AUTO_INCREMENT,
  `T_name` varchar(255) NOT NULL,
  `T_chance` int(11) NOT NULL,
  `T_maxDamage` int(11) NOT NULL,
  `T_worstCar` int(11) NOT NULL,
  `T_bestCar` int(11) NOT NULL,
  PRIMARY KEY (`T_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `users` (
  `U_id` int(11) NOT NULL AUTO_INCREMENT,
  `U_name` varchar(30) NOT NULL,
  `U_email` varchar(100) NOT NULL,
  `U_password` varchar(255) NOT NULL,
  `U_userLevel` int(1) NOT NULL,
  `U_status` int(1) NOT NULL,
  PRIMARY KEY (`U_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `userStats` (
  `US_id` int(11) NOT NULL PRIMARY KEY,
  `US_health` int(11) NOT NULL DEFAULT '100',
  `US_exp` int(11) NOT NULL DEFAULT '0',
  `US_money` int(11) NOT NULL DEFAULT '250',
  `US_bank` int(11) NOT NULL DEFAULT '0',
  `US_bullets` int(11) NOT NULL DEFAULT '100',
  `US_backfire` int(11) NOT NULL DEFAULT '50',
  `US_points` int(11) NOT NULL DEFAULT '0',
  `US_pic` varchar(200) NOT NULL DEFAULT 'themes/default/images/default-profile-picture.png',
  `US_bio` varchar(1000) NOT NULL DEFAULT '0',
  `US_weapon` int(11) NOT NULL DEFAULT '0',
  `US_armor` int(11) NOT NULL DEFAULT '0',
  `US_rank` int(11) NOT NULL DEFAULT '1',
  `US_gang` int(11) NOT NULL DEFAULT '0',
  `US_location` int(11) NOT NULL DEFAULT '1',
  `US_crimes` varchar(255) NOT NULL DEFAULT '35-25-15-5-5-5-5-5-5-5-5-5-5-5-5',
  `US_crimeTimer` int(11) NOT NULL DEFAULT '0',
  `US_jailTimer` int(11) NOT NULL DEFAULT '0',
  `US_theftTimer` int(11) NOT NULL DEFAULT '0',
  `US_chaseTimer` int(11) NOT NULL DEFAULT '0',
  `US_bulletTimer` int(11) NOT NULL DEFAULT '0',
  `US_travelTimer` int(11) NOT NULL DEFAULT '0'
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `userTimers` (
  `UT_user` int(11) NOT NULL,
  `UT_desc` varchar(32) NOT NULL,
  `UT_time` int(11) NOT NULL
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `weapons` (
  `W_id` int(11) NOT NULL AUTO_INCREMENT,
  `W_name` varchar(100) NOT NULL,
  `W_accuracy` int(11) NOT NULL,
  PRIMARY KEY (`W_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `roleAccess` ( 
  `RA_role` INT NOT NULL , 
  `RA_module` VARCHAR(128) NOT NULL,
  PRIMARY KEY(`RA_role`, `RA_module`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `userRoles` (
  `UR_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `UR_desc` varchar(128) NOT NULL,
  `UR_color` varchar(7) NOT NULL
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `moneyRanks` ( 
  `MR_id` INT(11) PRIMARY KEY AUTO_INCREMENT , 
  `MR_desc` VARCHAR(128), 
  `MR_money` INT(11)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `forums` ( 
  `F_id` INT(11) PRIMARY KEY AUTO_INCREMENT , 
  `F_sort` INT(11),
  `F_name` VARCHAR(128)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `forumAccess` ( 
  `FA_role` INT(11), 
  `FA_forum` INT(11)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `topics` ( 
  `T_id` INT(11) PRIMARY KEY AUTO_INCREMENT , 
  `T_date` INT(11), 
  `T_forum` INT(11), 
  `T_user` INT(11), 
  `T_subject` VARCHAR(128),
  `T_type` INT(11),
  `T_status` INT(11)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `posts` ( 
  `P_id` INT(11) PRIMARY KEY AUTO_INCREMENT , 
  `P_topic` INT(11), 
  `P_date` INT(11), 
  `P_user` INT(11), 
  `P_body` TEXT
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `topicReads` ( 
  `TR_topic` INT(11), 
  `TR_user` INT(11)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `properties` ( 
  `PR_id` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT , 
  `PR_location` INT(11) NOT NULL , 
  `PR_module` VARCHAR(128) NOT NULL , 
  `PR_user` INT(11) NOT NULL,
  `PR_cost` INT(11) NOT NULL,
  `PR_profit` INT(11) NOT NULL
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `items` (
  `I_id` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT , 
  `I_name` VARCHAR(128) NOT NULL , 
  `I_damage` INT(11) NOT NULL , 
  `I_cost` INT(11) NOT NULL , 
  `I_points` INT(11) NOT NULL , 
  `I_type` INT(11) NOT NULL , 
  `I_rank` INT(11) NOT NULL
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `detectives` (
  `D_id` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT , 
  `D_user` INT(11) NOT NULL , 
  `D_userToFind` INT(11) NOT NULL , 
  `D_detectives` INT(11) NOT NULL , 
  `D_start` INT(11) NOT NULL , 
  `D_end` INT(11) NOT NULL , 
  `D_success` INT(11) NOT NULL
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `bounties` (
  `B_id` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT , 
  `B_user` INT(11) NOT NULL , 
  `B_userToKill` INT(11) NOT NULL , 
  `B_cost` INT(11) NOT NULL
) ENGINE = InnoDB;

