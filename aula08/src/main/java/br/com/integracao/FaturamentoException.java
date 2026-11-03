package br.com.vendas.integracao;

public class FaturamentoException extends RuntimeException {
  public FaturamentoException(String mensagem, Throwable causa) {
    super(mensagem, causa);
  }

  public FaturamentoException(String mensagem) {
    super(mensagem);
  }
}