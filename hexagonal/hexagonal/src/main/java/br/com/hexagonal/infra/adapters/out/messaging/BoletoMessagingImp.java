package br.com.hexagonal.infra.adapters.out.messaging;

import java.math.BigInteger;
import java.util.UUID;

import org.springframework.stereotype.Component;

import br.com.hexagonal.domain.entities.Fatura;
import br.com.hexagonal.domain.ports.out.messaging.BoletoMessaging;
import lombok.extern.slf4j.Slf4j;

@Slf4j
@Component
public class BoletoMessagingImp implements BoletoMessaging{

    @Override
    public String emitirBoleto(Fatura fatura){
        log.info("[FILA] Publicando solicitação de boleto da fatura {} (valor{})",
        fatura.getId(), fatura.getValor());

        String digitos = new BigInteger (UUID.randomUUID().toString().replace("-", ""), 16).toString();
        String codigoBarras = ("23790" + digitos + "0".repeat(47)).substring(0, 47);

        log.info("[FILA] Boleto emitido, código de barras {}", codigoBarras);
    return codigoBarras;

    }
}