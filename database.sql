CREATE DATABASE cr_management;
USE cr_management;

CREATE TABLE users (
  id int(11) NOT NULL,
  username varchar(50) NOT NULL,
  password varchar(255) NOT NULL,
  role enum('developer','config_manager','customer_support') NOT NULL
);

INSERT INTO users (id, username, password, role) VALUES
(1, 'admin', 'adminpass', 'config_manager'),
(2, 'dev1', 'dev1pass', 'developer'),
(3, 'dev2', 'dev2pass', 'developer'),
(4, 'support1', 'supportpass', 'customer_support'),
(5, 'support2', 'supportpass', 'customer_support');

ALTER TABLE users
  ADD PRIMARY KEY (id),
  ADD UNIQUE KEY username (username);

ALTER TABLE users
  MODIFY id int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

CREATE TABLE crs (
  id int(11) NOT NULL,
  title varchar(100) NOT NULL,
  description text DEFAULT NULL,
  raised_by varchar(50) NOT NULL,
  raise_time timestamp NOT NULL DEFAULT current_timestamp(),
  status enum('unassigned','assigned','in_progress','completed') DEFAULT 'unassigned',
  assign_time timestamp NULL DEFAULT NULL,
  assigned_to varchar(50) DEFAULT NULL,
  completion_status enum('pending','completed') DEFAULT 'pending',
  cr_type varchar(100) DEFAULT NULL,
  cr_priority varchar(100) DEFAULT NULL
);

INSERT INTO crs (id, title, description, raised_by, raise_time, status, assign_time, assigned_to, completion_status, cr_type, cr_priority) VALUES
(1, 'Fix Login Bug', 'Users unable to log in with valid credentials', 'support1', '2024-03-15 03:00:00', 'assigned', '2024-03-16 03:30:00', 'dev1', 'pending', 'bug', 'high'),
(2, 'Fix Session Timeout Bug', 'User sessions expire too quickly', 'support1', '2024-03-28 03:15:00', 'assigned', '2024-03-29 03:45:00', 'dev2', 'pending', 'bug', 'high'),
(3, 'Add Dark Mode', 'Implement dark mode for UI', 'support2', '2024-04-12 05:30:00', 'unassigned', NULL, NULL, 'pending', 'feature', NULL),
(4, 'Fix Password Reset Issue', 'Unable to reset password for inactive users', 'support1', '2024-04-12 03:40:00', 'assigned', '2024-04-13 05:00:00', 'dev1', 'pending', 'bug', 'medium'),
(5, 'Fix Notification Delay Issue', 'Notifications arrive after significant delays', 'support1', '2024-04-25 05:30:00', 'assigned', '2024-04-26 06:15:00', 'dev2', 'pending', 'bug', 'high'),
(6, 'Optimize API Performance', 'Reduce API response times', 'dev1', '2024-05-18 03:55:00', 'unassigned', NULL, NULL, 'pending', 'improvement', NULL),
(7, 'Fix UI Overlapping Elements', 'UI elements overlap on smaller screens', 'support2', '2024-06-10 05:20:00', 'assigned', '2024-06-11 06:00:00', 'dev1', 'pending', 'bug', 'medium'),
(8, 'Improve Logging Mechanism', 'Add detailed logging for error analysis', 'dev2', '2024-06-25 08:35:00', 'unassigned', NULL, NULL, 'pending', 'improvement', NULL),
(9, 'Add Export to PDF Feature', 'Allow exporting reports to PDF format', 'dev2', '2024-07-15 06:30:00', 'unassigned', NULL, NULL, 'pending', 'feature', NULL),
(10, 'Optimize Database', 'Improve query performance for reports', 'dev1', '2024-09-10 07:45:00', 'unassigned', NULL, NULL, 'pending', 'improvement', NULL),
(11, 'Add Multi-Language Support', 'Support for multiple languages in the system', 'support2', '2024-09-18 08:50:00', 'unassigned', NULL, NULL, 'pending', 'feature', NULL),
(12, 'Update UI for Accessibility', 'Improve UI for better accessibility standards', 'support2', '2024-09-22 11:00:00', 'unassigned', NULL, NULL, 'pending', 'feature', NULL),
(13, 'Fix Crash on Save', 'App crashes when saving records', 'support1', '2024-10-05 04:50:00', 'assigned', '2024-10-06 06:30:00', 'dev2', 'pending', 'bug', 'high'),
(14, 'Add Notification System', 'Notify users about CR updates', 'support2', '2024-10-15 10:15:00', 'unassigned', NULL, NULL, 'pending', 'feature', NULL),
(15, 'Enhance Data Validation Rules', 'Add stricter validation for user inputs', 'dev2', '2024-10-22 07:40:00', 'unassigned', NULL, NULL, 'pending', 'improvement', NULL);

ALTER TABLE crs
  ADD PRIMARY KEY (id);

ALTER TABLE crs
  MODIFY id int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;