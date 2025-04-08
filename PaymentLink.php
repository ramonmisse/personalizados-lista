<?php
require_once 'config.php';

class PaymentLink {
  private $conn;

  public function __construct() {
    $this->conn = getDatabaseConnection();
  }

  // Listar links de pagamento com paginação e filtros
  public function listPaymentLinks($page = 1, $perPage = 10, $search = '', $startDate = null, $endDate = null) {
    try {
      $offset = ($page - 1) * $perPage;
      
      // Construir query base
      $query = "SELECT * FROM payment_links WHERE 1=1";
      $params = [];

      // Adicionar filtros de busca
      if (!empty($search)) {
        $query .= " AND (title LIKE :search OR 
                         CAST(amount AS CHAR) LIKE :search)";
        $params[':search'] = "%{$search}%";
      }

      // Filtro por data
      if ($startDate) {
        $query .= " AND created_at >= :start_date";
        $params[':start_date'] = $startDate;
      }
      if ($endDate) {
        $query .= " AND created_at <= :end_date";
        $params[':end_date'] = $endDate;
      }

      // Adicionar ordenação e paginação
      $query .= " ORDER BY created_at DESC LIMIT :limit OFFSET :offset";

      // Preparar e executar statement
      $stmt = $this->conn->prepare($query);
      
      // Bind de parâmetros
      foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
      }
      $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
      $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
      error_log("Erro ao listar links de pagamento: " . $e->getMessage());
      return [];
    }
  }

  // Contar total de registros para paginação
  public function countPaymentLinks($search = '', $startDate = null, $endDate = null) {
    try {
      $query = "SELECT COUNT(*) as total FROM payment_links WHERE 1=1";
      $params = [];

      // Adicionar filtros de busca (igual ao método listPaymentLinks)
      if (!empty($search)) {
        $query .= " AND (title LIKE :search OR 
                         CAST(amount AS CHAR) LIKE :search)";
        $params[':search'] = "%{$search}%";
      }

      // Filtro por data
      if ($startDate) {
        $query .= " AND created_at >= :start_date";
        $params[':start_date'] = $startDate;
      }
      if ($endDate) {
        $query .= " AND created_at <= :end_date";
        $params[':end_date'] = $endDate;
      }

      $stmt = $this->conn->prepare($query);
      
      // Bind de parâmetros
      foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
      }

      $stmt->execute();
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      return $result['total'];
    } catch(PDOException $e) {
      error_log("Erro ao contar links de pagamento: " . $e->getMessage());
      return 0;
    }
  }

  // Métodos anteriores mantidos (createPaymentLink, updatePaymentLinkStatus, etc.)
  public function createPaymentLink($title, $description, $amount) {
    try {
      $stmt = $this->conn->prepare("
        INSERT INTO payment_links 
        (title, description, amount) 
        VALUES (:title, :description, :amount)
      ");
      
      $stmt->bindParam(':title', $title);
      $stmt->bindParam(':description', $description);
      $stmt->bindParam(':amount', $amount);
      
      return $stmt->execute();
    } catch(PDOException $e) {
      error_log("Erro ao criar link de pagamento: " . $e->getMessage());
      return false;
    }
  }

  public function updatePaymentLinkStatus($id, $status) {
    try {
      $stmt = $this->conn->prepare("
        UPDATE payment_links 
        SET status = :status 
        WHERE id = :id
      ");
      
      $stmt->bindParam(':status', $status);
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);
      
      return $stmt->execute();
    } catch(PDOException $e) {
      error_log("Erro ao atualizar status do link: " . $e->getMessage());
      return false;
    }
  }

  public function getStatusColors() {
    return [
      'Link Enviado' => 'bg-blue-100 text-blue-800',
      'Promissória Gerada' => 'bg-yellow-100 text-yellow-800',
      'Crédito Gerado' => 'bg-green-100 text-green-800',
      'Cancelado' => 'bg-red-100 text-red-800'
    ];
  }
}
