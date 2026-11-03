package br.com.vendas.service;

import static org.junit.jupiter.api.Assertions.assertEquals;
import static org.junit.jupiter.api.Assertions.assertNull;
import static org.mockito.ArgumentMatchers.any;
import static org.mockito.Mockito.mock;
import static org.mockito.Mockito.when;

import java.math.BigDecimal;
import java.util.Optional;

import org.junit.jupiter.api.BeforeEach;
import org.junit.jupiter.api.Test;

import br.com.vendas.integracao.FaturamentoClient;
import br.com.vendas.integracao.FaturamentoException;
import br.com.vendas.model.Pedido;
import br.com.vendas.model.StatusPedido;
import br.com.vendas.repository.PedidoRepository;

class PedidoServiceTest {

  private PedidoRepository repository;
  private FaturamentoClient client;
  private PedidoService service;
  private Pedido pedido;

  @BeforeEach
  void setUp() {
    repository = mock(PedidoRepository.class);
    client = mock(FaturamentoClient.class);
    service = new PedidoService(repository, client);
    pedido = new Pedido("Maria", new BigDecimal("100.00"));
    when(repository.findById(1L)).thenReturn(Optional.of(pedido));
    when(repository.save(any(Pedido.class))).thenAnswer(i -> i.getArgument(0));
  }

  @Test
  void deveGuardarOIdDaFaturaDevolvidoPeloHub() {
    when(client.faturar(any(), any())).thenReturn("uuid-da-fatura");

    Pedido fechado = service.fechar(1L);

    assertEquals(StatusPedido.FATURADO, fechado.getStatus());
    assertEquals("uuid-da-fatura", fechado.getFaturaId());
  }

  @Test
  void deveManterPedidoPendenteQuandoHubFalha() {
    when(client.faturar(any(), any())).thenThrow(new FaturamentoException("hub fora do ar"));

    Pedido fechado = service.fechar(1L);

    assertEquals(StatusPedido.PENDENTE_FATURAMENTO, fechado.getStatus());
    assertNull(fechado.getFaturaId());
  }
}