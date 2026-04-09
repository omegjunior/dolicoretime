<?php
/* Copyright (C) 2026 Omega Junior <omegajunior.apps@gmail.com> */

require_once DOL_DOCUMENT_ROOT.'/custom/dolicoretime/lib/timecore.lib.php';

function dolicoretimeGetDefaultBusinessDayBounds()
{
	$businessTimezone = new DateTimeZone(dolicoretimeGetBusinessTimezone());
	$startDate = new DateTime('now', $businessTimezone);
	$startDate->setTime(0, 0, 0);
	$endDate = clone $startDate;
	$endDate->setTime(23, 59, 59);

	return array(
		'display_start' => $startDate->getTimestamp(),
		'display_end' => $endDate->getTimestamp(),
		'sql_start_local' => $startDate->format('Y-m-d H:i:s'),
		'sql_end_local' => $endDate->format('Y-m-d H:i:s'),
		'sql_start_utc' => gmdate('Y-m-d H:i:s', $startDate->getTimestamp()),
		'sql_end_utc' => gmdate('Y-m-d H:i:s', $endDate->getTimestamp()),
	);
}

function dolicoretimeBuildSqlDateBounds($startDate = null, $endDate = null, $storageMode = 'utc')
{
	$defaultBounds = dolicoretimeGetDefaultBusinessDayBounds();
	$useUtc = dolicoretimeUseUtcStorage($storageMode);
	$sourceTimezone = dolicoretimeGetBusinessTimezone();

	$normalize = function ($value) use ($useUtc, $sourceTimezone) {
		if ($value === null || $value === '' || $value === 0) {
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
			if ($useUtc) {
				$date->setTimezone(new DateTimeZone('UTC'));
			} else {
				$date->setTimezone(new DateTimeZone($sourceTimezone));
			}
			return $date->format('Y-m-d H:i:s');
		} catch (Exception $e) {
			return '';
		}
	};

	return array(
		'sql_start' => $normalize($startDate) ?: ($useUtc ? $defaultBounds['sql_start_utc'] : $defaultBounds['sql_start_local']),
		'sql_end' => $normalize($endDate) ?: ($useUtc ? $defaultBounds['sql_end_utc'] : $defaultBounds['sql_end_local']),
		'display_start' => ($startDate ? (is_numeric($startDate) ? (int) $startDate : strtotime((string) $startDate)) : $defaultBounds['display_start']),
		'display_end' => ($endDate ? (is_numeric($endDate) ? (int) $endDate : strtotime((string) $endDate)) : $defaultBounds['display_end']),
	);
}
