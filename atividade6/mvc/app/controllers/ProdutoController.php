<?php
declare(strict_types=1);

class ProdutoController {
    public function __construct(private Produto $produtos) {}

    public function criar(string $nome, string $descricao): void {
        $nome = trim($nome);
        if ($nome === '') throw new InvalidArgumentException('Informe o nome do produto.');
        $this->produtos->criar($nome, trim($descricao));
    }

    public function descontinuar(int $id, array $usuario): void {
        if (($usuario['perfil'] ?? '') !== 'ADM') {
            throw new RuntimeException('Somente o administrador pode descontinuar produtos.');
        }
        $produto = $this->produtos->buscar($id);
        if (!$produto) throw new RuntimeException('Produto não encontrado.');
        if ($produto['status'] !== 'DESCONTINUADO') $this->produtos->descontinuar($id);
    }
}
