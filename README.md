# 🥗 Site em PHP para Emagrecimento + Área do Cliente + Painel Unificado PIX

Plataforma completa em **PHP** para venda de produtos/cursos de emagrecimento, com:

- Site institucional (home, produtos, depoimentos, blog, FAQ, sobre etc.)
- Checkout com **PIX** em tempo real
- Área do cliente com cursos, receitas e histórico de pedidos
- Sistema ADM simples baseado em arquivos **JSON**
- **Painel Unificado** para monitorar:
  - Acessos em tempo real (Brasil / Internacional / Bots)
  - Pagamentos PIX em tempo real, direto dos logs da API :contentReference[oaicite:0]{index=0}  

---

## 🚀 Principais Funcionalidades

### 🛒 Loja / Frontend

- Página inicial focada em **emagrecimento**:
  - Apresentação da metodologia
  - Seção de benefícios
  - Chamada para o produto principal
- Páginas de produto:
  - `produto.php`, `produtos.php`
  - Kits (ex: detox, cetogênico, “seca tudo” etc.)
- Blog e conteúdo:
  - `blog/post-*.jpg` com instruções de imagens
- Depoimentos:
  - Fotos e vídeos em `assets/images/depoimentos/` e `assets/images/videos/`

### 👩‍💻 Área do Cliente

- Cadastro e login:
  - `cadastro.php`, `register.php`, `login.php`, `logout.php`
- Área logada:
  - `minha-conta.php`, `profile.php`
  - `my-purchases.php` – compras
  - `my-recipes.php` – receitas liberadas
- Conteúdo liberado automaticamente após pagamento PIX aprovado
  - Base em `data/users.json`, `data/transactions.json`, `data/orders.json`

### 🧾 Pagamentos PIX

Módulos em `api/pix/`:

- `create.php` – cria cobrança PIX
- `qrcode.php` – gera QR Code / payload
- `check_status.php` – consulta status
- `pix_webhook.php` – recebe notificações da API e atualiza os JSON de pedidos/transações

Outros helpers da API:

- `api/save_customer.php`, `api/save-client-data.php` – salvam dados de cliente
- `api/transactions/get.php` – lista transações
- `api/upload.php` – upload de arquivos (ex: comprovantes)

Configurações:

- `config/config.php` – configurações gerais do site
- `api/_env.php` – chaves/segredos da integração PIX (não versionar com dados reais em produção)

### 📊 Painel Unificado – Monitoramento + PIX (tempo real)

Página dedicada em **HTML + JS puro** para acompanhamento em tempo real: `painel-unificado.html`. :contentReference[oaicite:1]{index=1}  

**Funcionalidades principais:**

- Tela de **login** do painel (usuário `admin` + senha configurável no JS)
- Monitor de acessos (lado esquerdo):
  - Contadores:
    - Total de acessos
    - Brasil
    - Internacional
    - Bots (Googlebot / Facebook crawler)
  - Lista em tempo real com:
    - Data/Hora
    - IP
    - País (com bandeira)
    - Tipo (Brasil / Internacional / Google / Facebook)
    - Organização (ASN / provedor)
  - Leitura periódica via endpoint:
    - `painel-api.php?since=...&limit=...&enrich=1`
- Monitor PIX (lado direito):
  - KPIs:
    - Total de transações
    - Novas
    - Aguardando
    - Pagas
  - Tabela em tempo real com:
    - Status (badge colorido)
    - Valor
    - Cliente (e-mail)
    - Txid / ID
    - Data
  - Leitura do log:
    - `PIX_API = '/api/tmp/create_log.txt'` (cada linha com JSON da transação)
- Recursos extras:
  - Botão **“Iniciar/Pausar Monitoramento”** (acessos)
  - Botões para ativar/desativar som de:
    - Novos acessos
    - Novos PIX
  - Botão **“Limpar Tudo”** limpa as listas e zera os contadores
  - Sons diferentes para acessos e PIX (toques em `/api/tmp/toque*.mp3`)
  - Layout responsivo (desktop / tablet / mobile)

---

## 🛠 Tecnologias Utilizadas

- **Backend**
  - PHP 7+ (páginas `.php` + API)
  - Persistência em arquivos `.json` (sem banco relacional)
- **Frontend**
  - HTML5 / CSS3 (`assets/css/style.css`, `styles/globals.css`)
  - JavaScript (`assets/js/main.js` + JS do painel unificado)
- **Pagamentos**
  - Integração PIX via API externa (configurada em `_env.php` / `config.php`)
- **Armazenamento**
  - Arquivos JSON em `data/`:
    - `users.json`, `orders.json`, `transactions.json`, `courses.json`, `recipes.json`

---

## 📂 Estrutura Simplificada do Projeto

```text
/
├── api/
│   ├── _env.php
│   ├── bootstrap.php
│   ├── pix/
│   │   ├── create.php
│   │   ├── qrcode.php
│   │   ├── check_status.php
│   │   └── ...
│   ├── pix_webhook.php
│   ├── transactions/get.php
│   └── ...
├── assets/
│   ├── css/style.css
│   ├── js/main.js
│   └── images/...
├── config/config.php
├── data/
│   ├── users.json
│   ├── orders.json
│   ├── transactions.json
│   ├── courses.json
│   ├── recipes.json
│   └── tmp/ (logs, cache de PIX, etc.)
├── includes/
│   ├── header.php
│   ├── footer.php
│   ├── auth.php
│   ├── functions.php
│   ├── products.php
│   └── transactions.php
├── index.php
├── checkout.php
├── pagamento.php
├── painel-acessos.php
├── painel-api.php
├── painel-unificado.html
└── ...
▶️ Como Rodar em Ambiente Local
Requisitos

PHP 7+ (XAMPP, WampServer ou similar)

Servidor HTTP (Apache ou Nginx)

Extensões padrão do PHP habilitadas

Instalação

Copie o projeto para a pasta do servidor, por exemplo:

C:\xampp\htdocs\site-emagrecimento

Configure:

config/config.php (URL base, nome do site etc.)

api/_env.php (chaves da API PIX, webhook, etc.)

Permissões

Garanta que as pastas abaixo sejam graváveis pelo PHP:

data/

data/tmp/

api/tmp/ (logs PIX, sons, etc.)

Acessar o site

Abra no navegador:

http://localhost/site-emagrecimento/index.php

Acessar o Painel Unificado

Acesse:

http://localhost/site-emagrecimento/painel-unificado.html

Faça login com o usuário configurado (padrão: admin + senha definida no JS).

Clique em “Iniciar Monitoramento” para começar a puxar os acessos.

O painel de PIX começa a ler automaticamente o log em /api/tmp/create_log.txt.

⚠ Em produção, proteja o painel-unificado.html atrás de autenticação (ex.: .htaccess ou área ADM) e mantenha api/_env.php e config/config.php fora do acesso público.


Troque a senha padrão do painel e, se possível, integre com o sistema de login ADM.

👨‍💻 Autor
Matheus – Cub Tecnologia Dev
Soluções em PHP, Node.js e Python para automação, painéis de monitoramento e sistemas de venda online.
📧 cubtecnologia.dev@gmail.com
