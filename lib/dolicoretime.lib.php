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
 * \file    dolicoretime/lib/dolicoretime.lib.php
 * \ingroup dolicoretime
 * \brief   Library files with common functions for DoliCoreTime.
 */

/**
 * Prepare admin pages header.
 *
 * @return array<array{string,string,string}>
 */
function dolicoretimeAdminPrepareHead()
{
	global $conf, $langs;

	$langs->load('dolicoretime@dolicoretime');

	$h = 0;
	$head = array();

	$head[$h][0] = dol_buildpath('/dolicoretime/admin/setup.php', 1);
	$head[$h][1] = $langs->trans('Settings');
	$head[$h][2] = 'settings';
	$h++;

	complete_head_from_modules($conf, $langs, null, $head, $h, 'dolicoretime@dolicoretime');
	complete_head_from_modules($conf, $langs, null, $head, $h, 'dolicoretime@dolicoretime', 'remove');

	return $head;
}
