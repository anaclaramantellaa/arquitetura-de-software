package br.com.hexagonal.application;

import java.math.BigDecimal;
import java.util.UUID;

import org.springframework.stereotype.Service;

import br.com.hexagonal.domain.entities.Fatura;
import br.com.hexagonal.domain.ports.in.FaturaService;
import br.com.hexagonal.domain.ports.out.messaging.BoletoMessaging;
import br.com.hexagonal.domain.ports.out.persistence.FaturaRepository;
import br.com.hexagonal.domain.ports.out.smtp.EmailSmtp;
import br.com.hexagonal.domain.ports.out.soap.NotaFiscalSoap;
import lombok.RequiredArgsConstructor;

@Service
@RequiredArgsConstructor 
public class FaturaServiceImp implements FaturaService{

  private final FaturaRepository faturaRepository;
  private final BoletoMessaging boletoMessaging;
  private final NotaFiscalSoap notaFiscalSoap;
  private final EmailSmtp emailSmtp;

  @Override
  public UUID faturarPedido(Long pedidoId, BigDecimal valor) {
    Fatura fatura = Fatura.criar(pedidoId, valor);

    fatura.inserirBoleto(boletoMessaging.emitirBoleto(fatura));    // RF02
    fatura.inserirNota(notaFiscalSoap.emitirNota(fatura));         // RF03
    fatura.inserirHashEmail(emailSmtp.enviarEmail(fatura));        // RF04

    faturaRepository.salvar(fatura);                               // RF01
    return fatura.getId();
  }
}