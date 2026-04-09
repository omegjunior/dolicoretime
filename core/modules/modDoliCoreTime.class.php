<?php
/* Copyright (C) 2026 Omega Junior <omegajunior.apps@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 */

include_once DOL_DOCUMENT_ROOT.'/core/modules/DolibarrModules.class.php';

class modDoliCoreTime extends DolibarrModules
{
	public function __construct($db)
	{
		$this->db = $db;

		$this->numero = 501100;
		$this->rights_class = 'dolicoretime';
		$this->family = 'Fred Omega Junior';
		$this->module_position = '10';
		$this->name = preg_replace('/^mod/i', '', get_class($this));
		$this->description = 'DoliCoreTimeDescription';
		$this->descriptionlong = 'DoliCoreTimeDescription';
		$this->version = '0.1.0';
		$this->const_name = 'MAIN_MODULE_'.strtoupper($this->name);
		$this->picto = 'clock';
		$this->editor_name = 'Fred Omega Junior';
		$this->editor_url = 'www.linkedin.com/in/frédéric-h-887621160';

		$this->module_parts = array(
			'triggers' => 0,
			'login' => 0,
			'substitutions' => 0,
			'menus' => 0,
			'tpl' => 0,
			'barcode' => 0,
			'models' => 0,
			'printing' => 0,
			'theme' => 0,
			'css' => array(),
			'js' => array(),
			'hooks' => array(),
			'moduleforexternal' => 0,
		);

		$this->dirs = array('/dolicoretime/temp');
		$this->config_page_url = array('setup.php@dolicoretime');
		$this->depends = array('always1' => 'modSyslog');
		$this->requiredby = array();
		$this->conflictwith = array();
		$this->langfiles = array('dolicoretime@dolicoretime');
		$this->phpmin = array(7, 4);
		$this->need_dolibarr_version = array(22, 0);

		$this->const = array(
			1 => array('DOLICORETIME_BUSINESS_TZ', 'chaine', 'UTC', 'Timezone métier commune des modules socles', 0, 'current', 1),
		);

		$this->boxes = array();
		$this->cronjobs = array();
		$this->rights = array();
		$this->menus = array();
	}
}
