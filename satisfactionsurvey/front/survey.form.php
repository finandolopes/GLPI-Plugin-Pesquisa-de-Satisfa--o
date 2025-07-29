
<?php
// Exibe o formulário de pesquisa conforme configuração
$form_config = include(__DIR__ . '/../config_form.php');
$form_type = $form_config['survey_form_type'] ?? 'form1';
$ticket_id = isset($_GET['ticket_id']) ? intval($_GET['ticket_id']) : 0;

if ($form_type === 'form2') {
    $form_file = '../templates/form2.html';
} else {
    $form_file = '../templates/form.html';
}

$form_html = file_get_contents($form_file);
$form_html = str_replace('{{TICKET_ID}}', $ticket_id, $form_html);
echo $form_html;
