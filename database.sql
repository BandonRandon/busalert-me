
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
-- --------------------------------------------------------

--
-- Table structure for table `bus_data`
--

CREATE TABLE IF NOT EXISTS `bus_data` (
  `id` bigint(20) NOT NULL auto_increment,
  `twil_id` varchar(255) NOT NULL,
  `sms_from` bigint(15) NOT NULL,
  `stop_number` int(11) NOT NULL,
  `route_number` int(11) NOT NULL,
  `alert_time` tinyint(2) unsigned zerofill default NULL,
  `alert_after_time` bigint(20) NOT NULL,
  `alert_type` tinyint(4) NOT NULL,
  `alert_status` tinyint(1) NOT NULL default '0',
  `date` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `bus_data`
--

