<?php
declare(strict_types=1);

class EstoqueService {
    public function __construct(private PDO $db, private Produto $produtos, private Movimentacao $movimentacoes) {}

    public function movimentar(int $produtoId, int $usuarioId, string $tipo, int $quantidade, string $obs = ''): void {
        if (!in_array($tipo, ['ENTRADA', 'SAIDA'], true)) {
            throw new InvalidArgumentException('Tipo de movimentação inválido.');
        }
        if ($quantidade <= 0) {
            throw new InvalidArgumentException('A quantidade deve ser maior que zero.');
        }

        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare("SELECT * FROM produtos WHERE id = ? FOR UPDATE");
            $stmt->execute([$produtoId]);
            $produto = $stmt->fetch();

            if (!$produto) {
                throw new RuntimeException('Produto não encontrado.');
            }
            if ($produto['status'] === 'DESCONTINUADO') {
                throw new RuntimeException('Produto descontinuado não pode ser movimentado.');
            }

            $atual = (int)$produto['quantidade'];
            if ($tipo === 'SAIDA' && $quantidade > $atual) {
                throw new RuntimeException('Estoque insuficiente para realizar a saída.');
            }

            $novo = $tipo === 'ENTRADA' ? $atual + $quantidade : $atual - $quantidade;
            $status = $novo > 0 ? 'DISPONIVEL' : 'INDISPONIVEL';

            $this->produtos->atualizarEstoque($produtoId, $novo, $status);
            $this->movimentacoes->registrar($produtoId, $usuarioId, $tipo, $quantidade, trim($obs));
            $this->db->commit();
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) $this->db->rollBack();
            throw $e;
        }
    }
}
