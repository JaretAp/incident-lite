-- in MySQL as root
CREATE USER 'incidentapp'@'localhost' IDENTIFIED BY 'strong_password';
CREATE DATABASE IF NOT EXISTS incident_lite
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
GRANT ALL PRIVILEGES ON incident_lite.* TO 'incidentapp'@'localhost';
FLUSH PRIVILEGES;
