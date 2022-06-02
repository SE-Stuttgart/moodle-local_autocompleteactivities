<?php

defined('MOODLE_INTERNAL') || die();

// catching all moodle events: eventname = *
$observers = array(
    array(
        'eventname'   => '\core\event\course_module_completion_updated',
        'callback'    => '\local_autocompleteactivities\observer::course_module_completion_updated',
        'schedule'    => 'instant',
    ),
);
