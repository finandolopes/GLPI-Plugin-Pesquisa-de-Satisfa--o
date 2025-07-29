# Plugin SatisfactionSurvey para GLPI

## Visão Geral
O SatisfactionSurvey é um plugin moderno para GLPI que permite enviar pesquisas de satisfação automaticamente ao cliente após o fechamento de chamados, coletar respostas, analisar resultados e gerar relatórios visuais.

## Recursos
- Envio automático de pesquisa por e-mail ao fechar chamado
- Formulário responsivo com emojis e campo de comentário
- Armazenamento das respostas no banco de dados
- Dashboard com filtros, exportação CSV e gráficos
- Notificação interna para gestor em caso de avaliação baixa
- Controle de permissão para acesso ao dashboard
- Página de agradecimento após resposta
- Internacionalização (suporte a múltiplos idiomas)
- Logs de auditoria de acesso/exportação
- Comentário obrigatório para notas baixas
- Personalização do texto do e-mail

## Instalação

1. **Pré-requisitos**
   - GLPI 10.x (Community, auto-hospedado)
   - Permissão de administrador no GLPI

2. **Copie o plugin para o GLPI**
   - Renomeie a pasta do plugin para `satisfactionsurvey`.
   - Mova para o diretório `glpi/plugins/satisfactionsurvey`.

3. **Permissões**
   - Garanta que o usuário do servidor web tenha permissão de leitura e escrita na pasta do plugin.

4. **Instale e ative o plugin**
   - Acesse o GLPI como administrador.
   - Vá em **Configurar > Plugins**.
   - Localize o plugin SatisfactionSurvey, clique em **Instalar** e depois em **Ativar**.

5. **Configuração**
   - Edite o arquivo `config.php` para ajustar e-mails, textos e opções do plugin.

## Utilização

- Ao fechar um chamado, o cliente recebe um e-mail com link para a pesquisa.
- As respostas são salvas e podem ser visualizadas no dashboard do plugin.
- O dashboard permite filtrar por nota, período, exportar dados e visualizar gráficos.
- Gestores recebem alerta por e-mail em caso de avaliação baixa.
- Logs de auditoria são gerados em `audit.log`.

## Mockup Visual
Veja abaixo um exemplo visual dos principais recursos do plugin:

---

<!-- Mockup HTML -->
<html>
<head>
  <title>Mockup - Instalação e Recursos SatisfactionSurvey</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #f8fafc; }
    .mockup-img { border-radius: 1rem; box-shadow: 0 0 12px #ccc; }
    .step { margin-bottom: 2rem; }
  </style>
</head>
<body>
<div class="container py-4">
  <h1 class="mb-4">Mockup - Instalação e Recursos</h1>
  <div class="step">
    <h4>1. Instalação do Plugin</h4>
    <img src="https://i.imgur.com/2QwQwQw.png" alt="Instalação GLPI" class="img-fluid mockup-img mb-2" style="max-width:600px">
    <p>O plugin aparece na lista de plugins do GLPI. Basta clicar em <b>Instalar</b> e <b>Ativar</b>.</p>
  </div>
  <div class="step">
    <h4>2. E-mail de Pesquisa</h4>
    <img src="https://i.imgur.com/3QwQwQw.png" alt="E-mail Pesquisa" class="img-fluid mockup-img mb-2" style="max-width:600px">
    <p>O cliente recebe um e-mail moderno e responsivo com link para a pesquisa.</p>
  </div>
  <div class="step">
    <h4>3. Formulário de Satisfação</h4>
    <img src="https://i.imgur.com/4QwQwQw.png" alt="Formulário Pesquisa" class="img-fluid mockup-img mb-2" style="max-width:600px">
    <p>Formulário intuitivo, com emojis para avaliação e campo de comentário.</p>
  </div>
  <div class="step">
    <h4>4. Dashboard de Resultados</h4>
    <img src="https://i.imgur.com/5QwQwQw.png" alt="Dashboard" class="img-fluid mockup-img mb-2" style="max-width:600px">
    <p>Dashboard com filtros, exportação CSV, gráficos e controle de permissão.</p>
  </div>
  <div class="step">
    <h4>5. Página de Agradecimento</h4>
    <img src="https://i.imgur.com/6QwQwQw.png" alt="Agradecimento" class="img-fluid mockup-img mb-2" style="max-width:600px">
    <p>Após responder, o usuário é redirecionado para uma página de agradecimento personalizada.</p>
  </div>
</div>
</body>
</html>

---

## Estrutura de Pastas
```
satisfactionsurvey/
├── ajax/
├── front/
├── templates/
├── locales/
├── config.php
├── setup.php
├── hook.php
├── satisfactionsurvey.class.php
├── README.md
```

## Suporte e Customização
Para dúvidas, sugestões ou customizações, consulte a documentação do GLPI ou entre em contato com o desenvolvedor do plugin.
