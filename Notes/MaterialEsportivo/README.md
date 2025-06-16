# SportStyle Pro - Integração com Stripe

Este documento contém instruções para configurar e usar a integração com o gateway de pagamentos Stripe em sua loja virtual SportStyle Pro.

## Requisitos

- PHP 5.6 ou superior (recomendado PHP 7+)
- MySQL
- Conta na Stripe (https://stripe.com)

## Instalação

### 1. Instalar a biblioteca Stripe PHP

Existem duas maneiras de instalar a biblioteca Stripe:

#### Opção 1: Via Composer (Recomendado)

```bash
composer require stripe/stripe-php
```

#### Opção 2: Download manual

1. Baixe a biblioteca Stripe PHP do repositório oficial: https://github.com/stripe/stripe-php/releases
2. Extraia os arquivos para uma pasta chamada `stripe-php` na raiz do seu projeto

### 2. Configurar o banco de dados

Execute o script SQL incluído para criar as tabelas necessárias:

```bash
mysql -u root -p loja < pedidos.sql
```

### 3. Configurar as chaves da API

1. Acesse sua conta Stripe em https://dashboard.stripe.com/
2. Obtenha suas chaves de API (publicável e secreta)
3. Substitua as chaves nos arquivos:
   - `home.php`: Substitua `pk_test_51NzQwqSDn30SRr...` pela sua chave publicável
   - `carrinho.php` e `sucesso.php`: Substitua `sk_test_51NzQwqSDn30SRr...` pela sua chave secreta

## Como funciona

1. O usuário adiciona produtos ao carrinho
2. Ao clicar em "Finalizar Compra" no carrinho, é criada uma sessão de checkout na Stripe
3. O usuário é redirecionado para a página de pagamento da Stripe
4. Após concluir o pagamento, o usuário é redirecionado de volta para o site:
   - Pagamento bem-sucedido: `sucesso.php`
   - Pagamento cancelado: `cancelado.php`

## Testes

Para testar o sistema, você pode usar os cartões de teste da Stripe:

- Pagamento bem-sucedido: 4242 4242 4242 4242
- Pagamento recusado: 4000 0000 0000 0002

Mais cartões de teste: https://stripe.com/docs/testing#cards

## Ambiente de produção

Quando estiver pronto para ir para produção:

1. Mude para as chaves de produção da Stripe
2. Remova quaisquer logs de depuração
3. Certifique-se de que sua conexão SSL esteja funcionando corretamente, pois a Stripe exige HTTPS em produção

## Resolução de problemas

- Verifique os logs do PHP para mensagens de erro
- Consulte a documentação oficial da Stripe: https://stripe.com/docs
- Entre em contato com o suporte da Stripe para problemas relacionados a pagamentos

## Personalização

Você pode personalizar a aparência da página de checkout da Stripe no painel da Stripe:
https://dashboard.stripe.com/account/branding 