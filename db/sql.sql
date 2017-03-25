--
-- Database: `eyeeza_pm4w`
--

-- --------------------------------------------------------

--
-- Table structure for table `app_builds`
--

CREATE TABLE `app_builds` (
  `id_build` int(11) NOT NULL,
  `build_name` varchar(256) NOT NULL,
  `build_version` varchar(256) NOT NULL,
  `compatible_devices` text NOT NULL,
  `build_features` longtext NOT NULL,
  `build_date` datetime NOT NULL,
  `preferred` tinyint(1) NOT NULL,
  `is_stable` tinyint(1) NOT NULL,
  `file_id` int(11) NOT NULL,
  `uploaded_by` int(11) NOT NULL,
  `date_uploaded` datetime NOT NULL,
  `published` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `app_user_sessions`
--

CREATE TABLE `app_user_sessions` (
  `id_pass` bigint(255) NOT NULL,
  `uid` bigint(255) NOT NULL,
  `auth_code` varchar(40) NOT NULL,
  `auth_key` varchar(40) NOT NULL,
  `expires` datetime NOT NULL,
  `last_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `event_logs`
--

CREATE TABLE `event_logs` (
  `id_event` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `event` text NOT NULL,
  `event_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `event_description` text NOT NULL,
  `affected_object_id` int(11) NOT NULL,
  `system_used` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `expenditures`
--

CREATE TABLE `expenditures` (
  `id_expenditure` int(11) NOT NULL,
  `water_source_id` int(11) NOT NULL,
  `repair_type_id` int(11) NOT NULL,
  `expenditure_date` datetime NOT NULL,
  `expenditure_cost` float NOT NULL,
  `benefactor` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `logged_by` int(11) NOT NULL,
  `marked_for_delete` tinyint(1) NOT NULL DEFAULT '0',
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id_file` bigint(255) NOT NULL,
  `file_name` varchar(256) NOT NULL,
  `file_mime` varchar(256) NOT NULL,
  `file_size_bytes` bigint(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `date_added` datetime NOT NULL,
  `privacy` tinyint(4) NOT NULL,
  `owner` bigint(255) NOT NULL,
  `added_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `repair_types`
--

CREATE TABLE `repair_types` (
  `id_repair_type` int(11) NOT NULL,
  `repair_type` text NOT NULL,
  `added_by` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id_sale` bigint(20) NOT NULL,
  `water_source_id` bigint(20) NOT NULL,
  `sold_by` int(11) NOT NULL,
  `sold_to` int(11) NOT NULL,
  `sale_ugx` float NOT NULL,
  `sale_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `percentage_saved` float NOT NULL,
  `submitted_to_treasurer` tinyint(1) NOT NULL DEFAULT '0',
  `submitted_by` int(11) NOT NULL,
  `submittion_to_treasurer_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `treasurerer_approval_status` tinyint(1) NOT NULL,
  `reviewed_by` int(11) NOT NULL,
  `date_reviewed` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `marked_for_delete` tinyint(1) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id_system` int(11) NOT NULL,
  `config` longtext CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `sms_messages`
--

CREATE TABLE `sms_messages` (
  `id_msg` bigint(255) NOT NULL,
  `label` varchar(10) NOT NULL DEFAULT 'outbox',
  `type` varchar(25) NOT NULL,
  `created_by` int(11) NOT NULL,
  `received` tinyint(4) NOT NULL,
  `seen` tinyint(1) NOT NULL DEFAULT '0',
  `can_be_sent` tinyint(1) NOT NULL DEFAULT '0',
  `scheduled_send_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `message_content` text NOT NULL,
  `service_center` varchar(25) NOT NULL,
  `sent` tinyint(1) NOT NULL DEFAULT '0',
  `date_sent` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sms_messages_recipients`
--

CREATE TABLE `sms_messages_recipients` (
  `id_recepient` int(11) NOT NULL,
  `msg_id` int(11) DEFAULT '0',
  `idu` int(11) DEFAULT NULL,
  `id_user` int(11) NOT NULL,
  `account_type` text NOT NULL,
  `pnumber` varchar(25) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `idu` int(11) NOT NULL,
  `group_id` tinyint(4) NOT NULL,
  `username` varchar(25) NOT NULL,
  `pnumber` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL,
  `fname` varchar(25) NOT NULL,
  `lname` varchar(25) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_login` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `app_version_in_use` varchar(25) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `app_preferred_language` varchar(25) NOT NULL,
  `last_updated` datetime DEFAULT '0000-00-00 00:00:00',
  `device_imei` varchar(50) DEFAULT NULL,
  `last_known_location` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`idu`, `group_id`, `username`, `pnumber`, `email`, `fname`, `lname`, `date_added`, `last_login`, `app_version_in_use`, `active`, `app_preferred_language`, `last_updated`, `device_imei`, `last_known_location`) VALUES
(3, 2, 'buhct1', '0779079690', '', 'Wilson', 'Kutambaki', '2015-02-13 19:52:01', '2016-01-02 12:16:48', 'Unknown', 1, '', '2016-03-18 06:43:16', '865244022373920', '0.5585585585585585,30.18161435200745'),
(5, 2, 'kasct1', '0779080175', '', 'Judith', 'Kobusingye', '2015-02-13 19:57:46', '2015-10-21 17:49:05', '1.1062', 1, '', '2016-03-18 06:35:02', '', ''),
(9, 5, 'buhwb', '0779079671', '', 'Sylvia', 'Kemigisa', '2015-02-14 05:27:52', '2016-01-15 22:46:30', 'Unknown', 1, '', '2016-03-18 06:43:01', '865244022429425', '0.5765765765765766,30.199727305937298'),
(11, 4, 'buhwt', '0781959208', '', 'Sarah', 'Karatunga', '2015-02-14 05:33:49', '2016-02-14 20:25:20', 'Unknown', 1, '', '2016-03-18 06:41:58', '865244022429219', '0.6666666666666666,30.272319464005538'),
(12, 1, 'admin', '256772426538', '', 'Fiona', 'Mugarura', '0000-00-00 00:00:00', '2016-03-17 17:49:41', 'Unknown', 1, '', '2016-03-18 06:32:03', '864222027142475', '-33.96396396396396,18.465778128137558'),
(14, 2, 'buhct2', '0779079661', '', 'Boaz', 'Niwagaba', '2015-03-14 12:16:03', '2016-02-05 12:27:23', '1.1062', 1, '', '2016-03-18 06:26:26', '865244022370157', '0.6126126126126126,30.21794347485878'),
(15, 1, 'sukavi', '0784609616', 'sammyukavi@gmail.com', 'Sammy', 'Ukavi', '2015-03-14 12:19:06', '2016-05-11 23:29:20', '1.1063', 1, '', '2016-03-29 19:29:19', '076821464647861', ''),
(16, 7, 'kbdwo', '256782451886', 'mugpius77@yahoo.com', 'Pius', 'Mugabi', '2015-03-20 15:13:11', '2015-04-21 14:05:19', '1.105', 1, '', '0000-00-00 00:00:00', '', ''),
(17, 2, 'rbnct1', '0779015812', '', 'Kenneth', 'Tumwebaze', '2015-08-03 13:49:43', '2015-12-08 20:00:56', 'Unknown', 1, '', '2016-03-18 06:37:00', '867014020517994', '0.5585585585585585,30.18161435200745'),
(18, 2, 'rbnct2', '0779015769', '', 'Robert', 'Akugizibwe', '2015-08-03 13:53:08', '2016-02-09 10:07:52', 'Unknown', 1, '', '2016-03-18 06:40:57', '867014020467034', '0.5585585585585585,30.18161435200745'),
(19, 2, 'rbnct3', '0779015843', '', 'Selvester', 'Ahaisibwe', '2015-08-03 13:55:59', '2015-09-15 16:34:14', '1.1062', 1, '', '2016-03-18 06:42:23', '', ''),
(20, 2, 'rbnct4', '0779015942', '', 'Immaculate', 'Friday', '2015-08-03 13:57:12', '2015-12-10 08:48:28', 'Unknown', 1, '', '2016-03-18 06:33:31', '867014020467521', '0.5765765765765766,31.388976710586377'),
(21, 8, 'dummy', '0724116972', 'test@eyeeza.com', 'Device Number Sammsy', '20', '2015-08-04 00:46:37', '2016-05-11 23:35:00', '1.1063', 1, '', '2016-05-10 12:29:48', '867014020459692', '0.36036036036036034,32.54118416225175'),
(23, 5, 'kaswb', '0779080213', '', 'Clever', 'Frugence', '2015-08-06 22:16:16', '2016-01-12 08:14:51', '1.1062', 1, '', '2016-03-18 06:26:53', '', ''),
(24, 2, 'buhct3', '0779016046', '', 'Clovice', 'Kagaba', '2015-08-06 22:20:04', '2015-12-10 19:19:18', '1.1062', 1, '', '2016-03-18 06:30:01', '867014020570258', '0.5585585585585585,30.18161435200745'),
(25, 2, 'buhct4', '0784295397', '', 'Rose', 'Komuhangi', '2015-08-06 22:23:30', '2015-10-17 14:22:30', 'Unknown', 1, '', '2016-03-18 06:41:37', '867014020467679', '0.5585585585585585,30.18161435200745'),
(26, 2, 'buhct5', '0779015916', '', 'Frank', 'Agaba', '2015-08-06 22:31:50', '2016-03-18 07:07:24', '1.105', 1, '', '2016-03-18 06:32:28', '', ''),
(27, 2, 'buhct6', '0779016043', '', 'Rodger', 'Kankya', '2015-08-06 22:40:22', '2015-12-07 12:22:24', 'Unknown', 1, '', '2016-03-18 06:41:16', '867014020467547', '0.5765765765765766,30.181708375563826'),
(28, 2, 'buhct7', '0779015832', '', 'Robert', 'Kusemererwa', '2015-08-06 22:42:16', '2015-08-22 06:01:35', '1.1062', 1, '', '2016-03-18 06:40:25', '', ''),
(29, 2, 'kasct2', '0779016013', '', 'Moses', 'Mwesige', '2015-08-06 22:45:24', '2015-12-09 11:15:59', '1.1062', 1, '', '2016-03-18 06:38:26', '', ''),
(30, 2, 'kasct3', '0779016028', '', 'Julius', 'Tumwesigye', '2015-08-06 22:47:59', '2015-08-27 16:16:30', '1.1062', 1, '', '2016-03-18 06:36:00', '867014020467133', '0.48648648648648646,30.289380115911765'),
(31, 2, 'kasct4', '0779015946', '', 'Deus', 'Katuramu', '2015-08-06 23:14:14', '2016-03-11 11:10:38', 'Unknown', 1, '', '2016-03-18 06:30:28', '867014020455518', '0.48648648648648646,30.289380115911765'),
(32, 2, 'kasct5', '0779015822', '', 'Justus', 'Man', '2015-08-06 23:17:16', '2015-10-03 12:23:00', '1.1062', 1, '', '2016-03-18 06:36:33', '867014020570316', ''),
(33, 2, 'kasct6', '0784295415', '', 'Kurineri', 'Byomuhangi', '2015-08-06 23:18:57', '2015-12-12 17:15:10', 'Unknown', 1, '', '2016-03-18 06:37:34', '', ''),
(34, 5, 'rbnwb', '0780291859', '', 'Fred', 'Ojambo', '2015-08-06 23:39:57', '2015-10-09 15:41:26', '1.1062', 1, '', '2016-03-18 06:32:51', '864222027142475', '-33.96396396396396,18.465778128137558'),
(35, 5, 'rbnwt', '0779015844', '', 'Moureen', 'Natugonza', '2015-08-06 23:42:11', '2015-11-10 12:24:30', '1.1062', 1, '', '2016-03-18 06:38:52', '', ''),
(36, 2, 'rbnct5', '0779015829', '', 'Stephen', 'Guma', '2015-08-06 23:50:37', '2016-01-01 19:39:05', '1.1062', 1, '', '2016-03-18 06:42:40', '', ''),
(37, 2, 'rbnct6', '0779015814', '', 'Benard', 'Kamwebaze', '2015-08-06 23:55:39', '2015-10-20 19:31:31', '1.1062', 1, '', '2016-03-18 06:24:21', '', ''),
(38, 2, 'rbnct7', '0784295420', '', 'Lillian', 'Nyandera', '2015-08-06 23:58:55', '2015-11-17 18:25:09', 'Unknown', 1, '', '2016-03-18 06:38:01', '867014020467166', '0.5765765765765766,30.199727305937298'),
(39, 1, 'kabmorry', '', 'kabmorry@gmail.com', 'Morris', 'Kabuye', '2016-03-30 15:27:31', '0000-00-00 00:00:00', '', 1, '', '2016-03-30 15:27:31', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `user_groups`
--

CREATE TABLE `user_groups` (
  `id_group` int(11) NOT NULL,
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
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_groups`
--

INSERT INTO `user_groups` (`id_group`, `group_name`, `group_is_enabled`, `can_access_system_config`, `can_receive_emails`, `can_access_app`, `can_send_sms`, `can_receive_push_notifications`, `can_submit_attendant_daily_sales`, `can_approve_attendants_submissions`, `can_approve_treasurers_submissions`, `can_cancel_attendant_daily_sales`, `can_cancel_attendants_submissions`, `can_cancel_treasurers_submissions`, `can_add_water_users`, `can_edit_water_users`, `can_delete_water_users`, `can_view_water_users`, `can_add_sales`, `can_edit_sales`, `can_delete_sales`, `can_view_sales`, `can_view_personal_savings`, `can_view_water_source_savings`, `can_add_water_sources`, `can_edit_water_sources`, `can_delete_water_sources`, `can_view_water_sources`, `can_add_repair_types`, `can_edit_repair_types`, `can_delete_repair_types`, `can_view_repair_types`, `can_add_expenses`, `can_edit_expenses`, `can_delete_expenses`, `can_view_expenses`, `can_add_system_users`, `can_edit_system_users`, `can_delete_system_users`, `can_view_system_users`, `can_add_user_permissions`, `can_edit_user_permissions`, `can_delete_user_permissions`, `can_view_user_permissions`, `date_created`, `last_updated`) VALUES
(1, 'System Admin', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '0000-00-00 00:00:00', '2015-08-03 10:33:24'),
(2, 'Care Taker', 1, 0, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, 1, 1, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', '2015-08-03 10:33:24'),
(3, 'Scheme Attendant', 1, 0, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, 1, 1, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', '2015-08-03 10:33:24'),
(4, 'Water User Commitee Treasurer', 1, 0, 1, 1, 1, 1, 1, 1, 0, 1, 1, 0, 0, 0, 0, 1, 0, 0, 0, 1, 0, 1, 0, 0, 0, 1, 1, 1, 0, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', '2015-08-03 10:33:24'),
(5, 'Water Board Treasurer', 1, 0, 1, 1, 1, 1, 0, 1, 1, 0, 1, 1, 0, 0, 0, 1, 0, 0, 0, 1, 0, 1, 0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', '2015-08-03 10:33:24'),
(7, 'District Water Officer', 1, 0, 0, 1, 1, 1, 0, 0, 0, 0, 0, 0, 1, 1, 0, 1, 0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 1, 0, 0, 0, 1, '0000-00-00 00:00:00', '2015-08-03 10:33:24'),
(8, 'Test User Group', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `user_passwords`
--

CREATE TABLE `user_passwords` (
  `id_password` int(11) NOT NULL,
  `uid` bigint(255) NOT NULL,
  `password` varchar(40) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user_sessions`
--

CREATE TABLE `user_sessions` (
  `id_pass` bigint(255) NOT NULL,
  `uid` bigint(255) NOT NULL,
  `auth_code` varchar(40) NOT NULL,
  `auth_key` varchar(40) NOT NULL,
  `password` varchar(40) NOT NULL,
  `expires` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `water_sources`
--

CREATE TABLE `water_sources` (
  `id_water_source` int(11) NOT NULL,
  `water_source_id` text NOT NULL,
  `water_source_name` text NOT NULL,
  `water_source_location` text NOT NULL,
  `water_source_coordinates` text NOT NULL,
  `monthly_charges` float NOT NULL DEFAULT '0',
  `percentage_saved` float NOT NULL DEFAULT '0',
  `date_created` datetime DEFAULT '0000-00-00 00:00:00',
  `last_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `water_source_caretakers`
--

CREATE TABLE `water_source_caretakers` (
  `id_attendant` bigint(255) NOT NULL,
  `water_source_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `water_source_treasurers`
--

CREATE TABLE `water_source_treasurers` (
  `id_treasurer` bigint(255) NOT NULL,
  `water_source_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `water_users`
--

CREATE TABLE `water_users` (
  `id_user` bigint(255) NOT NULL,
  `fname` varchar(25) NOT NULL,
  `lname` varchar(25) NOT NULL,
  `pnumber` varchar(25) NOT NULL,
  `water_source_id` int(11) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `added_by` int(255) NOT NULL,
  `reported_defaulter` tinyint(1) NOT NULL,
  `marked_for_delete` tinyint(1) NOT NULL DEFAULT '0',
  `last_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `app_builds`
--
ALTER TABLE `app_builds`
  ADD PRIMARY KEY (`id_build`);

--
-- Indexes for table `app_user_sessions`
--
ALTER TABLE `app_user_sessions`
  ADD PRIMARY KEY (`id_pass`),
  ADD UNIQUE KEY `uid` (`uid`),
  ADD UNIQUE KEY `auth_keyauth_code` (`auth_key`,`auth_code`) USING BTREE,
  ADD KEY `quickLoginIndex` (`uid`,`auth_key`,`auth_code`);

--
-- Indexes for table `event_logs`
--
ALTER TABLE `event_logs`
  ADD PRIMARY KEY (`id_event`);

--
-- Indexes for table `expenditures`
--
ALTER TABLE `expenditures`
  ADD PRIMARY KEY (`id_expenditure`),
  ADD UNIQUE KEY `expenditure_id_water_source` (`id_expenditure`,`water_source_id`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id_file`),
  ADD KEY `idFileUid` (`id_file`) USING BTREE;

--
-- Indexes for table `repair_types`
--
ALTER TABLE `repair_types`
  ADD PRIMARY KEY (`id_repair_type`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id_sale`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id_system`);

--
-- Indexes for table `sms_messages`
--
ALTER TABLE `sms_messages`
  ADD PRIMARY KEY (`id_msg`);

--
-- Indexes for table `sms_messages_recipients`
--
ALTER TABLE `sms_messages_recipients`
  ADD PRIMARY KEY (`id_recepient`),
  ADD UNIQUE KEY `unique_receipient_index` (`msg_id`,`pnumber`,`idu`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`idu`);

--
-- Indexes for table `user_groups`
--
ALTER TABLE `user_groups`
  ADD PRIMARY KEY (`id_group`);

--
-- Indexes for table `user_passwords`
--
ALTER TABLE `user_passwords`
  ADD PRIMARY KEY (`id_password`),
  ADD UNIQUE KEY `uidIndex` (`id_password`),
  ADD KEY `uidPasswordIndex` (`uid`,`password`);

--
-- Indexes for table `user_sessions`
--
ALTER TABLE `user_sessions`
  ADD PRIMARY KEY (`id_pass`),
  ADD UNIQUE KEY `uid` (`uid`),
  ADD UNIQUE KEY `auth_keyauth_code` (`auth_key`,`auth_code`) USING BTREE,
  ADD KEY `quickLoginIndex` (`uid`,`auth_key`,`auth_code`);

--
-- Indexes for table `water_sources`
--
ALTER TABLE `water_sources`
  ADD PRIMARY KEY (`id_water_source`);

--
-- Indexes for table `water_source_caretakers`
--
ALTER TABLE `water_source_caretakers`
  ADD PRIMARY KEY (`id_attendant`);

--
-- Indexes for table `water_source_treasurers`
--
ALTER TABLE `water_source_treasurers`
  ADD PRIMARY KEY (`id_treasurer`);

--
-- Indexes for table `water_users`
--
ALTER TABLE `water_users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `id_user_water_source_index` (`id_user`,`water_source_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `app_builds`
--
ALTER TABLE `app_builds`
  MODIFY `id_build` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `app_user_sessions`
--
ALTER TABLE `app_user_sessions`
  MODIFY `id_pass` bigint(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT for table `event_logs`
--
ALTER TABLE `event_logs`
  MODIFY `id_event` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42438;
--
-- AUTO_INCREMENT for table `expenditures`
--
ALTER TABLE `expenditures`
  MODIFY `id_expenditure` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id_file` bigint(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `repair_types`
--
ALTER TABLE `repair_types`
  MODIFY `id_repair_type` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id_sale` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12365;
--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id_system` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `sms_messages`
--
ALTER TABLE `sms_messages`
  MODIFY `id_msg` bigint(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
--
-- AUTO_INCREMENT for table `sms_messages_recipients`
--
ALTER TABLE `sms_messages_recipients`
  MODIFY `id_recepient` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=806;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `idu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;
--
-- AUTO_INCREMENT for table `user_groups`
--
ALTER TABLE `user_groups`
  MODIFY `id_group` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `user_passwords`
--
ALTER TABLE `user_passwords`
  MODIFY `id_password` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;
--
-- AUTO_INCREMENT for table `user_sessions`
--
ALTER TABLE `user_sessions`
  MODIFY `id_pass` bigint(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;
--
-- AUTO_INCREMENT for table `water_sources`
--
ALTER TABLE `water_sources`
  MODIFY `id_water_source` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT for table `water_source_caretakers`
--
ALTER TABLE `water_source_caretakers`
  MODIFY `id_attendant` bigint(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=390;
--
-- AUTO_INCREMENT for table `water_source_treasurers`
--
ALTER TABLE `water_source_treasurers`
  MODIFY `id_treasurer` bigint(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=433;
--
-- AUTO_INCREMENT for table `water_users`
--
ALTER TABLE `water_users`
  MODIFY `id_user` bigint(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2508;