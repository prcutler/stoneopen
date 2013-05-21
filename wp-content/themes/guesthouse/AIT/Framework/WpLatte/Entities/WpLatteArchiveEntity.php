<?php

/**
 * AIT WordPress Framework
 *
 * Copyright (c) 2011, Affinity Information Technology, s.r.o. (http://ait-themes.com)
 */


/**
 * The Archive Entity
 *
 * Used by archive.php
 */
class WpLatteArchiveEntity extends WpLatteBaseEntity
{
	/** @var bool */
	protected $isDate;

	/** @var bool */
	protected $isYear;

	/** @var bool */
	protected $isMonth;

	/** @var bool */
	protected $isDay;

	/** @var bool */
	protected $isTime;



	/**
	 * @return bool
	 */
	protected function getIsDate()
	{
		return is_date();
	}



	/**
	 * @return bool
	 */
	protected function getIsYear()
	{
		return is_year();
	}



	/**
	 * @return bool
	 */
	protected function getIsMonth()
	{
		return is_month();
	}



	/**
	 * @return bool
	 */
	protected function getIsDay()
	{
		return is_day();
	}



	/**
	 * @return bool
	 */
	protected function getIsTime()
	{
		return is_time();
	}

}