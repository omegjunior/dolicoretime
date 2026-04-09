<?php
/* Copyright (C) 2026 Omega Junior <omegajunior.apps@gmail.com> */

function dolicoretimeIsValidTimezone($timezone)
{
	if (!is_string($timezone) || $timezone === '') {
		return false;
	}

	try {
		new DateTimeZone($timezone);
		return true;
	} catch (Exception $e) {
		return false;
	}
}

function dolicoretimeGetBusinessTimezone()
{
	$timezone = getDolGlobalString('DOLICORETIME_BUSINESS_TZ');
	if (!dolicoretimeIsValidTimezone($timezone)) {
		$serverTimezone = getDolGlobalString('MAIN_SERVER_TZ');
		if (dolicoretimeIsValidTimezone($serverTimezone)) {
			return $serverTimezone;
		}
		return 'UTC';
	}

	return $timezone;
}

function dolicoretimeGetUtcNowTimestamp()
{
	return time();
}

function dolicoretimeUseUtcStorage($storageMode)
{
	return is_string($storageMode) && strtolower(trim($storageMode)) === 'utc';
}
