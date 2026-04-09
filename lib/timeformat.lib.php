<?php
/* Copyright (C) 2026 Omega Junior <omegajunior.apps@gmail.com> */

require_once DOL_DOCUMENT_ROOT.'/custom/dolicoretime/lib/timecore.lib.php';

function dolicoretimeFormatBusinessDate($value, $format = 'd/m/Y H:i:s', $sourceTimezone = 'UTC')
{
	if ($value === null || $value === '') {
		return '';
	}

	try {
		if ($value instanceof DateTimeInterface) {
			$date = new DateTime('@'.$value->getTimestamp());
		} elseif (is_numeric($value)) {
			$date = new DateTime('@'.(int) $value);
		} else {
			$date = new DateTime((string) $value, new DateTimeZone($sourceTimezone));
		}

		$date->setTimezone(new DateTimeZone(dolicoretimeGetBusinessTimezone()));
		return $date->format($format);
	} catch (Exception $e) {
		return '';
	}
}

function dolicoretimeGetBusinessTimestamp($value, $sourceTimezone = 'UTC')
{
	if ($value === null || $value === '') {
		return 0;
	}

	try {
		if ($value instanceof DateTimeInterface) {
			return (int) $value->getTimestamp();
		}
		if (is_numeric($value)) {
			return (int) $value;
		}

		$date = new DateTime((string) $value, new DateTimeZone($sourceTimezone));
		$date->setTimezone(new DateTimeZone(dolicoretimeGetBusinessTimezone()));
		return (int) $date->getTimestamp();
	} catch (Exception $e) {
		return 0;
	}
}
