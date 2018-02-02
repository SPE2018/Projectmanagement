create database if not exists calendarlist;
use calendarlist;
DROP TABLE if exists `calendarlist`;

CREATE TABLE `calendarlist` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `meetingdate` date default NULL,
  `title` varchar(255) default NULL,
  `timestart` time default NULL,
  `timeend` time default NULL,
  `location` varchar(255) default NULL,
  `description` varchar(255) default NULL,
  PRIMARY KEY (`id`)
) AUTO_INCREMENT = 1;


INSERT INTO `calendarlist` (`meetingdate`, `title`, `timestart`, `timeend`, `location`, `description`) VALUES ("2018-03-10", "meeting1", "13:00:00", "14:00:00", "room1", "we´ll brief project1");
INSERT INTO `calendarlist` (`meetingdate`, `title`, `timestart`, `timeend`, `location`, `description`) VALUES ("2018-07-10", "meeting2", "10:00:00", "12:00:00", "room2", "we´ll brief project2");
INSERT INTO `calendarlist` (`meetingdate`, `title`, `timestart`, `timeend`, `location`, `description`) VALUES ("2018-11-10", "meeting3", "11:00:00", "12:00:00", "room3", "we´ll brief project3");
INSERT INTO `calendarlist` (`meetingdate`, `title`, `timestart`, `timeend`, `location`, `description`) VALUES ("1990-01-01", "meeting should not appear", "13:00:00", "14:00:00", "room1", "we´ll brief project1");
INSERT INTO `calendarlist` (`meetingdate`, `title`, `timestart`, `timeend`, `location`, `description`) VALUES ("2018-02-01", "meeting4", "13:00:00", "14:00:00", "room4", "we´ll brief project4");
INSERT INTO `calendarlist` (`meetingdate`, `title`, `timestart`, `timeend`, `location`, `description`) VALUES ("2018-02-02", "meeting5", "10:00:00", "12:00:00", "room5", "we´ll brief project5");
INSERT INTO `calendarlist` (`meetingdate`, `title`, `timestart`, `timeend`, `location`, `description`) VALUES ("2018-02-03", "meeting6", "11:00:00", "12:00:00", "room6", "we´ll brief project6");
INSERT INTO `calendarlist` (`meetingdate`, `title`, `timestart`, `timeend`, `location`, `description`) VALUES ("2018-02-04", "meeting7", "13:00:00", "14:00:00", "room7", "we´ll brief project7");
INSERT INTO `calendarlist` (`meetingdate`, `title`, `timestart`, `timeend`, `location`, `description`) VALUES ("2018-02-05", "meeting8", "10:00:00", "12:00:00", "room8", "we´ll brief project8");
INSERT INTO `calendarlist` (`meetingdate`, `title`, `timestart`, `timeend`, `location`, `description`) VALUES ("2018-02-06", "meeting9", "11:00:00", "12:00:00", "room9", "we´ll brief project9");

select id as Nummer, meetingdate, title, timestart, timeend, location, description, id from calendarlist order by id asc limit 10 offset 0;
use calendarlist;
show tables;