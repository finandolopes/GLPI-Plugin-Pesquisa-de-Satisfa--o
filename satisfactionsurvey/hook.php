
<?php
/**
 * Plugin SatisfactionSurvey - Hook file
 */

// Inclui as classes necessárias do GLPI
include_once(GLPI_ROOT . '/inc/user.class.php');
include_once(GLPI_ROOT . '/inc/notificationmail.class.php');

function plugin_satisfactionsurvey_ticket_close($ticket) {
    // Envia e-mail de pesquisa ao fechar chamado
    global $CFG_GLPI;
    $config = include(__DIR__ . '/config.php');
    if (!isset($ticket->fields['users_id_recipient'])) return;
    $user = new User();
    $user->getFromDB($ticket->fields['users_id_recipient']);
    $email = $user->fields['email'];
    if (!$email) return;

    $ticket_id = $ticket->fields['id'];
    $survey_url = $CFG_GLPI['root_doc'] . "/plugins/satisfactionsurvey/front/survey.form.php?ticket_id=" . $ticket_id;

    $subject = str_replace('{TICKET_ID}', $ticket_id, $config['email_subject']);
    $message = str_replace(['{SURVEY_URL}','{TICKET_ID}'], [$survey_url, $ticket_id], $config['email_message']);

    include_once(GLPI_ROOT . "/inc/notificationmail.class.php");
    NotificationMail::sendSimpleMail($email, $subject, $message);
// ...existing code...
}
