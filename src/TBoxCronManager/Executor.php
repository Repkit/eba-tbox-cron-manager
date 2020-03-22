<?php

/**
 * Executor.php object
 *
 * @package    Executor.php
 * @since      2016-09-06
 */

namespace TBoxCronManager;

class Executor implements JobInterface
{

	protected $crontab;

	public function __construct($CrontabCmd = '/usr/bin/crontab')
	{
		$this->crontab = $CrontabCmd;
	}

	public function read()
	{
		$cmd = $this->crontab . ' -l';
		$cmd = escapeshellcmd($cmd);
		exec($cmd, $listjobs);

		return $listjobs;
	}

	public function write($filename,$backupFile = null)
	{
		$cmd = '';
		if( isset($backupFile) )
		{
			$cmd .= $this->crontab . ' -l > ' . $backupFile.'.bak; ';
		}

		$cmd .= $this->crontab . ' ' . $filename;
		exec($cmd, $result);

		return $result;
	}

}