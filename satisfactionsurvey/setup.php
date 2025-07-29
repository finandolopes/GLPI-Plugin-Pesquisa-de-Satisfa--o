<?php
/**
 * Plugin SatisfactionSurvey - Setup file
 */

function plugin_satisfactionsurvey_install() {
    // Create DB table for responses
    global $DB;
    $query = "CREATE TABLE IF NOT EXISTS `glpi_plugin_satisfactionsurvey_responses` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `tickets_id` INT NOT NULL,
        `rating` INT NOT NULL,
        `comment` TEXT,
        `date_answered` DATETIME NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    $DB->query($query);
    return true;
}

function plugin_satisfactionsurvey_uninstall() {
    global $DB;
    $DB->query("DROP TABLE IF EXISTS `glpi_plugin_satisfactionsurvey_responses`");
    return true;
}
