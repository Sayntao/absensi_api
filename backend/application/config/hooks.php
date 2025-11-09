<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/userguide3/general/hooks.html
|
*/
$hook['pre_controller'][] = array(
    'class'    => 'CorsHook',
    'function' => 'setCorsHeaders',
    'filename' => 'CorsHook.php',
    'filepath' => 'hooks',
    'params'   => array()
);
$hook['post_controller_constructor'][] = array(
    'class'    => 'Token_check',
    'function' => 'verify_jwt',
    'filename' => 'Token_check.php',
    'filepath' => 'hooks',
    'params'   => array()
);
