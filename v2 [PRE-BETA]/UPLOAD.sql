-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 27, 2019 at 02:15 AM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 7.3.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hydrid_rw`
--

-- --------------------------------------------------------

--
-- Table structure for table `10_codes`
--

CREATE TABLE `10_codes` (
  `id` int(11) NOT NULL,
  `code` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `10_codes`
--

INSERT INTO `10_codes` (`id`, `code`) VALUES
(1, '10-6'),
(2, '10-7'),
(3, '10-8'),
(4, '10-11'),
(5, '10-15'),
(6, '10-23'),
(7, '10-41'),
(8, '10-42'),
(9, '10-90'),
(10, '10-97');

-- --------------------------------------------------------

--
-- Table structure for table `911calls`
--

CREATE TABLE `911calls` (
  `call_id` int(11) NOT NULL,
  `caller_id` int(11) NOT NULL,
  `call_description` varchar(355) NOT NULL,
  `call_location` varchar(128) NOT NULL,
  `call_crossstreat` varchar(128) NOT NULL,
  `call_postal` int(11) NOT NULL,
  `call_status` varchar(534) NOT NULL DEFAULT 'NOT ASSIGNED',
  `call_timestamp` text NOT NULL,
  `call_isPriority` enum('false','true') DEFAULT 'false'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `arrest_reports`
--

CREATE TABLE `arrest_reports` (
  `arrest_id` int(11) NOT NULL,
  `arresting_officer` varchar(126) NOT NULL,
  `timestamp` varchar(64) NOT NULL,
  `suspect` varchar(126) NOT NULL,
  `suspect_id` int(11) NOT NULL,
  `summary` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `assigned_callunits`
--

CREATE TABLE `assigned_callunits` (
  `id` int(11) NOT NULL,
  `call_id` int(11) NOT NULL,
  `unit_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bolos`
--

CREATE TABLE `bolos` (
  `bolo_id` int(11) NOT NULL,
  `vehicle_plate` varchar(8) NOT NULL,
  `vehicle_color` varchar(126) NOT NULL,
  `vehicle_model` varchar(126) NOT NULL,
  `bolo_created_on` varchar(128) NOT NULL,
  `bolo_reason` varchar(255) NOT NULL,
  `bolo_created_by` varchar(126) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `characters`
--

CREATE TABLE `characters` (
  `character_id` int(11) NOT NULL,
  `first_name` varchar(64) NOT NULL,
  `last_name` varchar(64) NOT NULL,
  `date_of_birth` varchar(126) NOT NULL,
  `address` text NOT NULL,
  `height` varchar(36) NOT NULL,
  `eye_color` varchar(36) NOT NULL,
  `hair_color` varchar(36) NOT NULL,
  `sex` varchar(12) NOT NULL,
  `race` varchar(60) NOT NULL,
  `weight` varchar(36) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `owner_name` varchar(128) NOT NULL,
  `status` varchar(36) NOT NULL DEFAULT 'Enabled',
  `license_driver` varchar(36) NOT NULL DEFAULT 'None',
  `license_firearm` varchar(36) NOT NULL DEFAULT 'None'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `identities`
--

CREATE TABLE `identities` (
  `identity_id` int(11) NOT NULL,
  `name` varchar(126) NOT NULL,
  `department` varchar(64) NOT NULL,
  `division` varchar(36) DEFAULT NULL,
  `supervisor` varchar(36) NOT NULL DEFAULT 'No',
  `created_on` varchar(126) NOT NULL,
  `user` int(11) NOT NULL,
  `user_name` varchar(128) NOT NULL,
  `status` enum('Active','Approval Needed','Suspended') NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `leo_division`
--

CREATE TABLE `leo_division` (
  `id` int(11) NOT NULL,
  `name` varchar(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `leo_division`
--

INSERT INTO `leo_division` (`id`, `name`) VALUES
(1, 'L.S.P.D'),
(2, 'B.C.S.O');

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `log_id` int(11) NOT NULL,
  `action` varchar(300) NOT NULL DEFAULT 'NaN',
  `username` varchar(128) NOT NULL DEFAULT 'NaN',
  `timestamp` varchar(364) NOT NULL DEFAULT 'NaN'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `on_duty`
--

CREATE TABLE `on_duty` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `department` varchar(64) NOT NULL,
  `division` varchar(64) DEFAULT NULL,
  `status` varchar(64) NOT NULL DEFAULT '10-41'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `servers`
--

CREATE TABLE `servers` (
  `id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `aop` varchar(64) NOT NULL DEFAULT 'Sandy Shores',
  `priority` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `servers`
--

INSERT INTO `servers` (`id`, `name`, `aop`, `priority`) VALUES
(1, 'Server 1', 'Statewide', 0);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `setting_id` int(11) NOT NULL,
  `site_name` varchar(255) NOT NULL,
  `account_validation` varchar(36) NOT NULL DEFAULT 'false',
  `identity_validation` varchar(36) NOT NULL DEFAULT 'false',
  `steam_required` varchar(36) NOT NULL DEFAULT 'false',
  `discord_alerts` enum('true','false') NOT NULL DEFAULT 'false',
  `discord_webhook` text,
  `timezone` varchar(128) NOT NULL DEFAULT 'America/Los_Angeles',
  `civ_side_warrants` varchar(36) NOT NULL DEFAULT 'false',
  `add_warrant` enum('all','supervisor') NOT NULL DEFAULT 'supervisor'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`setting_id`, `site_name`, `account_validation`, `identity_validation`, `steam_required`, `discord_alerts`, `discord_webhook`, `timezone`, `civ_side_warrants`, `add_warrant`) VALUES
