package br.com.hexagonal.infra.adapters.out.soap;

import java.math.BigInteger;
import java.util.UUID;

import org.springframework.stereotype.Component;

import br.com.hexagonal.domain.entities.Fatura;
import br.com.hexagonal.domain.ports.out.soap.NotaFiscalSoap;
import lombok.extern.slf4j.Slf4j;

@Slf4j
@Component
public class NotaFiscalSoapImp implements NotaFiscalSoap {

  @Override
  public String emitirNota(Fatura fatura) {
    String envelope = """
      <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
        <soapenv:Body>
          <emitirNota>
            <faturaId>%s</faturaId>
            <pedidoId>%s</pedidoId>
            <valor>%s</valor>
          </emitirNota>
        </soapenv:Body>
      </soapenv:Envelope>
      """.formatted(fatura.getId(), fatura.getPedidoId(), fatura.getValor().toPlainString());
    log.info("[SOAP] Enviando requisição:\n{}", envelope);

    String digitos = new BigInteger(UUID.randomUUID().toString().replace("-", ""), 16).toString();
    String chave = (digitos + "0".repeat(44)).substring(0, 44);

    log.info("[SOAP] Nota emitida, chave de acesso {}", chave);
    return chave;
  }
}