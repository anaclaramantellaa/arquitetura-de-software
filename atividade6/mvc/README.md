# Módulo de estoque — Projeto MVC (PHP + PDO + MySQL)

Projeto inicial funcional para a atividade da aula-6.

## Requisitos
- PHP 8+
- MySQL/MariaDB
- Servidor local (XAMPP recomendado)

## Como executar
1. Copie a pasta `projeto-mvc` para `C:\xampp\htdocs\aula-6\projeto-mvc`.
2. No phpMyAdmin, importe `database.sql`.
3. Confira usuário/senha em `config/database.php` (padrão XAMPP: usuário `root`, senha vazia).
4. Abra `http://localhost/aula-6/projeto-mvc/public/`.
5. Login inicial: `admin` / `admin123`.

## O que já está implementado
- Cadastro/listagem de produtos.
- Produto criado com status INDISPONIVEL.
- Entrada e saída de estoque com validações.
- Estoque maior que zero => DISPONIVEL.
- Estoque igual a zero => INDISPONIVEL, exceto se estiver DESCONTINUADO.
- Apenas ADM pode descontinuar produto.
- Produto descontinuado não pode receber entrada nem saída.
- Histórico de movimentações.
- Quatro diagramas Mermaid na pasta `aula-6`.

## Observações
É um projeto-base didático. Altere credenciais e remova/force a troca da senha padrão antes de qualquer uso real.
