package br.com.vendas.integracao;

import java.math.BigDecimal;

import org.springframework.beans.factory.annotation.Value;
import org.springframework.stereotype.Component;
import org.springframework.web.client.RestClient;
import org.springframework.web.client.RestClientException;
@Component
public class FaturamentoClient {

  private final RestClient restClient;

  public FaturamentoClient(RestClient.Builder builder, @Value("${hub.url}") String hubUrl) {
    this.restClient = builder.baseUrl(hubUrl).build();
  }

  public String faturar(Long pedidoId, BigDecimal valor) {
    try {
      FaturaResponse resposta = restClient.post()
        .uri("/fatura")
        .body(new FaturaRequest(pedidoId, valor))
        .retrieve()
        .body(FaturaResponse.class);

      if (resposta == null || resposta.faturaId() == null || resposta.faturaId().isBlank()) {
        throw new FaturamentoException("O hub respondeu sem o ID da fatura");
      }
      return resposta.faturaId();
    } catch (RestClientException e) {
      throw new FaturamentoException("Falha ao chamar o hub de faturamento", e);
    }
  }
}