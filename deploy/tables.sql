CREATE TABLE IF NOT EXISTS `weather_days` (
  `date_day` date NOT NULL,
  `temp_low` varchar(6) DEFAULT NULL,
  `temp_high` varchar(6) DEFAULT NULL,
  `noon` varchar(100) DEFAULT NULL,
  `rain` enum('0','1') DEFAULT '0',
  `fog` enum('0','1') DEFAULT '0',
  `snow` enum('0','1') DEFAULT '0',
  `hail` enum('0','1') DEFAULT '0',
  `thunder` enum('0','1') DEFAULT '0',
  `tornado` enum('0','1') DEFAULT '0',
  PRIMARY KEY (`date_day`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Daily weather conditions';

CREATE TABLE IF NOT EXISTS `weather_hours` (
  `date_day` date NOT NULL,
  `date_hour` varchar(2) NOT NULL DEFAULT '0',
  `temp` varchar(6) DEFAULT NULL,
  `weather` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`date_day`,`date_hour`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Hourly weather conditions';

CREATE TABLE IF NOT EXISTS `vacations` (
  `date_start` datetime NOT NULL,
  `date_end` datetime NOT NULL,
  `title` varchar(256) NOT NULL,
  `house_empty` enum('0','1') NOT NULL DEFAULT '1',
  PRIMARY KEY (`date_start`,`date_end`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Time away from the house';