CREATE TABLE IF NOT EXISTS `cars` (
  `CA_id` int(11) NOT NULL AUTO_INCREMENT,
  `CA_name` varchar(255) NULL,
  `CA_value` int(11) NOT NULL DEFAULT 0,
  `CA_theftChance` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`CA_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `crimes` (
  `C_id` int(11) NOT NULL AUTO_INCREMENT,
  `C_name` varchar(120) NULL,
  `C_cooldown` int(11) NOT NULL DEFAULT 0,
  `C_money` int(11) NOT NULL DEFAULT 0,
  `C_maxMoney` int(11) NOT NULL DEFAULT 0,
  `C_bullets` int(11) NOT NULL DEFAULT 0,
  `C_maxBullets` int(11) NOT NULL DEFAULT 0,
  `C_exp` int(11) NOT NULL DEFAULT 1,
  `C_level` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`C_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `gameNews` (
  `GN_id` int(11) NOT NULL AUTO_INCREMENT,
  `GN_author` int(11) NOT NULL DEFAULT 0,
  `GN_title` varchar(120) NULL,
  `GN_text` text NULL,
  `GN_date` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`GN_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `gangs` (
  `G_id` int(11) NOT NULL AUTO_INCREMENT,
  `G_name` varchar(120) NULL,
  `G_bank` int(11) NOT NULL DEFAULT 0,
  `G_money` int(11) NOT NULL DEFAULT 0,
  `G_bullets` int(11) NOT NULL DEFAULT 0,
  `G_info` text NULL,
  `G_desc` text NULL,
  `G_location` int(11) NOT NULL DEFAULT 0,
  `G_boss` int(11) NOT NULL DEFAULT 0,
  `G_underboss` int(11) NOT NULL DEFAULT 0,
  `G_level` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`G_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `gangPermissions` (
  `GP_id` int(11) NOT NULL AUTO_INCREMENT,
  `GP_user` int(11) NOT NULL,
  `GP_access` varchar(128) NOT NULL,
  PRIMARY KEY (`GP_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `gangInvites` (
  `GI_id` int(11) NOT NULL AUTO_INCREMENT,
  `GI_user` int(11) NOT NULL,
  `GI_gangUser` int(11) NOT NULL,
  `GI_gang` int(11) NOT NULL,
  PRIMARY KEY (`GI_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `gangLogs` (
  `GL_id` int(11) NOT NULL AUTO_INCREMENT,
  `GL_gang` int(11) NOT NULL,
  `GL_time` int(11) NOT NULL,
  `GL_user` int(11) NOT NULL,
  `GL_log` varchar(255) NOT NULL,
  PRIMARY KEY (`GL_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `garage` (
  `GA_id` int(11) NOT NULL AUTO_INCREMENT,
  `GA_uid` int(11) NOT NULL DEFAULT 0,
  `GA_car` int(11) NOT NULL DEFAULT 0,
  `GA_damage` int(11) NOT NULL DEFAULT 0,
  `GA_location` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`GA_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `locations` (
  `L_id` int(11) NOT NULL AUTO_INCREMENT,
  `L_name` varchar(120) NULL,
  `L_cost` int(11) NOT NULL DEFAULT 0,
  `L_bullets` int(11) NOT NULL DEFAULT 0,
  `L_bulletCost` int(11) NOT NULL DEFAULT '100',
  `L_cooldown` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`L_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `mail` (
  `M_id` int(11) NOT NULL AUTO_INCREMENT,
  `M_time` int(11) NOT NULL DEFAULT 0,
  `M_uid` int(11) NOT NULL DEFAULT 0,
  `M_sid` int(11) NOT NULL DEFAULT 0,
  `M_subject` varchar(120) NULL,
  `M_parent` int(11) NOT NULL DEFAULT 0,
  `M_text` text NULL,
  `M_type` int(11) NOT NULL DEFAULT 0,
  `M_read` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`M_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `notifications` (
  `N_id` int(11) NOT NULL AUTO_INCREMENT,
  `N_uid` int(11) NOT NULL DEFAULT 0,
  `N_time` int(11) NOT NULL DEFAULT 0,
  `N_text` text NULL,
  `N_read` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`N_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ranks` (
  `R_id` int(11) NOT NULL AUTO_INCREMENT,
  `R_name` varchar(100) NULL,
  `R_exp` int(11) NOT NULL DEFAULT 0,
  `R_limit` int(11) NOT NULL DEFAULT 0,
  `R_cashReward` int(11) NOT NULL DEFAULT 0,
  `R_health` int(11) NOT NULL DEFAULT 0,
  `R_bulletReward` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`R_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `settings` (
  `S_id` int(11) NOT NULL AUTO_INCREMENT,
  `S_desc` varchar(255) NULL,
  `S_value` text NULL,
  PRIMARY KEY (`S_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `theft` (
  `T_id` int(11) NOT NULL AUTO_INCREMENT,
  `T_name` varchar(255) NULL,
  `T_chance` int(11) NOT NULL DEFAULT 0,
  `T_maxDamage` int(11) NOT NULL DEFAULT 0,
  `T_worstCar` int(11) NOT NULL DEFAULT 0,
  `T_bestCar` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`T_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `users` (
  `U_id` int(11) NOT NULL AUTO_INCREMENT,
  `U_name` varchar(30) NULL,
  `U_email` varchar(100) NULL,
  `U_password` varchar(255) NOT NULL DEFAULT '',
  `U_userLevel` int(1) NULL,
  `U_status` int(1) NULL,
  `U_round` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`U_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `userStats` (
  `US_id` int(11) NOT NULL PRIMARY KEY,
  `US_shotBy` int(11) NOT NULL DEFAULT '0',
  `US_health` int(11) NOT NULL DEFAULT '0',
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
  `US_crimes` varchar(255) NOT NULL DEFAULT '35-25-15-5-5-5-5-5-5-5-5-5-5-5-5'
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `userTimers` (
  `UT_user` int(11) NOT NULL DEFAULT 0,
  `UT_desc` varchar(32) NULL,
  `UT_time` int(11) NOT NULL
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `weapons` (
  `W_id` int(11) NOT NULL AUTO_INCREMENT,
  `W_name` varchar(100) NULL,
  `W_accuracy` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`W_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE `rounds` (
  `R_id` INT(11) AUTO_INCREMENT, 
  `R_name` VARCHAR(128), 
  `R_start` INT(11), 
  `R_end` INT(11), 
  PRIMARY KEY(`R_id`)
);

CREATE TABLE IF NOT EXISTS `roleAccess` ( 
  `RA_role` INT NOT NULL , 
  `RA_module` VARCHAR(128) NOT NULL,
  PRIMARY KEY(`RA_role`, `RA_module`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `userRoles` (
  `UR_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `UR_desc` varchar(128) NULL,
  `UR_color` varchar(7) NOT NULL
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `moneyRanks` ( 
  `MR_id` INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT , 
  `MR_desc` VARCHAR(128), 
  `MR_money` INT(11)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `forums` ( 
  `F_id` INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT , 
  `F_sort` INT(11) NOT NULL DEFAULT 0,
  `F_name` VARCHAR(128)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `forumAccess` ( 
  `FA_role` INT(11), 
  `FA_forum` INT(11)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `topics` ( 
  `T_id` INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT , 
  `T_date` INT(11), 
  `T_forum` INT(11), 
  `T_user` INT(11), 
  `T_subject` VARCHAR(128),
  `T_type` INT(11),
  `T_status` INT(11)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `posts` ( 
  `P_id` INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT , 
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
  `PR_user` int(11) NOT NULL DEFAULT 0,
  `PR_cost` int(11) NOT NULL DEFAULT 0,
  `PR_profit` INT(11) NOT NULL DEFAULT 0
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `items` (
  `I_id` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT , 
  `I_name` VARCHAR(128) NOT NULL ,  
  `I_type` INT(11) NOT NULL DEFAULT 0
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `detectives` (
  `D_id` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT , 
  `D_user` INT(11) NOT NULL DEFAULT 0 , 
  `D_userToFind` INT(11) NOT NULL DEFAULT 0 , 
  `D_detectives` INT(11) NOT NULL DEFAULT 0 , 
  `D_start` INT(11) NOT NULL DEFAULT 0 , 
  `D_end` INT(11) NOT NULL DEFAULT 0 , 
  `D_success` INT(11) NOT NULL DEFAULT 0
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `bounties` (
  `B_id` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT , 
  `B_user` INT(11) NOT NULL DEFAULT 0 , 
  `B_userToKill` INT(11) NOT NULL DEFAULT 0 , 
  `B_cost` INT(11) NOT NULL DEFAULT 0
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `premiumMembership` (
  `PM_id` int(11) NOT NULL AUTO_INCREMENT,
  `PM_desc` varchar(255) NOT NULL,
  `PM_seconds` int(11) NOT NULL,
  `PM_cost` int(11) NOT NULL,
  PRIMARY KEY (`PM_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE `userInventory` (
  `UI_user` INT(11), 
  `UI_item` INT(11), 
  `UI_qty` INT(11), 
  PRIMARY KEY(`UI_user`, `UI_item`)
);

CREATE TABLE `itemEffects` (
  `IE_effect` VARCHAR(32), 
  `IE_item` INT(11), 
  `IE_value` VARCHAR(128), 
  `IE_desc` VARCHAR(128), 
  PRIMARY KEY(`IE_effect`, `IE_item`)
);

CREATE TABLE `itemMeta` (
  `IM_item` INT(11), 
  `IM_meta` VARCHAR(32), 
  `IM_value` TEXT, 
  PRIMARY KEY(`IM_item`, `IM_meta`)
);