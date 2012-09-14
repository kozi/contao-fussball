CREATE TABLE `tl_content` (
  `fussball_wettbewerbs_id` varchar(255) NOT NULL default '',
  `fussball_saison` varchar(255) NOT NULL default '',
  `fussball_positions` text NULL,
  `fussball_results` text NULL,
  `fussball_goalgetter` text NULL,
  `fussball_maxPositions` int(10) unsigned NOT NULL default '0',
  `fussball_team` varchar(255) NOT NULL default '',
  `fussball_filter_team` varchar(255) NOT NULL default '',
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `tl_calendar_events` (
		`fussball_tourn_host` varchar(255) NOT NULL default '',
		`fussball_tourn_location` varchar(255) NOT NULL default '',
		`fussball_tourn_type` varchar(255) NOT NULL default '',
		`fussball_tourn_clients` varchar(255) NOT NULL default '',
		`fussball_tourn_confirmed` char(1) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
