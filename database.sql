-- Script de criação do banco de dados
CREATE DATABASE IF NOT EXISTS payment_links_db;
USE payment_links_db;

CREATE TABLE IF NOT EXISTS payment_links (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  description TEXT,
  amount DECIMAL(10,2) NOT NULL,
  status ENUM('Link Enviado', 'Promissória Gerada', 'Crédito Gerado', 'Cancelado') DEFAULT 'Link Enviado',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
