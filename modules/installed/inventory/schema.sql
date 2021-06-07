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