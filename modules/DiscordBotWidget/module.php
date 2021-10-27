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

class DiscordBotWidget extends Module
{

	private $_language, $DiscordBotLanguage;

	public function __construct($language, $pages, $INFO_MODULE)
	{
		$this->_language = $language;

		$this->DiscordBotLanguage = $GLOBALS['DiscordBotLanguage'];

		$this->module_name = $INFO_MODULE['name'];
		$author = $INFO_MODULE['author'];
		$module_version = $INFO_MODULE['module_ver'];
		$nameless_version = $INFO_MODULE['nml_ver'];
		parent::__construct($this, $this->module_name, $author, $module_version, $nameless_version);

		// StaffCP
		$pages->add($this->module_name, '/panel/ds-bot', 'settings.php');
	}

	public function onInstall()
	{

		try {
			$engine = Config::get('mysql/engine');
			$charset = Config::get('mysql/charset');
		} catch (Exception $e) {
			$engine = 'InnoDB';
			$charset = 'utf8mb4';
		}
		if (!$engine || is_array($engine))
			$engine = 'InnoDB';

		if (!$charset || is_array($charset))
			$charset = 'latin1';

		// Queries
		$queries = new Queries();

		try {
			$queries->createTable("ds_bot", "`id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(255) NOT NULL, `value` varchar(5000) NOT NULL, PRIMARY KEY (`id`)", "ENGINE=$engine DEFAULT CHARSET=$charset");
		} catch (Exception $e) {
			var_dump($e);
		}
	}

	public function onUninstall()
	{
	}

	public function onEnable()
	{

		$queries = new Queries();

		try {

			$group = $queries->getWhere('groups', array('id', '=', 2));
			$group = $group[0];

			$group_permissions = json_decode($group->permissions, TRUE);
			$group_permissions['admincp.dsbot'] = 1;

			$group_permissions = json_encode($group_permissions);
			$queries->update('groups', 2, array('permissions' => $group_permissions));
		} catch (Exception $e) {
			// Ошибка
		}
	}

	public function onDisable()
	{
	}

	public function onPageLoad($user, $pages, $cache, $smarty, $navs, $widgets, $template)
	{

		PermissionHandler::registerPermissions($this->module_name, array(
			'admincp.dsbot' => $this->DiscordBotLanguage->get('general', 'group_permision')
		));

		$icon = '<i class="fab fa-discord"></i>';
		$order = 40;

		if (defined('FRONT_END')) {

			$queries = new Queries();
			$settings_data = $queries->getWhere('ds_bot', array('id', '<>', 0));
			if (count($settings_data)) {

				foreach ($settings_data as $value) {
					if ($value->name == 'discord_id') {
						$discord_id = Output::getClean($value->value);
					} elseif ($value->name == 'channel_id') {
						$channel_id = Output::getClean($value->value);
					} elseif ($value->name == 'color_btn') {
						$color_btn = Output::getClean($value->value);
					} elseif ($value->name == 'btn_horizontal') {
						$btn_horizontal = Output::getClean($value->value);
					} elseif ($value->name == 'btn_vertical') {
						$btn_vertical = Output::getClean($value->value);
					}
				}

				if (PAGE != 404) {

					if (PAGE == 'index' or PAGE == 'status' or PAGE == 'forum' or PAGE == 'rules' or PAGE == 'voted' or PAGE == 'McTrade' or is_numeric(PAGE)) {
						$template->addJSFiles(array(
							'https://cdn.jsdelivr.net/npm/@widgetbot/crate@3' => array()
						));

						$template->addJSScript('
					new Crate({
						server: \'' . $discord_id . '\',
						channel: \'' . $channel_id . '\',
						location: [\'' . $btn_horizontal . '\', \'' . $btn_vertical . '\'],
						color: \'' . $color_btn . '\',
						defer: true,
					})
					');
					}
				}
			}
		}

		if (defined('BACK_END')) {

			$title = $this->DiscordBotLanguage->get('general', 'title');


			if ($user->hasPermission('admincp.dsbot')) {

				$navs[2]->add('dsbot_divider', mb_strtoupper($title, 'UTF-8'), 'divider', 'top', null, $order, '');

				$navs[2]->add('dsbot_items', $title, URL::build('/panel/ds-bot'), 'top', null, $order + 0.1, $icon);
			}
		}
	}
}
