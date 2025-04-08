<?php
require_once 'PaymentLink.php';

$paymentLinkManager = new PaymentLink();
$statusColors = $paymentLinkManager->getStatusColors();

// Processar formulário de criação de link
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
  $title = $_POST['title'] ?? '';
  $description = $_POST['description'] ?? '';
  $amount = $_POST['amount'] ?? 0;

  $result = $paymentLinkManager->createPaymentLink(
    $title, $description, $amount
  );
}

// Parâmetros de paginação e busca
$page = $_GET['page'] ?? 1;
$search = $_GET['search'] ?? '';
$startDate = $_GET['start_date'] ?? null;
$endDate = $_GET['end_date'] ?? null;

// Buscar lista de links
$paymentLinks = $paymentLinkManager->listPaymentLinks(
  $page, 10, $search, $startDate, $endDate
);

// Contar total de registros para paginação
$totalLinks = $paymentLinkManager->countPaymentLinks(
  $search, $startDate, $endDate
);
$totalPages = ceil($totalLinks / 10);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Gerenciador de Links de Pagamento</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">
  <div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-4">Gerenciador de Links de Pagamento</h1>
    
    <!-- Formulário de Criação -->
    <form method="POST" class="bg-white p-6 rounded shadow-md mb-6">
      <input type="hidden" name="action" value="create">
      <div class="grid grid-cols-2 gap-4">
        <div class="mb-4">
          <label class="block text-gray-700">Título</label>
          <input type="text" name="title" required class="w-full px-3 py-2 border rounded">
        </div>
        <div class="mb-4">
          <label class="block text-gray-700">Valor</label>
          <input type="number" step="0.01" name="amount" required class="w-full px-3 py-2 border rounded">
        </div>
      </div>
      <div class="mb-4">
        <label class="block text-gray-700">Descrição</label>
        <textarea name="description" class="w-full px-3 py-2 border rounded"></textarea>
      </div>
      <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
        Criar Link de Pagamento
      </button>
    </form>

    <!-- Filtro de Busca -->
    <form method="GET" class="bg-white p-4 rounded shadow-md mb-4">
      <div class="grid grid-cols-3 gap-4">
        <div>
          <label class="block text-gray-700">Busca</label>
          <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" 
                 class="w-full px-3 py-2 border rounded" 
                 placeholder="Buscar por título ou valor">
        </div>
        <div>
          <label class="block text-gray-700">Data Inicial</label>
          <input type="date" name="start_date" value="<?= htmlspecialchars($startDate ?? '') ?>" 
                 class="w-full px-3 py-2 border rounded">
        </div>
        <div>
          <label class="block text-gray-700">Data Final</label>
          <input type="date" name="end_date" value="<?= htmlspecialchars($endDate ?? '') ?>" 
                 class="w-full px-3 py-2 border rounded">
        </div>
      </div>
      <button type="submit" class="mt-4 bg-green-500 text-white px-4 py-2 rounded">
        Filtrar
      </button>
    </form>

    <!-- Lista de Links -->
    <div class="bg-white p-6 rounded shadow-md">
      <h2 class="text-xl font-semibold mb-4">Links de Pagamento</h2>
      <table class="w-full border-collapse">
        <thead>
          <tr class="bg-gray-200">
            <th class="p-2 border text-left">Título</th>
            <th class="p-2 border text-left">Valor</th>
            <th class="p-2 border text-left">Status</th>
            <th class="p-2 border text-left">Data Criação</th>
            <th class="p-2 border text-left">Ações</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($paymentLinks as $link): 
            $statusColor = $statusColors[$link['status']] ?? 'bg-gray-100 text-gray-800';
            $createdAt = date('d/m/Y H:i', strtotime($link['created_at']));
          ?>
          <tr>
            <td class="p-2 border"><?= htmlspecialchars($link['title']) ?></td>
            <td class="p-2 border">R$ <?= number_format($link['amount'], 2, ',', '.') ?></td>
            <td class="p-2 border">
              <span class="px-2 py-1 rounded <?= $statusColor ?>">
                <?= htmlspecialchars($link['status']) ?>
              </span>
            </td>
            <td class="p-2 border"><?= $createdAt ?></td>
            <td class="p-2 border">
              <form method="GET" action="update_status.php" class="flex items-center">
                <input type="hidden" name="id" value="<?= $link['id'] ?>">
                <select name="status" class="border rounded px-2 py-1 mr-2">
                  <option value="">Atualizar Status</option>
                  <?php foreach (array_keys($statusColors) as $status): ?>
                    <?php if ($status !== $link['status']): ?>
                      <option value="<?= $status ?>"><?= $status ?></option>
                    <?php endif; ?>
                  <?php endforeach; ?>
                </select>
                <button type="submit" class="bg-blue-500 text-white px-2 py-1 rounded">
                  Salvar
                </button>
              </form>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <!-- Paginação -->
      <div class="flex justify-center mt-4">
        <div class="flex space-x-2">
          <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&start_date=<?= $startDate ?>&end_date=<?= $endDate ?>" 
               class="px-3 py-1 <?= $page == $i ? 'bg-blue-500 text-white' : 'bg-gray-200' ?> rounded">
              <?= $i ?>
            </a>
          <?php endfor; ?>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
