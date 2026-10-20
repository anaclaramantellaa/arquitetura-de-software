<?php
declare(strict_types=1);

class AuthController {
    public function __construct(private Usuario $usuarios) {}

    public function login(string $usuario, string $senha): bool {
        $dados = $this->usuarios->autenticar($usuario, $senha);
        if (!$dados) return false;
        session_regenerate_id(true);
        $_SESSION['usuario'] = $dados;
        return true;
    }

    public function logout(): void {
        $_SESSION = [];
        session_destroy();
    }
}
