<?php
require_once(__DIR__ . '/../../config.php');

require_login();
$context = context_system::instance();
require_capability('local/mprogress:view', $context);

$PAGE->set_context($context);
$PAGE->set_url('/local/mprogress/index.php');
$PAGE->set_title(get_string('dashboard', 'local_mprogress'));
$PAGE->set_heading(get_string('dashboard', 'local_mprogress'));

// Подключаем стили
$PAGE->requires->css('/local/mprogress/style/styles.css');

$output = $PAGE->get_renderer('local_mprogress');
echo $output->header();
echo $output->render(new \local_mprogress\output\dashboard($USER->id));
echo $output->footer();