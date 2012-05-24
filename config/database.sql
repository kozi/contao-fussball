CREATE TABLE `tl_content` (
  `fussball_wettbewerbs_id` varchar(255) NOT NULL default '',
  `fussball_saison` varchar(255) NOT NULL default '',
  `fussball_positions` text NULL,
  `fussball_maxPositions` int(10) unsigned NOT NULL default '0',
  `fussball_team` varchar(255) NOT NULL default '',  
) ENGINE=MyISAM DEFAULT CHARSET=utf8;