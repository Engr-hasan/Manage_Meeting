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

INSERT INTO `audit_log` (`id`, `remote_ip`, `module`, `details`, `user_type`, `user_sub_type`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1,	'::1',	'User.edit',	'{\"validateFieldName\":\"\",\"isRequired\":\"\",\"user_full_name\":\"Rafid Shahriar\",\"user_type\":\"1x102\",\"user_nid\":\"\",\"user_DOB\":\"1992-11-21\",\"user_phone\":\"+8801521527826\",\"district\":\"26\",\"thana\":\"224\"}',	'1x101',	0,	1,	'2016-11-21 15:38:44',	1,	'2016-11-21 15:38:44');