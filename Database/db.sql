
--
-- Table structure for table `expenditures`
--

CREATE TABLE IF NOT EXISTS `expenditures` (
  `id_expenditure` int(11) NOT NULL AUTO_INCREMENT,
  `water_source_id` int(11) NOT NULL,
  `repair_type_id` int(11) NOT NULL,
  `expenditure_date` datetime NOT NULL,
  `expenditure_cost` float NOT NULL,
  `benefactor` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `logged_by` int(11) NOT NULL,
  `date_logged` datetime NOT NULL,
  PRIMARY KEY (`id_expenditure`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `push_messages`
--

CREATE TABLE IF NOT EXISTS `push_messages` (
  `id_sms` bigint(255) NOT NULL AUTO_INCREMENT,
  `message_content` text NOT NULL,
  `system_users` text NOT NULL,
  `water_users` text NOT NULL,
  `date_sent` datetime NOT NULL,
  `sent` tinyint(1) NOT NULL DEFAULT '0',
  `sent_by` int(11) NOT NULL,
  PRIMARY KEY (`id_sms`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `repair_types`
--

CREATE TABLE IF NOT EXISTS `repair_types` (
  `id_repair_type` int(11) NOT NULL AUTO_INCREMENT,
  `repair_type` text NOT NULL,
  `added_by` int(11) NOT NULL,
  `date_added` datetime NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_repair_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE IF NOT EXISTS `sales` (
  `id_sale` bigint(20) NOT NULL AUTO_INCREMENT,
  `water_source_id` bigint(20) NOT NULL,
  `sold_by` int(11) NOT NULL,
  `sold_to` int(11) NOT NULL,
  `sale_ugx` float NOT NULL,
  `sale_date` datetime NOT NULL,
  `percentage_saved` float NOT NULL,
  `submitted_to_treasurer` tinyint(1) NOT NULL DEFAULT '0',
  `submitted_by` int(11) NOT NULL,
  `submittion_to_treasurer_date` datetime NOT NULL,
  `treasurerer_approval_status` tinyint(1) NOT NULL,
  `reviewed_by` int(11) NOT NULL,
  `date_reviewed` datetime NOT NULL,
  PRIMARY KEY (`id_sale`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `id_system` int(11) NOT NULL AUTO_INCREMENT,
  `robots` varchar(25) NOT NULL,
  `site_desc` text NOT NULL,
  `site_keywords` text NOT NULL,
  `system_name` varchar(25) NOT NULL,
  `system_status` tinyint(1) NOT NULL DEFAULT '0',
  `enable_water_user_registrations` tinyint(1) NOT NULL DEFAULT '0',
  `default_locale_coordinates` text NOT NULL,
  `account_created_email_template` text NOT NULL,
  `account_created_sms_template` text NOT NULL,
  `recovery_email_template` text NOT NULL,
  `recovery_sms_template` text NOT NULL,
  `funds_accountability_email_template` text NOT NULL,
  `funds_accountability_sms_template` text NOT NULL,
  `enable_emails` tinyint(1) NOT NULL DEFAULT '0',
  `enable_sms` tinyint(1) NOT NULL DEFAULT '0',
  `sms_api_username` text NOT NULL,
  `sms_api_key` text NOT NULL,
  `enable_acountablility_sms` tinyint(1) NOT NULL DEFAULT '0',
  `acountablility_cycle` int(4) NOT NULL DEFAULT '30',
  `batch_schedule_date` datetime NOT NULL,
  `acountablility_recipients` varchar(15) NOT NULL DEFAULT 'all',
  `last_day_acountability_sms_was_sent` datetime NOT NULL,
  `enable_push_notifications` tinyint(1) NOT NULL DEFAULT '0',
  `google_api_key` text NOT NULL,
  PRIMARY KEY (`id_system`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id_system`, `robots`, `site_desc`, `site_keywords`, `system_name`, `system_status`, `enable_water_user_registrations`, `default_locale_coordinates`, `account_created_email_template`, `account_created_sms_template`, `recovery_email_template`, `recovery_sms_template`, `funds_accountability_email_template`, `funds_accountability_sms_template`, `enable_emails`, `enable_sms`, `sms_api_username`, `sms_api_key`, `enable_acountablility_sms`, `acountablility_cycle`, `batch_schedule_date`, `acountablility_recipients`, `last_day_acountability_sms_was_sent`, `enable_push_notifications`, `google_api_key`) VALUES
(1, 'index-follow', 'The PM4W project aims to improve water and sanitation in rural parts of Uganda by enabling access to information by various stakeholders and players in the Water and Sanitation sector. Using mobile phones baseline and inspection information is collected about each water point using the Ministry of Water and Environment guidelines and standards.', 'water and sanitation app,water mobile app,sanitation mobile app,water and sanitation mobile app,water app,sanitation app,water sales app, manage water app,manage sales,clear water app,water app uganda,sanitation app uganda,android water app,water and sanitation mobile app', 'PM4W', 1, 1, '0.6600, 30.2750', 'Your account at {$system_name} has been successfully created. <br/>\r\nPlease login at <a href="{$site_url}">{$site_url}</a>.<br/>\r\nLog in with your username: <strong>{$username}</strong><br/>\r\nand your password: <strong>{$password}</strong>', 'Your account at {$system_name} has been successfully created.\r\nLog in with your username {$username}\r\nYour password is {$password}', 'Your {$system_name} password has been reset. <br/>\r\nYour new password is: <strong>{$password}</strong>', 'Your {$system_name} password has been reset.\r\nYour new password is {$password}.', 'This is an accountability report from {$system_name} for {$water_source_name} for the last {$acountablility_cycle} days\r\nTotal Sales: <strong>{$total_savings}</strong><br/>\r\nTotal Expenditures: <strong>{$total_expenditures}</strong><br/>\r\nTotal Savings: <strong>{$total_savings}</strong>', 'This is an accountability report from {$system_name} for {$water_source_name} for the last {$acountablility_cycle} days\r\nTotal Sales: {$total_savings}\r\nTotal Expenditures: {$total_expenditures}\r\nTotal Savings: {$total_savings}', 1, 1, 'sukavi', '9376f1aa65c5ebdcafd1b33d8fe93515285d2c978c390206efce9eb19a22397f', 1, 30, '2015-02-15 09:00:00', 'all', '2015-02-15 21:00:13', 0, 'AIzaSyAWbniCEyoYyRVUhvEE8LoDkjtvPSnsuWQ');

-- --------------------------------------------------------

--
-- Table structure for table `sms_messages`
--

CREATE TABLE IF NOT EXISTS `sms_messages` (
  `id_sms` bigint(255) NOT NULL AUTO_INCREMENT,
  `message_content` text NOT NULL,
  `system_users` text NOT NULL,
  `water_users` text NOT NULL,
  `date_sent` datetime NOT NULL,
  `sent` tinyint(1) NOT NULL DEFAULT '0',
  `sent_by` int(11) NOT NULL,
  PRIMARY KEY (`id_sms`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `idu` int(11) NOT NULL AUTO_INCREMENT,
  `gcm_regid` text NOT NULL,
  `group_id` tinyint(4) NOT NULL,
  `username` varchar(25) NOT NULL,
  `pnumber` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL,
  `fname` varchar(25) NOT NULL,
  `lname` varchar(25) NOT NULL,
  `password` varchar(40) NOT NULL,
  `date_added` datetime NOT NULL,
  `last_login` datetime NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idu`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`idu`, `gcm_regid`, `group_id`, `username`, `pnumber`, `email`, `fname`, `lname`, `password`, `date_added`, `last_login`, `active`) VALUES
(1, '', 1, 'admin', '0779079661', 'sammyukavi@gmail.com', 'System', 'Admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', '2015-02-13 19:43:00', '2015-02-16 18:21:22', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_groups`
--

CREATE TABLE IF NOT EXISTS `user_groups` (
  `id_group` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(150) NOT NULL,
  `group_is_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `can_access_system_config` tinyint(1) NOT NULL DEFAULT '0',
  `can_receive_emails` tinyint(1) NOT NULL DEFAULT '0',
  `can_access_app` tinyint(1) NOT NULL DEFAULT '0',
  `can_send_sms` tinyint(1) NOT NULL DEFAULT '0',
  `can_receive_push_notifications` tinyint(1) NOT NULL DEFAULT '0',
  `can_submit_attendant_daily_sales` tinyint(1) NOT NULL DEFAULT '0',
  `can_approve_attendants_submissions` tinyint(1) NOT NULL DEFAULT '0',
  `can_approve_treasurers_submissions` tinyint(1) NOT NULL DEFAULT '0',
  `can_cancel_attendant_daily_sales` tinyint(1) NOT NULL DEFAULT '0',
  `can_cancel_attendants_submissions` tinyint(1) NOT NULL DEFAULT '0',
  `can_cancel_treasurers_submissions` tinyint(1) NOT NULL DEFAULT '0',
  `can_add_water_users` tinyint(1) NOT NULL DEFAULT '0',
  `can_edit_water_users` tinyint(1) NOT NULL DEFAULT '0',
  `can_delete_water_users` tinyint(1) NOT NULL DEFAULT '0',
  `can_view_water_users` tinyint(1) NOT NULL DEFAULT '0',
  `can_add_sales` tinyint(1) NOT NULL DEFAULT '0',
  `can_edit_sales` tinyint(1) NOT NULL DEFAULT '0',
  `can_delete_sales` tinyint(1) NOT NULL DEFAULT '0',
  `can_view_sales` tinyint(1) NOT NULL DEFAULT '0',
  `can_view_personal_savings` tinyint(1) NOT NULL DEFAULT '0',
  `can_view_water_source_savings` tinyint(1) NOT NULL DEFAULT '0',
  `can_add_water_sources` tinyint(1) NOT NULL DEFAULT '0',
  `can_edit_water_sources` tinyint(1) NOT NULL DEFAULT '0',
  `can_delete_water_sources` tinyint(1) NOT NULL DEFAULT '0',
  `can_view_water_sources` tinyint(1) NOT NULL DEFAULT '0',
  `can_add_repair_types` tinyint(1) NOT NULL DEFAULT '0',
  `can_edit_repair_types` tinyint(1) NOT NULL DEFAULT '0',
  `can_delete_repair_types` tinyint(1) NOT NULL DEFAULT '0',
  `can_view_repair_types` tinyint(1) NOT NULL DEFAULT '0',
  `can_add_expenses` tinyint(1) NOT NULL DEFAULT '0',
  `can_edit_expenses` tinyint(1) NOT NULL DEFAULT '0',
  `can_delete_expenses` tinyint(1) NOT NULL DEFAULT '0',
  `can_view_expenses` tinyint(1) NOT NULL DEFAULT '0',
  `can_add_system_users` tinyint(1) NOT NULL DEFAULT '0',
  `can_edit_system_users` tinyint(1) NOT NULL DEFAULT '0',
  `can_delete_system_users` tinyint(1) DEFAULT '0',
  `can_view_system_users` tinyint(1) NOT NULL DEFAULT '0',
  `can_add_user_permissions` tinyint(1) NOT NULL DEFAULT '0',
  `can_edit_user_permissions` tinyint(1) NOT NULL DEFAULT '0',
  `can_delete_user_permissions` tinyint(1) NOT NULL DEFAULT '0',
  `can_view_user_permissions` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_group`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `user_groups`
--

INSERT INTO `user_groups` (`id_group`, `group_name`, `group_is_enabled`, `can_access_system_config`, `can_receive_emails`, `can_access_app`, `can_send_sms`, `can_receive_push_notifications`, `can_submit_attendant_daily_sales`, `can_approve_attendants_submissions`, `can_approve_treasurers_submissions`, `can_cancel_attendant_daily_sales`, `can_cancel_attendants_submissions`, `can_cancel_treasurers_submissions`, `can_add_water_users`, `can_edit_water_users`, `can_delete_water_users`, `can_view_water_users`, `can_add_sales`, `can_edit_sales`, `can_delete_sales`, `can_view_sales`, `can_view_personal_savings`, `can_view_water_source_savings`, `can_add_water_sources`, `can_edit_water_sources`, `can_delete_water_sources`, `can_view_water_sources`, `can_add_repair_types`, `can_edit_repair_types`, `can_delete_repair_types`, `can_view_repair_types`, `can_add_expenses`, `can_edit_expenses`, `can_delete_expenses`, `can_view_expenses`, `can_add_system_users`, `can_edit_system_users`, `can_delete_system_users`, `can_view_system_users`, `can_add_user_permissions`, `can_edit_user_permissions`, `can_delete_user_permissions`, `can_view_user_permissions`) VALUES
(1, 'System Admin', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `water_sources`
--

CREATE TABLE IF NOT EXISTS `water_sources` (
  `id_water_source` int(11) NOT NULL AUTO_INCREMENT,
  `water_source_id` text NOT NULL,
  `water_source_name` text NOT NULL,
  `water_source_location` text NOT NULL,
  `water_source_coordinates` text NOT NULL,
  `monthly_charges` float NOT NULL DEFAULT '0',
  `percentage_saved` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_water_source`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `water_source_caretakers`
--

CREATE TABLE IF NOT EXISTS `water_source_caretakers` (
  `id_attendant` bigint(255) NOT NULL AUTO_INCREMENT,
  `water_source_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  PRIMARY KEY (`id_attendant`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `water_source_treasurers`
--

CREATE TABLE IF NOT EXISTS `water_source_treasurers` (
  `id_treasurer` bigint(255) NOT NULL AUTO_INCREMENT,
  `water_source_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  PRIMARY KEY (`id_treasurer`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `water_users`
--

CREATE TABLE IF NOT EXISTS `water_users` (
  `id_user` bigint(255) NOT NULL AUTO_INCREMENT,
  `fname` varchar(25) NOT NULL,
  `lname` varchar(25) NOT NULL,
  `pnumber` varchar(25) NOT NULL,
  `date_added` datetime NOT NULL,
  `added_by` int(255) NOT NULL,
  `reported_defaulter` tinyint(1) NOT NULL,
  `marked_for_delete` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
