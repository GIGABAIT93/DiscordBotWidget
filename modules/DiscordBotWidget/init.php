<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/tree/v2/
 *  NamelessMC version 2.0.0-pr9
 *
 *  License: MIT
 *
 *  DiscordBotWidget By Mubeen & xGIGABAITx
 */

$INFO_MODULE = array(
	'name' => 'DiscordBotWidget',
	'author' => '<a href="https://lectrichost.com" target="_blank" rel="nofollow noopener">Mubeen</a> & <a href="https://tensa.co.ua" target="_blank" rel="nofollow noopener">xGIGABAITx</a>',
	'module_ver' => '1.0.1',
	'nml_ver' => '2.0.0-pr10',
);

$DiscordBotLanguage = new Language(ROOT_PATH . '/modules/' . $INFO_MODULE['name'] . '/language', LANGUAGE);

$GLOBALS['DiscordBotLanguage'] = $DiscordBotLanguage;

require_once(ROOT_PATH . '/modules/' . $INFO_MODULE['name'] . '/module.php');

$module = new DiscordBotWidget($language, $pages, $INFO_MODULE);
