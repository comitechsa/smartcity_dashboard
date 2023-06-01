CREATE TABLE `otp` (
  `id` int(11) NOT NULL,
  `otp_pass` varchar(12) NOT NULL,
  `otp_timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `otp_tries` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `otp`
  ADD PRIMARY KEY (`id`);