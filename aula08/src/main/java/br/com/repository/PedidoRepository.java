package br.com.vendas.repository;

import org.springframework.data.jpa.repository.JpaRepository;

import br.com.vendas.model.Pedido;

public interface PedidoRepository extends JpaRepository<Pedido, Long> {
}