<?php

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(!function_exists('vdump')) {
    function vdump($value, $die = false): void
    {
        global $USER;
        if(!is_null($USER) && $USER->IsAdmin()) {
            ?><pre style="display:block; text-align:left; border:1px solid #ccc; padding:20px; margin:15px 0; background:#152735; color:#ccc; border-radius:10px; box-shadow:1px 1px 8px rgba(0,0,0,0.6);"><?=htmlspecialchars(print_r($value,1))?></pre><?
            if($die) die;
        }
    }
}