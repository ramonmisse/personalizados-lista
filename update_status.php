<?php
require_once 'PaymentLink.php';

$paymentLinkManager = new PaymentLink();

// Verificar se ID e status foram passados
if (isset($_GET['id']) && isset($_GET['status'])) {
  $id = intval($_GET['id']);
  $status = $_GET['status'];

  // Lista de status válidos
  $validStatuses = [
    'Link Enviado', 
    'Promissória Gerada', 
    'Crédito Gerado', 
    'Cancelado'
  ];

  // Validar status
  if (in_array($status, $validStatuses)) {
    // Atualizar status
    $result = $paymentLinkManager->updatePaymentLinkStatus($id, $status);

    // Redirecionar de volta para a página principal
    header('Location: index.php');
    exit();
  } else {
    echo "Status inválido";
    exit();
  }
} else {
  // Caso não tenha os parâmetros necessários
  echo "Parâmetros inválidos";
  exit();
}
