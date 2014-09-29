<?php
namespace Flux;

class Timezone {
	
	protected static $__timezones;
	protected static $__formatted_timezones;
	
	/**
	 * Returns an array of timezones
	 * @return array
	 */
	public static function getDefaultTimezone() {
		return 'America/Los_Angeles';
	}
	
	/**
	 * Returns an array of timezones
	 * @return array
	 */
	public static function retrieveTimezones() {
		if(is_null(static::$__timezones)) {
			static::$__timezones = \DateTimeZone::listIdentifiers();
		}
		return static::$__timezones;
	}
	
	/**
	 * Returns an array of formatted timezones
	 * @return array
	 */
	public static function retrieveTimezonesFormatted() {
		if(is_null(static::$__formatted_timezones)) {
			//$timezones = timezone::retrieveTimezones();
			$timezones = array(
				'America/Los_Angeles' => 'America/Los Angeles',
				'America/Phoenix' => 'America/Phoenix',
				'America/Denver' => 'America/Denver',
				'America/Chicago' => 'America/Chicago',
				'America/Detroit' => 'America/Detroit',
				'America/New_York' => 'America/New York'
			);
			foreach($timezones AS $timezone_id => $timezone_name) {
				$timezone = new \DateTimeZone($timezone_id);
				$time = new \DateTime('now', $timezone);
				$offset = $timezone->getOffset($time);
				/*$is_daylight_savings = $time->format('I');
				if($is_daylight_savings == '1') {
					$offset -= 3600;
				}*/
				$offsetSign = $offset >= 0 ? '+' : '-';
				$offsetHours = round(abs($offset)/3600);
				$offsetMinutes = round((abs($offset) - $offsetHours * 3600) / 60);
				$format_offset = $offsetSign . str_pad($offsetHours, 2, '0', STR_PAD_LEFT) .':'. str_pad($offsetMinutes, 2, '0');
				static::$__formatted_timezones[$timezone_id] = '(GMT ' . $format_offset . ') ' . $timezone_name;
			}
		}
		return static::$__formatted_timezones;
	}
}
