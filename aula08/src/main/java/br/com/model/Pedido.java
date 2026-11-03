package br.com.vendas.model;

import java.math.BigDecimal;
import java.time.LocalDateTime;

import jakarta.persistence.Entity;
import jakarta.persistence.EnumType;
import jakarta.persistence.Enumerated;
import jakarta.persistence.GeneratedValue;
import jakarta.persistence.GenerationType;
import jakarta.persistence.Id;
import jakarta.persistence.Table;
import lombok.Getter;
import lombok.NoArgsConstructor;
import lombok.Setter;

@Getter
@Setter
@NoArgsConstructor
@Entity
@Table(name = "pedido")
public class Pedido {

  @Id
  @GeneratedValue(strategy = GenerationType.IDENTITY)
  private Long id;

  private String cliente;

  private BigDecimal valor;

  @Enumerated(EnumType.STRING)
  private StatusPedido status = StatusPedido.ABERTO;

  /** ID da fatura devolvido pelo hub. Nulo enquanto o pedido não foi faturado. */
  private String faturaId;

  private LocalDateTime fechadoEm;

  public Pedido(String cliente, BigDecimal valor) {
    if (cliente == null || cliente.isBlank()) throw new IllegalArgumentException("Cliente obrigatório");
    if (valor == null || valor.signum() <= 0) throw new IllegalArgumentException("Valor deve ser maior que zero");
    this.cliente = cliente;
    this.valor = valor;
  }

  public void fechar() {
    if (status != StatusPedido.ABERTO) {
      throw new IllegalStateException("Só é possível fechar um pedido ABERTO");
    }
    this.fechadoEm = LocalDateTime.now();
  }

  public void registrarFatura(String faturaId) {
    this.faturaId = faturaId;
    this.status = StatusPedido.FATURADO;
  }

  public void marcarPendenteFaturamento() {
    this.status = StatusPedido.PENDENTE_FATURAMENTO;
  }
}