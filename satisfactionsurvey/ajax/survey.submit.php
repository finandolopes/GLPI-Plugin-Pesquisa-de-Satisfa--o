
<?php
// Handle AJAX submission of the survey

define('GLPI_ROOT', '../../..');
include (GLPI_ROOT . "/inc/includes.php");
header('Content-Type: application/json');

$rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
$comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';
$ticket_id = isset($_POST['ticket_id']) ? intval($_POST['ticket_id']) : 0;

// Validação dos dados
if ($rating < 1 || $rating > 5) {
    echo json_encode(['success' => false, 'message' => 'Selecione uma nota válida (1 a 5).']);
    exit;
}
if (!$ticket_id) {
    echo json_encode(['success' => false, 'message' => 'Chamado não identificado.']);
    exit;
}
if (strlen($comment) > 500) {
    echo json_encode(['success' => false, 'message' => 'Comentário muito longo.']);
    exit;
}
// Comentário obrigatório para notas baixas
if ($rating <= 2 && strlen($comment) < 5) {
    echo json_encode(['success' => false, 'message' => 'Por favor, descreva o motivo da avaliação baixa.']);
    exit;
}

global $DB;
// Verifica se o chamado existe
$ticket_check = $DB->query("SELECT id FROM glpi_tickets WHERE id = $ticket_id LIMIT 1");
if ($DB->numrows($ticket_check) == 0) {
    echo json_encode(['success' => false, 'message' => 'Chamado não encontrado.']);
    exit;
}

// Evita duplicidade de resposta
$dup_check = $DB->query("SELECT id FROM glpi_plugin_satisfactionsurvey_responses WHERE tickets_id = $ticket_id LIMIT 1");
if ($DB->numrows($dup_check) > 0) {
    echo json_encode(['success' => false, 'message' => 'Este chamado já foi avaliado.']);
    exit;
}

// Salva resposta
$query = "INSERT INTO glpi_plugin_satisfactionsurvey_responses (tickets_id, rating, comment, date_answered) VALUES (?, ?, ?, NOW())";
$stmt = $DB->prepare($query);
if ($stmt->execute([$ticket_id, $rating, $comment])) {
    // Notificação interna para gestor se nota baixa
    if ($rating <= 2) {
        // Defina o e-mail do gestor abaixo
        $gestor_email = 'gestor@seudominio.com';
        $subject = "Alerta: Avaliação baixa no chamado #$ticket_id";
        $message = "<p>Uma avaliação de satisfação baixa foi registrada:</p>"
            . "<ul>"
            . "<li><strong>Chamado:</strong> #$ticket_id</li>"
            . "<li><strong>Nota:</strong> $rating</li>"
            . "<li><strong>Comentário:</strong> " . htmlspecialchars($comment) . "</li>"
            . "</ul>";
        include_once(GLPI_ROOT . "/inc/notificationmail.class.php");
        NotificationMail::sendSimpleMail($gestor_email, $subject, $message);
    }
    echo json_encode(['success' => true, 'message' => 'Obrigado! Sua resposta foi registrada com sucesso.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao registrar resposta. Tente novamente.']);
}
exit;
