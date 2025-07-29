<?php
// Configurações do plugin SatisfactionSurvey
return [
    // E-mail do gestor para notificações internas
    'gestor_email' => 'wellington.oliveira@cross.org.br',
    // Texto do e-mail de pesquisa
    'email_subject' => 'Pesquisa de Satisfação - Chamado #{TICKET_ID}',
    'email_message' => '<p>Olá,<br>Seu chamado foi concluído. Por favor, avalie nosso atendimento:</p><p><a href="{SURVEY_URL}" style="font-size:18px; color:#007bff;">Responder pesquisa</a></p>',
];
