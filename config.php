<?php
// Configurações de conexão com o banco de dados
define('DB_HOST', 'localhost');
define('DB_NAME', 'payment_links_db');
define('DB_USER', 'seu_usuario');
define('DB_PASS', 'sua_senha');

// Função de conexão com o banco de dados
function getDatabaseConnection() {
  try {
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $conn;
  } catch(PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
  }
}
