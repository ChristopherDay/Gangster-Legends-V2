--
-- Dumping data for table `cars`
--

INSERT INTO `cars` (`CA_id`, `CA_name`, `CA_value`, `CA_theftChance`) VALUES
(1, 'Peugeot 106', 400, 333),
(2, 'Citroen Saxo', 500, 333),
(3, 'Ford Fiesta', 600, 333),
(4, 'VW Golf', 1500, 250),
(5, 'Audi A3', 3200, 150),
(6, 'BMW 5 Series', 16000, 75),
(7, 'Porsche 911', 45000, 15),
(8, 'Ferrari California', 90000, 5);

--
-- Dumping data for table `crimes`
--

INSERT INTO `crimes` (`C_id`, `C_name`, `C_cooldown`, `C_money`, `C_maxMoney`, `C_level`) VALUES
(1, 'Mug an old lady', 20, 1, 5, 1),
(2, 'Rob a cab driver', 45, 10, 18, 1);

--
-- Dumping data for table `gameNews`
--

INSERT INTO `gameNews` (`GN_id`, `GN_author`, `GN_title`, `GN_text`, `GN_date`) VALUES
(1, 1, 'Some Title 1', 'edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v ', 1388562841),
(2, 1, 'Some Title 2', 'edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v ', 1388562801),
(3, 1, 'Some Title 3', 'edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v ', 1388562741),
(4, 1, 'Some Title 4', 'edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v ', 1388562701),
(5, 1, 'Some Title 5', 'edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v ', 1388562641),
(6, 1, 'Some Title 6', 'edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v edrjkkjlhvghbkj bkxj bvkjzxcb vkjzxb v ', 1388562601);


--
-- Dumping data for table `garage`
--

INSERT INTO `garage` (`GA_id`, `GA_uid`, `GA_car`, `GA_damage`, `GA_location`) VALUES
(23, 1, 4, 0, 3),
(22, 3, 4, 2, 1);

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`L_id`, `L_name`, `L_cost`, `L_bullets`, `L_bulletCost`, `L_cooldown`) VALUES
(1, 'London', 170, 11977, 100, 3600),
(3, 'Rome', 220, 0, 100, 4800),
(2, 'Paris', 200, 0, 100, 4200);

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`N_id`, `N_uid`, `N_text`, `N_read`) VALUES
(1, 1, 'dsfdsasdsadsds', 0);

--
-- Dumping data for table `ranks`
--

INSERT INTO `ranks` (`R_id`, `R_name`, `R_exp`, `R_limit`, `R_cashReward`, `R_bulletReward`) VALUES
(1, 'Lowlife', 0, 0, 75, 25),
(2, 'Thug', 50, 0, 150, 60),
(3, 'Criminal', 100, 0, 250, 100);

--
-- Dumping data for table `theft`
--

INSERT INTO `theft` (`T_id`, `T_name`, `T_chance`, `T_maxDamage`, `T_worstCar`, `T_bestCar`) VALUES
(1, 'Steal from street corner', 50, 100, 1, 3),
(2, 'Steel from 24hour car park', 35, 75, 1, 3),
(3, 'Steal from private car park', 25, 60, 1, 4),
(4, 'Steal from golf course', 18, 30, 3, 6),
(5, 'Steal from car dearlership', 10, 10, 4, 7);

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`U_id`, `U_name`, `U_email`, `U_password`, `U_userLevel`, `U_status`) VALUES
(1, 'Admin', 'Admin@yourgame.com', '0f4afdf3a12e95916d9750debbcff3999a502aa9', 2, 1);
--

INSERT INTO `userStats` (`US_id`) VALUES (1);

--
-- Dumping data for table `weapons`
--

INSERT INTO `weapons` (`W_id`, `W_name`, `W_accuracy`) VALUES
(1, 'Pistol', 5);
