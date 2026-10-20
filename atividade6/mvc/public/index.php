<?php
declare(strict_types=1);
session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/models/Produto.php';
require_once __DIR__ . '/../app/models/Usuario.php';
require_once __DIR__ . '/../app/models/Movimentacao.php';
require_once __DIR__ . '/../app/services/EstoqueService.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/ProdutoController.php';

$erro = '';
$sucesso = '';
try {
    $db = getPDO();
    $produtoModel = new Produto($db);
    $usuarioModel = new Usuario($db);
    $movModel = new Movimentacao($db);
    $auth = new AuthController($usuarioModel);
    $produtoController = new ProdutoController($produtoModel);
    $estoqueService = new EstoqueService($db, $produtoModel, $movModel);

    $action = $_GET['action'] ?? 'home';
    if ($action === 'logout') {
        $auth->logout();
        header('Location: index.php');
        exit;
    }

    if (empty($_SESSION['usuario'])) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'login') {
            if ($auth->login($_POST['usuario'] ?? '', $_POST['senha'] ?? '')) {
                header('Location: index.php');
                exit;
            }
            $erro = 'Usuário ou senha inválidos.';
        }
        require __DIR__ . '/../app/views/layout/header.php';
        ?>
        <section class="card" style="max-width:430px;margin:50px auto">
          <h1>Entrar</h1><p>Faça login para acessar produtos e estoque.</p>
          <?php if ($erro): ?><div class="msg erro"><?= htmlspecialchars($erro) ?></div><?php endif; ?>
          <form method="post" action="?action=login">
            <label>Usuário</label><input name="usuario" required autocomplete="username">
            <label>Senha</label><input type="password" name="senha" required autocomplete="current-password">
            <button type="submit">Entrar</button>
          </form>
          <small>Login inicial: admin / admin123</small>
        </section>
        <?php require __DIR__ . '/../app/views/layout/footer.php'; exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($action === 'criar-produto') {
            $produtoController->criar($_POST['nome'] ?? '', $_POST['descricao'] ?? '');
            $sucesso = 'Produto cadastrado com status INDISPONÍVEL.';
        } elseif ($action === 'movimentar') {
            $estoqueService->movimentar(
                (int)($_POST['produto_id'] ?? 0),
                (int)$_SESSION['usuario']['id'],
                $_POST['tipo'] ?? '',
                (int)($_POST['quantidade'] ?? 0),
                $_POST['observacao'] ?? ''
            );
            $sucesso = 'Movimentação de estoque registrada com sucesso.';
        } elseif ($action === 'descontinuar') {
            $produtoController->descontinuar((int)($_POST['produto_id'] ?? 0), $_SESSION['usuario']);
            $sucesso = 'Produto descontinuado.';
        }
    }
} catch (Throwable $e) {
    $erro = $e->getMessage();
}

require __DIR__ . '/../app/views/layout/header.php';
if ($erro): ?><div class="msg erro"><?= htmlspecialchars($erro) ?></div><?php endif; ?>
<?php if ($sucesso): ?><div class="msg"><?= htmlspecialchars($sucesso) ?></div><?php endif; ?>

<div class="grid">
  <section class="card">
    <h2>Cadastrar produto</h2>
    <form method="post" action="?action=criar-produto">
      <label>Nome do produto</label><input name="nome" required maxlength="150">
      <label>Descrição</label><textarea name="descricao" rows="2"></textarea>
      <button type="submit">Cadastrar</button>
    </form>
    <small>Todo produto novo começa com quantidade zero e status INDISPONÍVEL.</small>
  </section>
  <section class="card">
    <h2>Movimentar estoque</h2>
    <form method="post" action="?action=movimentar">
      <label>Produto</label>
      <select name="produto_id" required>
        <option value="">Selecione</option>
        <?php foreach (($produtoModel->todos() ?? []) as $p): ?>
          <option value="<?= (int)$p['id'] ?>"><?= htmlspecialchars($p['nome']) ?> — <?= htmlspecialchars($p['status']) ?> (qtd. <?= (int)$p['quantidade'] ?>)</option>
        <?php endforeach; ?>
      </select>
      <label>Tipo</label><select name="tipo" required><option value="ENTRADA">Entrada</option><option value="SAIDA">Saída</option></select>
      <label>Quantidade</label><input type="number" name="quantidade" min="1" step="1" required>
      <label>Observação (opcional)</label><input name="observacao" maxlength="255">
      <button type="submit">Registrar movimentação</button>
    </form>
  </section>
</div>

<section class="card">
<h2>Produtos</h2>
<table><thead><tr><th>Produto</th><th>Quantidade</th><th>Status</th><th>Ação</th></tr></thead><tbody>
<?php foreach (($produtoModel->todos() ?? []) as $p): ?>
<tr>
<td><?= htmlspecialchars($p['nome']) ?><br><small><?= htmlspecialchars($p['descricao'] ?? '') ?></small></td>
<td><?= (int)$p['quantidade'] ?></td>
<td class="status <?= htmlspecialchars($p['status']) ?>"><?= htmlspecialchars(str_replace('_',' ', $p['status'])) ?></td>
<td>
<?php if ($p['status'] !== 'DESCONTINUADO' && ($_SESSION['usuario']['perfil'] ?? '') === 'ADM'): ?>
<form class="inline" method="post" action="?action=descontinuar" onsubmit="return confirm('Descontinuar este produto?')">
<input type="hidden" name="produto_id" value="<?= (int)$p['id'] ?>">
<button class="danger" type="submit">Descontinuar</button>
</form>
<?php elseif ($p['status'] === 'DESCONTINUADO'): ?><small>Sem movimentações</small>
<?php else: ?><small>Apenas ADM pode descontinuar</small><?php endif; ?>
</td>
</tr>
<?php endforeach; ?>
<?php if (empty($produtoModel->todos())): ?><tr><td colspan="4">Nenhum produto cadastrado.</td></tr><?php endif; ?>
</tbody></table>
</section>

<section class="card">
<h2>Histórico de movimentações</h2>
<table><thead><tr><th>Data</th><th>Produto</th><th>Tipo</th><th>Quantidade</th><th>Usuário</th><th>Observação</th></tr></thead><tbody>
<?php foreach ($movModel->historico() as $m): ?>
<tr><td><?= htmlspecialchars($m['criado_em']) ?></td><td><?= htmlspecialchars($m['produto']) ?></td><td><?= htmlspecialchars($m['tipo']) ?></td><td><?= (int)$m['quantidade'] ?></td><td><?= htmlspecialchars($m['usuario']) ?></td><td><?= htmlspecialchars($m['observacao'] ?? '') ?></td></tr>
<?php endforeach; ?>
<?php if (empty($movModel->historico())): ?><tr><td colspan="6">Nenhuma movimentação registrada.</td></tr><?php endif; ?>
</tbody></table>
</section>
<?php require __DIR__ . '/../app/views/layout/footer.php'; ?>
