<?php
/* Copyright (C) 2026 Omega Junior <omegajunior.apps@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

/**
 * \file    dolicoretime/admin/setup.php
 * \ingroup dolicoretime
 * \brief   DoliCoreTime setup page.
 */

// Load Dolibarr environment
$res = 0;
if (!$res && !empty($_SERVER['CONTEXT_DOCUMENT_ROOT'])) {
	$res = @include $_SERVER['CONTEXT_DOCUMENT_ROOT'].'/main.inc.php';
}
$tmp = empty($_SERVER['SCRIPT_FILENAME']) ? '' : $_SERVER['SCRIPT_FILENAME'];
$tmp2 = realpath(__FILE__);
$i = strlen($tmp) - 1;
$j = strlen($tmp2) - 1;
while ($i > 0 && $j > 0 && isset($tmp[$i]) && isset($tmp2[$j]) && $tmp[$i] === $tmp2[$j]) {
	$i--;
	$j--;
}
if (!$res && $i > 0 && file_exists(substr($tmp, 0, ($i + 1)).'/main.inc.php')) {
	$res = @include substr($tmp, 0, ($i + 1)).'/main.inc.php';
}
if (!$res && $i > 0 && file_exists(dirname(substr($tmp, 0, ($i + 1))).'/main.inc.php')) {
	$res = @include dirname(substr($tmp, 0, ($i + 1))).'/main.inc.php';
}
if (!$res && file_exists('../../main.inc.php')) {
	$res = @include '../../main.inc.php';
}
if (!$res && file_exists('../../../main.inc.php')) {
	$res = @include '../../../main.inc.php';
}
if (!$res) {
	die('Include of main fails');
}

require_once DOL_DOCUMENT_ROOT.'/core/lib/admin.lib.php';
require_once '../lib/dolicoretime.lib.php';
require_once '../lib/timecore.lib.php';

/**
 * @var Conf $conf
 * @var DoliDB $db
 * @var HookManager $hookmanager
 * @var Translate $langs
 * @var User $user
 */

$langs->loadLangs(array('admin', 'dolicoretime@dolicoretime'));

// Initialize a technical object to manage hooks of page.
$hookmanager->initHooks(array('dolicoretimesetup', 'globalsetup'));

$action = GETPOST('action', 'aZ09');
$backtopage = GETPOST('backtopage', 'alpha');
$modulepart = GETPOST('modulepart', 'aZ09'); // Used by actions_setmoduleoptions.inc.php

$error = 0;
$setupnotempty = 0;
$useFormSetup = 1;

if (!$user->admin) {
	accessforbidden();
}

if (!class_exists('FormSetup')) {
	if (floatval(DOL_VERSION) < 16.0 && !class_exists('FormSetup')) {
		require_once __DIR__.'/../backport/v16/core/class/html.formsetup.class.php';
	} else {
		require_once DOL_DOCUMENT_ROOT.'/core/class/html.formsetup.class.php';
	}
}

$formSetup = new FormSetup($db);

$item = $formSetup->newItem('DOLICORETIME_BUSINESS_TZ');
$item->defaultFieldValue = dolicoretimeGetBusinessTimezone();
$item->nameText = $langs->transnoentities('BusinessTimezone');
$item->helpText = $langs->transnoentities('BusinessTimezoneHelp');
$item->cssClass = 'minwidth300';

if (!dolicoretimeIsValidTimezone((string) $item->fieldValue)) {
	$item->fieldValue = dolicoretimeGetBusinessTimezone();
}

$item->setValueFromPostCallBack(function () use ($item, $langs) {
	$timezone = trim((string) GETPOST('DOLICORETIME_BUSINESS_TZ', 'alphanohtml'));
	$item->fieldValue = $timezone;

	if (!dolicoretimeIsValidTimezone($timezone)) {
		setEventMessages($langs->trans('InvalidTimezone'), null, 'errors');
		return -1;
	}

	return 1;
});

$setupnotempty += count($formSetup->items);

if ($action === 'update' && !empty($user->admin)) {
	$result = $formSetup->saveConfFromPost(true);
	if ($result > 0) {
		setEventMessages($langs->trans('SetupSaved'), null, 'mesgs');
	} elseif ($result < 0) {
		if (empty($_SESSION['dol_events']['errors'])) {
			setEventMessages($langs->trans('SetupNotSaved'), null, 'errors');
		}
	}
}

$action = 'edit';

/*
 * View
 */

$help_url = '';
$page_name = 'DoliCoreTimeSetup';

llxHeader('', $langs->trans($page_name), $help_url, '', 0, 0, '', '', '', 'mod-dolicoretime page-admin');

$linkback = '<a href="'.($backtopage ? $backtopage : DOL_URL_ROOT.'/admin/modules.php?restore_lastsearch_values=1').'">'.$langs->trans('BackToModuleList').'</a>';

print load_fiche_titre($langs->trans($page_name), $linkback, 'title_setup');

$head = dolicoretimeAdminPrepareHead();
print dol_get_fiche_head($head, 'settings', $langs->trans($page_name), -1, 'dolicoretime@dolicoretime');

print '<span class="opacitymedium">'.$langs->trans('DoliCoreTimeSetupPage').'</span><br><br>';

if (!empty($formSetup->items)) {
	print $formSetup->generateOutput(true);
	print '<br>';
} elseif (empty($setupnotempty)) {
	print '<br>'.$langs->trans('NothingToSetup');
}

print dol_get_fiche_end();

llxFooter();
$db->close();
