<?php
/* Copyright (C) 2026 Omega Junior <omegajunior.apps@gmail.com> */

$res = 0;
if (!$res && !empty($_SERVER['CONTEXT_DOCUMENT_ROOT'])) {
	$res = @include $_SERVER['CONTEXT_DOCUMENT_ROOT'].'/main.inc.php';
}
$tmp = empty($_SERVER['SCRIPT_FILENAME']) ? '' : $_SERVER['SCRIPT_FILENAME'];
$tmp2 = realpath(__FILE__);
$i = strlen($tmp) - 1;
$j = strlen($tmp2) - 1;
while ($i > 0 && $j > 0 && isset($tmp[$i]) && isset($tmp2[$j]) && $tmp[$i] === $tmp2[$j]) {
	$i--; $j--;
}
if (!$res && $i > 0 && file_exists(substr($tmp, 0, ($i + 1)).'/main.inc.php')) {
	$res = @include substr($tmp, 0, ($i + 1)).'/main.inc.php';
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
require_once DOL_DOCUMENT_ROOT.'/core/class/html.form.class.php';
require_once DOL_DOCUMENT_ROOT.'/custom/dolicoretime/lib/timecore.lib.php';

$langs->loadLangs(array('admin', 'dolicoretime@dolicoretime'));

if (!$user->admin) {
	accessforbidden();
}

$action = GETPOST('action', 'aZ09');
$value = GETPOST('DOLICORETIME_BUSINESS_TZ', 'alphanohtml');

if ($action === 'set_timezone') {
	if (!dolicoretimeIsValidTimezone($value)) {
		setEventMessages($langs->trans('InvalidTimezone'), null, 'errors');
	} else {
		dolibarr_set_const($db, 'DOLICORETIME_BUSINESS_TZ', $value, 'chaine', 0, '', $conf->entity);
		setEventMessages($langs->trans('SetupSaved'), null, 'mesgs');
	}
}

$form = new Form($db);
$businessTimezone = dolicoretimeGetBusinessTimezone();

llxHeader('', $langs->trans('DoliCoreTimeSetup'));
print load_fiche_titre($langs->trans('DoliCoreTimeSetupPage'));

print '<form method="post" action="'.$_SERVER['PHP_SELF'].'">';
print '<input type="hidden" name="token" value="'.newToken().'">';
print '<input type="hidden" name="action" value="set_timezone">';
print '<table class="noborder centpercent">';
print '<tr class="liste_titre">';
print '<td>'.$langs->trans('Parameter').'</td>';
print '<td>'.$langs->trans('Value').'</td>';
print '<td>'.$langs->trans('Description').'</td>';
print '</tr>';
print '<tr class="oddeven">';
print '<td>DOLICORETIME_BUSINESS_TZ</td>';
print '<td><input type="text" class="flat minwidth300" name="DOLICORETIME_BUSINESS_TZ" value="'.dol_escape_htmltag($businessTimezone).'"></td>';
print '<td>'.$langs->trans('BusinessTimezoneHelp').'</td>';
print '</tr>';
print '</table>';
print '<div class="tabsAction">';
print '<input type="submit" class="button button-save" value="'.$langs->trans('Save').'">';
print '</div>';
print '</form>';

llxFooter();
$db->close();
