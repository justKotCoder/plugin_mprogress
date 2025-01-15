<?php
require_once(__DIR__ . '/../../config.php');

// Убедимся, что пользователь вошел в систему
require_login();

// Проверка прав доступа
$context = context_system::instance();
require_capability('local/mprogress:view', $context);

// Получаем текущий язык
$current_lang = current_language();

// Если язык изменен, обновляем язык
if (isset($_GET['lang'])) {
    $new_lang = $_GET['lang'];
    if (array_key_exists($new_lang, get_string_manager()->get_list_of_languages())) {
        set_user_preference('lang', $new_lang); // Устанавливаем предпочтения пользователя
        $current_lang = $new_lang; // Обновляем текущий язык
    }
}

// Установка контекста страницы
$PAGE->set_context($context);
$PAGE->set_url('/local/mprogress/index.php');
$PAGE->set_title(get_string('dashboard', 'local_mprogress'));
$PAGE->set_heading(get_string('dashboard', 'local_mprogress'));

// Подключение стилей и скриптов
$PAGE->requires->css('/local/mprogress/styles/styles.css');
$PAGE->requires->js('/local/mprogress/scripts/scripts.js');

// Создание экземпляра дашборда
$dashboard = new \local_mprogress\output\dashboard($USER->id);

// Получение рендера страницы
$output = $PAGE->get_renderer('local_mprogress');

// Рендеринг страницы
echo $output->header();
echo $output->render($dashboard);
echo $output->footer();
?>
