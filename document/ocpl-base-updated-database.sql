CREATE TABLE `application` (
  `application_id` int(11) NOT NULL AUTO_INCREMENT,
  `tracking_number` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `status_id` int(11) NOT NULL,
  `applicant_type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `remarks` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `applicant_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `applicant_father_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `applicant_mother_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `permanent_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `present_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `agency_id` int(11) NOT NULL DEFAULT '0',
  `application_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_draft` tinyint(2) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  `initiated_by` int(11) NOT NULL DEFAULT '0',
  `closed_by` int(11) NOT NULL DEFAULT '0',
  `on_behalf_of` int(11) NOT NULL DEFAULT '0' COMMENT 'any process updated on behalf of any responsible person',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`application_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `app_process_path`;
CREATE TABLE `app_process_path` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `process_type` int(11) NOT NULL,
  `status_from` int(11) NOT NULL,
  `status_to` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `desk_from` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `desk_to` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `service_id` int(11) NOT NULL,
  `FILE_ATTACHMENT` tinyint(2) NOT NULL,
  `email` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `SERVICE_ID` (`process_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `app_status`;
CREATE TABLE `app_status` (
  `status_id` int(11) NOT NULL,
  `status_name` varchar(50) NOT NULL,
  `service_id` int(11) NOT NULL,
  `color` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`status_id`,`service_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `area_info`;
CREATE TABLE `area_info` (
  `area_id` int(11) DEFAULT NULL,
  `area_nm` varchar(120) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pare_id` int(11) DEFAULT NULL,
  `area_type` tinyint(4) DEFAULT NULL,
  `area_nm_ban` varchar(480) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nid_area_code` int(11) DEFAULT NULL,
  `sb_dist_code` int(11) DEFAULT NULL,
  `soundex_nm` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `audit_log`;
CREATE TABLE `audit_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `remote_ip` varchar(64) DEFAULT NULL,
  `module` varchar(50) DEFAULT NULL,
  `details` longblob,
  `user_type` varchar(20) DEFAULT NULL,
  `user_sub_type` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `configuration`;
CREATE TABLE `configuration` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `caption` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `details` varchar(255) NOT NULL,
  `value2` varchar(45) DEFAULT NULL,
  `value3` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int(11) NOT NULL,
  `is_locked` int(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `country_info`;
CREATE TABLE `country_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_code` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL,
  `iso` char(2) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `nicename` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `nationality` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `iso3` char(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `numcode` smallint(6) DEFAULT NULL,
  `phonecode` int(5) NOT NULL,
  `country_priority` tinyint(4) DEFAULT '2',
  `country_status` enum('Yes','No') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Yes',
  PRIMARY KEY (`id`),
  KEY `nationality` (`nationality`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `dashboard_object`;
CREATE TABLE `dashboard_object` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `db_obj_title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `db_obj_caption` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `db_user_id` int(11) DEFAULT NULL,
  `db_obj_type` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `db_obj_para1` varchar(5000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `db_obj_para2` varchar(5000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `db_obj_status` int(11) DEFAULT NULL,
  `db_obj_sort` int(11) DEFAULT NULL,
  `db_user_type` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_locked` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `db_usere_id` (`db_user_id`),
  KEY `db_obj_status` (`db_obj_status`),
  KEY `is_locked` (`is_locked`),
  KEY `db_obj_type` (`db_obj_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `dashboard_object_dynamic`;
CREATE TABLE `dashboard_object_dynamic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `pages` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `key` varchar(20) CHARACTER SET utf8 NOT NULL,
  `query` longtext COLLATE utf8_unicode_ci NOT NULL,
  `response` longtext COLLATE utf8_unicode_ci NOT NULL,
  `layout` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `updated_at` datetime NOT NULL,
  `time_limit` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `economic_zones`;
CREATE TABLE `economic_zones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `upazilla` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `district` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `area` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `remarks` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_locked` tinyint(4) NOT NULL DEFAULT '0',
  `is_archieved` tinyint(4) NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `failed_login_history`;
CREATE TABLE `failed_login_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `remote_address` varchar(50) NOT NULL,
  `user_email` varchar(40) NOT NULL,
  `is_archived` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `img_user_profile`;
CREATE TABLE `img_user_profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ref_id` int(11) DEFAULT NULL,
  `details` longtext,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int(11) DEFAULT NULL,
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `notice`;
CREATE TABLE `notice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `heading` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `details` varchar(5000) COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('public','unpublished','private','draft') COLLATE utf8_unicode_ci NOT NULL,
  `importance` enum('info','warning','danger','top') COLLATE utf8_unicode_ci NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `process_list`;
CREATE TABLE `process_list` (
  `process_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `track_no` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reference_no` varchar(65) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  `record_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `initiated_by` int(11) NOT NULL,
  `closed_by` int(11) NOT NULL,
  `desk_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `process_type` int(11) NOT NULL,
  `process_desc` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `on_behalf_of_desk` int(11) NOT NULL DEFAULT '0',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int(11) NOT NULL,
  PRIMARY KEY (`process_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `security_profile`;
CREATE TABLE `security_profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `user_email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `user_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `allowed_remote_ip` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `week_off_days` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `work_hour_start` time NOT NULL,
  `work_hour_end` time NOT NULL,
  `active_status` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `service_info`;
CREATE TABLE `service_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `url` varchar(64) DEFAULT NULL COMMENT 'After base URL',
  `form_url` varchar(64) DEFAULT NULL,
  `panel` varchar(64) DEFAULT NULL,
  `is_active` int(11) NOT NULL DEFAULT '1',
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `user_sub_type` int(11) NOT NULL DEFAULT '0',
  `eco_zone_id` int(11) NOT NULL DEFAULT '0',
  `desk_id` int(11) NOT NULL DEFAULT '0',
  `user_full_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `user_email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `delegate_to_user_id` int(11) DEFAULT NULL,
  `delegate_by_user_id` int(11) DEFAULT NULL,
  `user_hash` text COLLATE utf8_unicode_ci NOT NULL,
  `user_status` enum('active','inactive','rejected') CHARACTER SET utf8 NOT NULL,
  `user_verification` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `user_pic` text COLLATE utf8_unicode_ci NOT NULL,
  `user_nid` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_DOB` date NOT NULL,
  `user_gender` enum('Male','Female','Not defined') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Not defined',
  `user_phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `authorization_file` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `passport_nid_file` varchar(70) COLLATE utf8_unicode_ci DEFAULT NULL,
  `signature` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `user_first_login` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_language` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'bn',
  `security_profile_id` int(11) NOT NULL DEFAULT '0',
  `details` text COLLATE utf8_unicode_ci NOT NULL,
  `division` int(11) NOT NULL,
  `district` int(11) NOT NULL DEFAULT '0',
  `thana` int(11) NOT NULL DEFAULT '0',
  `country` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `nationality` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `passport_no` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `province` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `road_no` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `house_no` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `post_code` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `user_fax` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `login_token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_hash_expire_time` datetime NOT NULL,
  `auth_token_allow` int(11) NOT NULL DEFAULT '0',
  `auth_token` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `user_agreement` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `first_login` tinyint(4) NOT NULL DEFAULT '0',
  `identity_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1 = passport, 2 = NID',
  `is_approved` tinyint(4) NOT NULL DEFAULT '0',
  `is_locked` tinyint(4) NOT NULL DEFAULT '0',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_email` (`user_email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `user_desk`;
CREATE TABLE `user_desk` (
  `desk_id` int(11) NOT NULL AUTO_INCREMENT,
  `desk_name` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `desk_status` tinyint(1) NOT NULL COMMENT '1 = active, 0 = inactive',
  `is_registarable` int(11) NOT NULL,
  `deligate_to_desk` varchar(60) COLLATE utf8_unicode_ci DEFAULT '0',
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`desk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `user_logs`;
CREATE TABLE `user_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `ip_address` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `access_log_id` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `login_dt` datetime NOT NULL,
  `logout_dt` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `user_types`;
CREATE TABLE `user_types` (
  `id` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `type_name` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `is_registarable` int(11) NOT NULL,
  `access_code` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `permission_json` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'inactive',
  `security_profile_id` int(11) DEFAULT '1',
  `auth_token_type` enum('optional','mandatory') COLLATE utf8_unicode_ci DEFAULT 'optional',
  `db_access_data` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_by` int(11) NOT NULL,
  `updated_on` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `is_registrable` (`is_registarable`),
  KEY `security_profile.id` (`security_profile_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `user_types` (`id`, `type_name`, `is_registarable`, `access_code`, `permission_json`, `status`, `security_profile_id`, `auth_token_type`, `db_access_data`, `created_at`, `updated_at`, `updated_by`, `updated_on`) VALUES
('1x101',	'System Admin',	-1,	'1_101',	NULL,	'active',	1,	'optional',	'',	'2015-12-13 23:04:16',	'0000-00-00 00:00:00',	0,	'0000-00-00 00:00:00'),
('2x201',	'Registration Authority',	-1,	'2_201',	NULL,	'active',	1,	'optional',	'',	'2016-10-15 04:21:56',	'2014-12-31 05:00:00',	0,	'0000-00-00 00:00:00'),
('3x301',	'Administrative Official (AO)',	-1,	'6_606',	NULL,	'active',	1,	'optional',	'0',	'2016-10-05 09:35:30',	'2016-10-05 09:13:15',	1,	'0000-00-00 00:00:00'),
('4x401',	'Secretary',	-1,	'7_707',	NULL,	'active',	1,	'optional',	'0',	'2016-10-05 19:35:30',	'2016-10-05 19:13:15',	1,	'0000-00-00 00:00:00'),
('5x501',	'Agency Admin',	-1,	'',	NULL,	'inactive',	1,	'optional',	NULL,	'0000-00-00 00:00:00',	'0000-00-00 00:00:00',	0,	'0000-00-00 00:00:00'),
('5x502',	'Agency User',	1,	'',	NULL,	'inactive',	1,	'optional',	NULL,	'0000-00-00 00:00:00',	'0000-00-00 00:00:00',	0,	'0000-00-00 00:00:00'),
('6x601',	'Section/Branch User',	-1,	'6_601',	NULL,	'active',	1,	'optional',	'',	'2016-10-15 04:21:56',	'2014-12-31 05:00:00',	0,	'0000-00-00 00:00:00');