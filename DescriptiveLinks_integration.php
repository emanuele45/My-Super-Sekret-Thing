<?php

/**
 *
 * @package Descriptive Links
 * @author Spuds
 * @license This Source Code is subject to the terms of the Mozilla Public License
 * version 2.0 (the "License"). You can obtain a copy of the License at
 * http://mozilla.org/MPL/2.0/.
 *
 * @version 1.0
 *
 */

if (!defined('SMF'))
	die('Hacking attempt...');

/**
 * dlinks_integrate_admin_areas()
 *
 * add a line under modifcation config
 * @param mixed $admin_areas
 * @return
 */
function dlinks_integrate_admin_areas(&$admin_areas)
{
	global $txt;
	$admin_areas['config']['areas']['modsettings']['subsections']['dlinks'] = array($txt['mods_cat_modifications_dlinks']);
}

/**
 * dlinks_integrate_modify_modifications()
 *
 * @param mixed $sub_actions
 * @return
 */
function dlinks_integrate_modify_modifications(&$sub_actions)
{
	$sub_actions['dlinks'] = 'ModifydlinksSettings';
}

/**
 * dlinks_integrate_load_permissions()
 *
 * Permissions hook, integrate_load_permissions, called from ManagePermissions.php
 * used to add new permisssions
 *
 * @param mixed $permissionGroups
 * @param mixed $permissionList
 * @param mixed $leftPermissionGroups
 * @param mixed $hiddenPermissions
 * @param mixed $relabelPermissions
 * @return
 */
function dlinks_integrate_load_permissions(&$permissionGroups, &$permissionList, &$leftPermissionGroups, &$hiddenPermissions, &$relabelPermissions)
{
	$permissionList['board']['disable_title_convert_url'] = array(false, 'topic', 'moderate');
}

/**
 * ModifydlinksSettings()
 *
 * @param mixed $return_config
 * @return
 */
function ModifydlinksSettings($return_config = false)
{
	global $txt, $scripturl, $context, $smcFunc, $sourcedir;

	$context[$context['admin_menu_name']]['tab_data']['tabs']['dlinks']['description'] = $txt['descriptivelinks_desc'];
	$config_vars = array(
		array('check', 'descriptivelinks_enabled'),
		array('check', 'descriptivelinks_title_url'),
		array('check', 'descriptivelinks_title_internal'),
		array('check', 'descriptivelinks_title_bbcurl'),
		array('int', 'descriptivelinks_title_url_count', 'subtext' => $txt['descriptivelinks_title_url_count_sub'], 'postinput' => $txt['descriptivelinks_title_url_count_urls']),
		array('int', 'descriptivelinks_title_url_length'),
		array('text','descriptivelinks_title_url_generic', 60, 'subtext' => $txt['descriptivelinks_title_url_generic_sub']),		
	);

	if ($return_config)
		return $config_vars;

	if (isset($_GET['save']))
	{
		checkSession();
		saveDBSettings($config_vars);

		// enabling the mod then lets have the main file available, otherwise lets not ;)
		if (isset($_POST['descriptivelinks_enabled']))
			add_integration_function('integrate_pre_include', '$sourcedir/Subs-DescriptiveLinks.php');
		else
			remove_integration_function('integrate_pre_include', '$sourcedir/Subs-DescriptiveLinks.php');

		writeLog();
		redirectexit('action=admin;area=modsettings;sa=dlinks');
	}

	$context['post_url'] = $scripturl . '?action=admin;area=modsettings;save;sa=dlinks';
	$context['settings_title'] = $txt['mods_cat_modifications_dlinks'];

	prepareDBSettingContext($config_vars);
}

?>