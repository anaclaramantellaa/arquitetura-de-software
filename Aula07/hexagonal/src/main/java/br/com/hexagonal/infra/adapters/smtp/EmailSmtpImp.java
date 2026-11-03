package br.com.hexagonal.infra.adapters.out.smtp;

import java.nio.charset.StandardCharsets;
import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;
import java.util.HexFormat;

import org.springframework.stereotype.Component;

import br.com.hexagonal.domain.entities.Fatura;
import br.com.hexagonal.domain.ports.out.smtp.EmailSmtp;
import lombok.extern.slf4j.Slf4j;

@Slf4j
@Component
public class EmailSmtpImp implements EmailSmtp {

  @Override
  public String enviarEmail(Fatura fatura) {
    String conteudo = """
      Assunto: Fatura %s do pedido %s
      Valor: R$ %s
      Boleto (código de barras): %s
      Nota fiscal (chave de acesso): %s
      """.formatted(fatura.getId(), fatura.getPedidoId(), fatura.getValor().toPlainString(),
        fatura.getCodigoBarras(), fatura.getChaveAcesso());

    log.info("[SMTP] Enviando e-mail:\n{}", conteudo);
    String hash = sha256(conteudo);
    log.info("[SMTP] E-mail enviado, hash {}", hash);
    return hash;
  }

  private String sha256(String texto) {
    try {
      byte[] h = MessageDigest.getInstance("SHA-256").digest(texto.getBytes(StandardCharsets.UTF_8));
      return HexFormat.of().formatHex(h);
    } catch (NoSuchAlgorithmException e) {
      throw new IllegalStateException(e);
    }
  }
}