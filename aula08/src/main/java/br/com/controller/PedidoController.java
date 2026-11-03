package br.com.vendas.controller;

import java.math.BigDecimal;

import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestParam;
import org.springframework.web.servlet.mvc.support.RedirectAttributes;

import br.com.vendas.model.Pedido;
import br.com.vendas.model.StatusPedido;
import br.com.vendas.service.PedidoService;
import lombok.RequiredArgsConstructor;

@Controller
@RequiredArgsConstructor
public class PedidoController {

  private final PedidoService service;

  @GetMapping({"/", "/pedidos"})
  public String listar(Model model) {
    model.addAttribute("pedidos", service.listar());
    return "pedidos";
  }

  @PostMapping("/pedidos")
  public String criar(@RequestParam String cliente, @RequestParam BigDecimal valor,
                      RedirectAttributes redirect) {
    try {
      service.criar(cliente, valor);
    } catch (IllegalArgumentException e) {
      redirect.addFlashAttribute("erro", e.getMessage());
    }
    return "redirect:/pedidos";
  }

  @PostMapping("/pedidos/{id}/fechar")
  public String fechar(@PathVariable Long id, RedirectAttributes redirect) {
    try {
      Pedido pedido = service.fechar(id);
      if (pedido.getStatus() == StatusPedido.FATURADO) {
        redirect.addFlashAttribute("sucesso", "Pedido " + id + " faturado. Fatura: " + pedido.getFaturaId());
      } else {
        redirect.addFlashAttribute("erro",
          "Pedido " + id + " fechado, mas o faturamento está pendente (hub indisponível).");
      }
    } catch (IllegalArgumentException | IllegalStateException e) {
      redirect.addFlashAttribute("erro", e.getMessage());
    }
    return "redirect:/pedidos";
  }
}