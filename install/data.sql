INSERT INTO `cars` (`CA_id`, `CA_name`, `CA_value`, `CA_theftChance`) VALUES
(1, 'Peugeot 106', 400, 333),
(2, 'Citroen Saxo', 500, 333),
(3, 'Ford Fiesta', 600, 333),
(4, 'VW Golf', 1500, 250),
(5, 'Audi A3', 3200, 150),
(6, 'BMW 5 Series', 16000, 75),
(7, 'Porsche 911', 45000, 15),
(8, 'Ferrari California', 90000, 5);

INSERT INTO `crimes` (`C_id`, `C_name`, `C_cooldown`, `C_money`, `C_maxMoney`, `C_level`) VALUES
(1, 'Mug an old lady', 20, 1, 5, 1),
(2, 'Rob a cab driver', 45, 10, 18, 1);

INSERT INTO `gameNews` (`GN_id`, `GN_author`, `GN_title`, `GN_text`, `GN_date`) VALUES
(1, 1, 'Instalation Complete', 'GL v2 successfully installed', UNIX_TIMESTAMP());

INSERT INTO `garage` (`GA_id`, `GA_uid`, `GA_car`, `GA_damage`, `GA_location`) VALUES
(23, 1, 4, 0, 3),
(22, 3, 4, 2, 1);

INSERT INTO `locations` (`L_id`, `L_name`, `L_cost`, `L_bullets`, `L_bulletCost`, `L_cooldown`) VALUES
(1, 'London', 170, 11977, 100, 3600),
(3, 'Rome', 220, 0, 100, 4800),
(2, 'Paris', 200, 0, 100, 4200);

INSERT INTO `notifications` (`N_time`, `N_id`, `N_uid`, `N_text`, `N_read`) VALUES
(UNIX_TIMESTAMP(), 1, 1, 'GL V2 installed successfully', 0);

INSERT INTO `ranks` (`R_id`, `R_name`, `R_exp`, `R_limit`, `R_cashReward`, `R_bulletReward`, `R_health`) VALUES
(1, 'Lowlife', 0, 0, 75, 25, 5000),
(2, 'Thug', 50, 0, 150, 60, 10000),
(3, 'Criminal', 100, 0, 250, 100, 15000);

INSERT INTO `rounds` VALUES (NULL, "Round 1", UNIX_TIMESTAMP() - UNIX_TIMESTAMP() % 86400, UNIX_TIMESTAMP() - UNIX_TIMESTAMP() % 86400 + (60 * 86400));

INSERT INTO `theft` (`T_id`, `T_name`, `T_chance`, `T_maxDamage`, `T_worstCar`, `T_bestCar`) VALUES
(1, 'Steal from street corner', 50, 100, 1, 1000),
(2, 'Steel from 24hour car park', 35, 75, 1, 1000),
(3, 'Steal from private car park', 25, 60, 1, 2000),
(4, 'Steal from golf course', 18, 30, 500, 20000),
(5, 'Steal from car dearlership', 10, 10, 1000, 50000);

INSERT INTO `settings` (`S_desc`, `S_value`) VALUES
('pointsName', 'Points'),
('detectiveReport', '2'),
('gangName', 'Gang');

INSERT INTO `weapons` (`W_id`, `W_name`, `W_accuracy`) VALUES
(1, 'Pistol', 5);

INSERT INTO `userRoles` (`UR_id`, `UR_desc`, `UR_color`) VALUES
(1, 'User', '#777777'),
(2, 'Admin', '#FFFFFF'),
(3, 'Banned', '#FF0000');

INSERT INTO `roleAccess` (`RA_role`, `RA_module`) VALUES (2, '*');

INSERT INTO `moneyRanks` (`MR_id`, `MR_desc`, `MR_money`) VALUES
(1, "Broke", 0),
(2, "Very Poor", 10000),
(3, "Poor", 100000),
(4, "Rich", 1000000),
(5, "Very Rich", 10000000);
