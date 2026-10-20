<?php
declare(strict_types=1);

class Movimentacao {
    public function __construct(private PDO $db) {}

    public function registrar(int $produtoId, int $usuarioId, string $tipo, int $quantidade, string $obs = ''): void {
        $stmt = $this->db->prepare(
            "INSERT INTO movimentacoes (produto_id, usuario_id, tipo, quantidade, observacao)
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([$produtoId, $usuarioId, $tipo, $quantidade, $obs]);
    }

    public function historico(): array {
        return $this->db->query(
            "SELECT m.*, p.nome AS produto, u.usuario
             FROM movimentacoes m
             JOIN produtos p ON p.id = m.produto_id
             JOIN usuarios u ON u.id = m.usuario_id
             ORDER BY m.id DESC LIMIT 100"
        )->fetchAll();
    }
}
