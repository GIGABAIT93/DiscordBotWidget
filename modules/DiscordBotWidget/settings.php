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

$DiscordBotLanguage = $GLOBALS['DiscordBotLanguage'];
$page_title = $DiscordBotLanguage->get('general', 'title');

if ($user->isLoggedIn()) {
	if (!$user->canViewStaffCP()) {

		Redirect::to(URL::build('/'));
		die();
	}
	if (!$user->isAdmLoggedIn()) {

		Redirect::to(URL::build('/panel/auth'));
		die();
	} else {
		if (!$user->hasPermission('admincp.dsbot')) {
			require_once(ROOT_PATH . '/403.php');
			die();
		}
	}
} else {
	// Not logged in
	Redirect::to(URL::build('/login'));
	die();
}

define('PAGE', 'panel');
define('PARENT_PAGE', 'dsbot_items');
define('PANEL_PAGE', 'dsbot_items');

require_once(ROOT_PATH . '/core/templates/backend_init.php');


$smarty->assign(array(
	'SUBMIT' => $language->get('general', 'submit'),
	'YES' => $language->get('general', 'yes'),
	'NO' => $language->get('general', 'no'),
	'BACK' => $language->get('general', 'back'),
	'BACK_LINK' => URL::build('/panel/nextgen'),
	'ARE_YOU_SURE' => $language->get('general', 'are_you_sure'),
	'CONFIRM_DELETE' => $language->get('general', 'confirm_delete'),
	'NAME' => $language->get('admin', 'name'),
	'DESCRIPTION' => $language->get('admin', 'description'),
	'DISCORD_ID_LABEL' => $DiscordBotLanguage->get('general', 'discord_id_label'),
	'CHANNEL_ID_LABEL' => $DiscordBotLanguage->get('general', 'channel_id_label'),
	'COLOR_BTN_LABEL' => $DiscordBotLanguage->get('general', 'color_btn_label'),
	'HORIZONTAL_LOCATION_LABEL' => $DiscordBotLanguage->get('general', 'horizontal_location_label'),
	'VERTICAL_LOCATION_LABEL' => $DiscordBotLanguage->get('general', 'vertical_location_label'),

));

$settings_data = $queries->getWhere('ds_bot', array('id', '<>', 0));
if (count($settings_data)) {
	foreach ($settings_data as $key => $value) {
		$settings_data_array[$value->name] = array(
			'id' => Output::getClean($value->id),
			'value' => Output::getClean($value->value)
		);
		$smarty->assign(array(
			strtoupper($value->name) => $settings_data_array[$value->name]['value']
		));
	}
}

if (Input::exists()) {
	$errors = array();
	if (Token::check(Input::get('token'))) {

		foreach ($_POST as $key => $value) {

			if ($key == 'token') {
				continue;
			}


			$validate = new Validate();
			$validation = $validate->check($_POST, array(
				'token' => array(
					'required' => true
				)
			));

			if ($validation->passed()) {
				try {
					try {
						$queries->update('ds_bot', $settings_data_array[$key]['id'], array(
							'value' => Input::get($key)
						));
					} catch (Exception $e) {
						$queries->create('ds_bot',  array(
							'name' => $key,
							'value' => Input::get($key)
						));
					}
				} catch (Exception $e) {
					$errors[] = $e->getMessage();
				}
			} else {
				$errors[] = $DiscordBotLanguage->get('general', 'save_errors');
			}
		}
	} else {
		$errors[] = $language->get('general', 'invalid_token');
	}
	if (empty($errors)) {
		Session::flash('staff', $DiscordBotLanguage->get('general', 'save_successfully'));
		Redirect::to(URL::build('/panel/ds-bot'));
		die();
	}
}

$template_file = 'discordbotwidget/settings.tpl';


// Load modules + template
Module::loadPage($user, $pages, $cache, $smarty, array($navigation, $cc_nav, $mod_nav), $widgets, $template);
$page_load = microtime(true) - $start;
define('PAGE_LOAD_TIME', str_replace('{x}', round($page_load, 3), $language->get('general', 'page_loaded_in')));
$template->onPageLoad();

if (Session::exists('staff'))
	$success = Session::flash('staff');

if (isset($success))
	$smarty->assign(array(
		'SUCCESS' => $success,
		'SUCCESS_TITLE' => $language->get('general', 'success')
	));

if (isset($errors) && count($errors))
	$smarty->assign(array(
		'ERRORS' => $errors,
		'ERRORS_TITLE' => $language->get('general', 'error')
	));

$smarty->assign(array(
	'TOKEN' => Token::get(),
));

require(ROOT_PATH . '/core/templates/panel_navbar.php');

$template->displayTemplate($template_file, $smarty);
