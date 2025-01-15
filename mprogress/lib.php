<?php
function local_mprogress_before_standard_html_head() {
    global $PAGE, $CFG;

    $allowed_pages = array(
        $CFG->wwwroot . '/',
        $CFG->wwwroot . '/my',
        $CFG->wwwroot . '/my/courses.php'
    );

    if (in_array($PAGE->url->out(), $allowed_pages)) {
        $PAGE->requires->css('/local/mprogress/styles/button.css');
    }
}

function local_mprogress_before_footer() {
    global $PAGE, $CFG;

    $allowed_pages = array(
        $CFG->wwwroot . '/',
        $CFG->wwwroot . '/my',
        $CFG->wwwroot . '/my/courses.php'
    );

    if (in_array($PAGE->url->out(), $allowed_pages)) {
        $PAGE->requires->js('/local/mprogress/scripts/draggable-button.js');
    }
}