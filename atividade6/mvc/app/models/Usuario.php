<?php
declare(strict_types=1);

class Usuario {
    public function __construct(private PDO $db) {}

    public function autenticar(string $usuario, string $senha): ?array {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE usuario = ?");
        $stmt->execute([$usuario]);
        $row = $stmt->fetch();
        if ($row && password_verify($senha, $row['senha'])) {
            return ['id' => (int)$row['id'], 'usuario' => $row['usuario'], 'perfil' => $row['perfil']];
        }
        return null;
    }
}
