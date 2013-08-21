CREATE TABLE IF NOT EXISTS `#__email_alert` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `alert_id` int(11) NOT NULL,
  `option` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `plugins_subscribed_to` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `#__email_alert` CHANGE `plugins_subscribed_to` `plugins_subscribed_to` TEXT NOT NULL ;

CREATE TABLE IF NOT EXISTS `#__email_alert_type` (
  `id` int(11) NOT NULL auto_increment,
  `alert_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `allow_user_select_plugin` tinyint(1) NOT NULL,
  `allowed_freq` varchar(255) NOT NULL,
  `default_freq` tinyint(1) NOT NULL,
  `published` tinyint(1) NOT NULL,
  `isdefault` tinyint(1) NOT NULL,
  `email_subject` varchar(255) NOT NULL,
  `template` text NOT NULL,
  `template_css` text NOT NULL,
  `respect_last_email_date` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
