# Atividade 03

## Cliente-Servidor

Esse estilo arquitetural é dividido em duas partes:

- **Cliente:** faz as solicitações que deseja e apresenta as informações necessárias ao usuário.
- **Servidor:** recebe as solicitações, processa os dados e devolve a resposta.

Esse estilo é recomendado em sistemas onde diversos usuários precisam acessar dados e serviços centralizados, como **sistemas bancários** (nos quais o servidor recebe as informações do usuário e consulta seu saldo, faz Pix e verifica as transações) e **lojas virtuais** (onde o cliente acessa o app, e o servidor gerencia os produtos, estoque, pedidos, etc.).

Ele é muito utilizado em controle de acesso, segurança e quando se tem clientes em massa acessando ao mesmo tempo.

### Vantagens

- Centralização de dados;
- Segurança;
- Facilidade de manutenção;
- Compartilhamento;
- Escalabilidade.

### Desvantagens

- Dependência do servidor;
- Custo;
- Congestionamento;
- Dependência.

---

## Monolítico

Nesse estilo, todas as partes do sistema ficam reunidas em uma única aplicação, como a interface, regras de negócio e acesso ao banco de dados.

É a melhor opção a ser escolhida por um sistema que não é muito grande e quando a equipe precisa desenvolver e manter as coisas de forma mais simples.

Esse sistema é recomendado em **sistemas de pequenas e médias empresas** (sistema de estoque, vendas e cadastro de clientes) e **aplicações menores de web** (como sites de lojas, sistema acadêmico e sistema de aluguel).

### Vantagens

- É mais simples de compreender e desenvolver;
- Fácil manutenção;
- Implantação mais simples;
- Comunicação rápida.

### Desvantagens

- Difícil escala de partes específicas;
- A manutenção pode acabar ficando complicada conforme o sistema cresce;
- Uma pequena alteração pode afetar outras partes do sistema;
- Pode ficar grande e difícil de gerenciar.
