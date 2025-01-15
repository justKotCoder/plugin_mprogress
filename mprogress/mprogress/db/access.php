<?php
defined('MOODLE_INTERNAL') || die();

$capabilities = [
    'local/mprogress:view' => [
        'captype' => 'read', // Тип права: только чтение
        'contextlevel' => CONTEXT_SYSTEM, // Уровень контекста: пользовательский
        'archetypes' => [
            'student' => CAP_ALLOW, // Разрешение для роли студент
            'teacher' => CAP_ALLOW, // Разрешение для роли преподаватель
            'manager' => CAP_ALLOW, // Разрешение для роли менеджер
            'admin' => CAP_ALLOW,   // Администратор (опционально)
        ],
    ],
];