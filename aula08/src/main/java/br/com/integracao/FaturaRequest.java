package br.com.vendas.integracao;

import java.math.BigDecimal;

public record FaturaRequest(Long pedidoId, BigDecimal valor) {
}