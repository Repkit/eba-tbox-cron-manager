<?php

/**
 * Job.php object
 *
 * @package    Job.php
 * @since      2016-09-06
 */

namespace TBoxCronManager;

class Job implements JobInterface
{

	/**
     * Minute (0 - 59)
     *
     * @var string
     */
    private $minute = '*';

    /**
     * Hour (0 - 23)
     *
     * @var string
     */
    private $hour = '*';

    /**
     * Day of Month (1 - 31)
     *
     * @var string
     */
    private $dayOfMonth = '*';

    /**
     * Month (1 - 12) OR jan,feb,mar,apr...
     *
     * @var string
     */
    private $month = '*';

    /**
     * Day of week (0 - 6) (Sunday=0 or 7) OR sun,mon,tue,wed,thu,fri,sat
     *
     * @var string
     */
    private $dayOfWeek = '*';

    /**
     * Job to be done
     *
     * @var string
     */
    private $command = null;

    /**
     * Job description
     *
     * @var string
     */
    public $comment = null;

    /**
     * Set minute or minutes
     *
     * @param string $minute required
     *
     * @return Job
     */
    public function onMinute($minute)
    {
        $this->minute = $minute;
        return $this;
    }

    /**
     * Set hour or hours
     *
     * @param string $hour required
     *
     * @return Job
     */
    public function onHour($hour)
    {
        $this->hour = $hour;
        return $this;
    }

    /**
     * Set day of month or days of month
     *
     * @param string $dayOfMonth required
     *
     * @return Job
     */
    public function onDayOfMonth($dayOfMonth)
    {
        $this->dayOfMonth = $dayOfMonth;
        return $this;
    }

    /**
     * Set month or months
     *
     * @param string $month required
     *
     * @return Job
     */
    public function onMonth($month)
    {
        $this->month = $month;
        return $this;
    }

    /**
     * Set day of week or days of week
     *
     * @param string $minute required
     *
     * @return Job
     */
    public function onDayOfWeek($day)
    {
        $this->dayOfWeek = $day;
        return $this;
    }

    /**
     * Set command
     *
     * @param string $command required
     *
     * @return Job
     */
    public function command($command)
    {
        $this->command = trim($command);
        return $this;
    }

    /**
     * Set comment
     *
     * @param string $comment required
     *
     * @return Job
     */
    public function comment($comment)
    {
        $this->comment = trim($comment);
        return $this;
    }

    /**
     * Set entire time code with one public function.
     *
     * Set entire time code with one public function. This has to be a
     * complete entry. See http://en.wikipedia.org/wiki/Cron#crontab_syntax
     *
     * @param string $timeCode required
     *
     * @return Job
     */
    public function on($timeCode)
    {
        list(
            $this->minute,
            $this->hour,
            $this->dayOfMonth,
            $this->month,
            $this->dayOfWeek
            ) = preg_split('/\s+/', trim($timeCode));

        return $this;
    }

    public function hydrate(array $Data)
    {
    	foreach($Data as $property => $value)
    	{
    		$this->$property = $value;
    	}
    }

    public function __toString()
    {
    	$entry = array(
            $this->minute,
            $this->hour,
            $this->dayOfMonth,
            $this->month,
            $this->dayOfWeek,
            $this->command
        );

        return join("\t", $entry);
    }
	
 
    /**
    * Gets the Minute (0 - 59).
    *
    * @return string
    */
    public function getMinute()
    {
        return $this->minute;
    }
 
    /**
    * Gets the Hour (0 - 23).
    *
    * @return string
    */
    public function getHour()
    {
        return $this->hour;
    }
 
    /**
    * Gets the Day of Month (1 - 31).
    *
    * @return string
    */
    public function getDayOfMonth()
    {
        return $this->dayOfMonth;
    }
 
    /**
    * Gets the Month (1 - 12) OR jan,feb,mar,apr.
    *
    * @return string
    */
    public function getMonth()
    {
        return $this->month;
    }
 
    /**
    * Gets the Day of week (0 - 6) (Sunday=0 or 7) OR sun,mon,tue,wed,thu,fri,sat.
    *
    * @return string
    */
    public function getDayOfWeek()
    {
        return $this->dayOfWeek;
    }
 
    /**
    * Gets the Job to be done.
    *
    * @return string
    */
    public function getCommand()
    {
        return $this->command;
    }
 
    /**
    * Gets the Job description.
    *
    * @return string
    */
    public function getComment()
    {
        return $this->comment;
    }
}