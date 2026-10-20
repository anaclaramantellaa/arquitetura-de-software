<?php
declare(strict_types=1);

class Produto {
    public function __construct(private PDO $db) {}

    public function todos(): array {
        return $this->db->query("SELECT * FROM produtos ORDER BY id DESC")->fetchAll();
    }

    public function buscar(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM produtos WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function criar(string $nome, string $descricao = ''): void {
        $stmt = $this->db->prepare(
            "INSERT INTO produtos (nome, descricao, quantidade, status) VALUES (?, ?, 0, 'INDISPONIVEL')"
        );
        $stmt->execute([$nome, $descricao]);
    }

    public function atualizarEstoque(int $id, int $quantidade, string $status): void {
        $stmt = $this->db->prepare("UPDATE produtos SET quantidade = ?, status = ? WHERE id = ?");
        $stmt->execute([$quantidade, $status, $id]);
    }

    public function descontinuar(int $id): void {
        $stmt = $this->db->prepare("UPDATE produtos SET status = 'DESCONTINUADO' WHERE id = ?");
        $stmt->execute([$id]);
    }
}