(1, 'Hydrid CAD/MDT', 'no', 'no', 'false', 'false', NULL, 'America/New_York', 'false', 'supervisor');

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `ticket_id` int(11) NOT NULL,
  `officer` varchar(126) NOT NULL,
  `suspect` varchar(126) NOT NULL,
  `suspect_id` int(11) NOT NULL,
  `ticket_timestamp` varchar(355) NOT NULL,
  `reasons` text NOT NULL,
  `location` text NOT NULL,
  `postal` int(255) NOT NULL,
  `amount` varchar(24) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(36) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(164) NOT NULL,
  `usergroup` enum('Banned','Unverified','User','Moderator','Admin','Super Admin') NOT NULL DEFAULT 'User',
  `join_date` varchar(126) NOT NULL,
  `join_ip` varchar(126) NOT NULL,
  `last_ip` varchar(36) DEFAULT NULL,
  `steam_id` varchar(355) DEFAULT NULL,
  `avatar` varchar(355) DEFAULT 'assets/images/users/placeholder.png',
  `failed_logins` int(11) NOT NULL DEFAULT '0',
  `locked` varchar(36) DEFAULT NULL,
  `ban_reason` varchar(126) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `vehicle_id` int(11) NOT NULL,
  `vehicle_plate` varchar(8) DEFAULT NULL,
  `vehicle_color` varchar(36) NOT NULL,
  `vehicle_model` varchar(36) NOT NULL,
  `vehicle_is` varchar(36) NOT NULL,
  `vehicle_rs` varchar(36) NOT NULL,
  `vehicle_vin` varchar(17) NOT NULL,
  `vehicle_owner` int(11) NOT NULL,
  `vehicle_ownername` varchar(126) NOT NULL,
  `vehicle_status` varchar(36) NOT NULL DEFAULT 'Enabled'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `warrants`
--

CREATE TABLE `warrants` (
  `warrant_id` int(11) NOT NULL,
  `issued_on` varchar(355) NOT NULL,
  `signed_by` varchar(128) NOT NULL,
  `reason` text NOT NULL,
  `wanted_person` varchar(128) NOT NULL,
  `wanted_person_id` int(11) NOT NULL,
  `wanted_status` varchar(36) NOT NULL DEFAULT 'WANTED'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `weapons`
--

CREATE TABLE `weapons` (
  `wpn_id` int(11) NOT NULL,
  `wpn_type` varchar(126) NOT NULL,
  `wpn_serial` varchar(10) NOT NULL,
  `wpn_owner` int(11) NOT NULL,
  `wpn_ownername` varchar(255) NOT NULL,
  `wpn_rpstatus` varchar(255) NOT NULL DEFAULT 'Valid'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `10_codes`
--
ALTER TABLE `10_codes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `911calls`
--
ALTER TABLE `911calls`
  ADD PRIMARY KEY (`call_id`);

--
-- Indexes for table `arrest_reports`
--
ALTER TABLE `arrest_reports`
  ADD PRIMARY KEY (`arrest_id`);

--
-- Indexes for table `assigned_callunits`
--
ALTER TABLE `assigned_callunits`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bolos`
--
ALTER TABLE `bolos`
  ADD PRIMARY KEY (`bolo_id`);

--
-- Indexes for table `characters`
--
ALTER TABLE `characters`
  ADD PRIMARY KEY (`character_id`);

--
-- Indexes for table `identities`
--
ALTER TABLE `identities`
  ADD PRIMARY KEY (`identity_id`);

--
-- Indexes for table `leo_division`
--
ALTER TABLE `leo_division`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `on_duty`
--
ALTER TABLE `on_duty`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `servers`
--
ALTER TABLE `servers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`setting_id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`ticket_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`vehicle_id`);

--
-- Indexes for table `warrants`
--
ALTER TABLE `warrants`
  ADD PRIMARY KEY (`warrant_id`);

--
-- Indexes for table `weapons`
--
ALTER TABLE `weapons`
  ADD PRIMARY KEY (`wpn_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `10_codes`
--
ALTER TABLE `10_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `911calls`
--
ALTER TABLE `911calls`
  MODIFY `call_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `arrest_reports`
--
ALTER TABLE `arrest_reports`
  MODIFY `arrest_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `assigned_callunits`
--
ALTER TABLE `assigned_callunits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bolos`
--
ALTER TABLE `bolos`
  MODIFY `bolo_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `characters`
--
ALTER TABLE `characters`
  MODIFY `character_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `identities`
--
ALTER TABLE `identities`
  MODIFY `identity_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leo_division`
--
ALTER TABLE `leo_division`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `on_duty`
--
ALTER TABLE `on_duty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `servers`
--
ALTER TABLE `servers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `ticket_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `vehicle_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `warrants`
--
ALTER TABLE `warrants`
  MODIFY `warrant_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `weapons`
--
ALTER TABLE `weapons`
  MODIFY `wpn_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
