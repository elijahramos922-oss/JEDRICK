-- ============================================
-- Buksan mo ang phpMyAdmin, pindutin ang "Import" tab,
-- tapos i-upload/i-paste ang buong file na ito.
-- Gagawa ito ng database at table na kailangan.
-- ============================================

CREATE DATABASE IF NOT EXISTS birthday_jedrick
  CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

USE birthday_jedrick;

CREATE TABLE IF NOT EXISTS wishes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  wish_text TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);