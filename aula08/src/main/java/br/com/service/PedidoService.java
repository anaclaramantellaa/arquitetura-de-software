package br.com.vendas.service;

import java.math.BigDecimal;
import java.util.List;

import org.springframework.stereotype.Service;

import br.com.vendas.integracao.FaturamentoClient;
import br.com.vendas.integracao.FaturamentoException;
import br.com.vendas.model.Pedido;
import br.com.vendas.repository.PedidoRepository;
import lombok.RequiredArgsConstructor;
import lombok.extern.slf4j.Slf4j;

@Slf4j
@Service
@RequiredArgsConstructor
public class PedidoService {

  private final PedidoRepository repository;
  private final FaturamentoClient faturamentoClient;

  public List<Pedido> listar() {
    return repository.findAll();
  }

  public Pedido criar(String cliente, BigDecimal valor) {
    return repository.save(new Pedido(cliente, valor));
  }


  public Pedido fechar(Long pedidoId) {
    Pedido pedido = repository.findById(pedidoId)
      .orElseThrow(() -> new IllegalArgumentException("Pedido não encontrado: " + pedidoId));

    pedido.fechar();
    try {
      String faturaId = faturamentoClient.faturar(pedido.getId(), pedido.getValor());
      pedido.registrarFatura(faturaId);
    } catch (FaturamentoException e) {

      log.warn("Pedido {} fechado, mas não faturado: {}", pedidoId, e.getMessage());
      pedido.marcarPendenteFaturamento();
    }
    return repository.save(pedido);
  }
}