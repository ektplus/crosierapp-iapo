CREATE DATABASE `crosier_iapo_dev` CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_0900_ai_ci';
CREATE USER 'crosier_iapo_dev'@'localhost' IDENTIFIED BY 'crosier_iapo_dev';
GRANT ALL PRIVILEGES ON crosier_iapo_dev.* TO 'crosier_iapo_dev'@'localhost';
FLUSH PRIVILEGES;
