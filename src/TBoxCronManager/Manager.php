<?php

/**
 * Manager.php object
 *
 * @package    Manager.php
 * @since      2016-09-06
 */

namespace TBoxCronManager;


class Manager
{

	const SHEBANG = '#!/bin/sh';

	// our jobs
	private $_jobs = array();

	// others jobs
	private $_extrajobs = array();

	private $hash;

	protected $parser;
	protected $executor;

	public $folder;

	/**
     * Location to save the crontab file.
     *
     * @var string
     */
    private $_tmpfile;

	public function __construct($Executor, $Parser, $Folder = null)
	{
		$this->executor = $Executor;
		$this->parser = $Parser;

		$this->folder = $Folder;
		if(!is_dir($Folder)){
			$this->folder = getcwd();
		}
        $this->_setTempFile();
		$this->init();
	}

	    /**
     * Destrutor
     */
    public function __destruct() 
    {
        if ($this->_tmpfile && is_file($this->_tmpfile)) {
            unlink($this->_tmpfile);
        }
    }

    /**
     * Sets tempfile name
     *
     * @return CrontabManager
     */
    protected function _setTempFile() 
    {
        if ($this->_tmpfile && is_file($this->_tmpfile)) {
            unlink($this->_tmpfile);
        }
        $tmpDir = sys_get_temp_dir();
        $this->_tmpfile = tempnam($tmpDir, 'cronman');
        chmod($this->_tmpfile, 0666);

        return $this;
    }

	/**
	* add a new job to crontab
	* return string job hash
	*/
	public function add(JobInterface $Job)
	{
		$job = $Job;
		$hash = $this->hash($job);
		$commandContent = self::SHEBANG . PHP_EOL . $job->getCommand();
		$jobFile = $this->folder . '/' . $hash . '-cron-command.sh';
		file_put_contents($jobFile, $commandContent);
		chmod($jobFile, 0755);
		$job->command($jobFile);
		$hash = $this->hash($job);
		$this->_jobs[$hash] = $job;

		return $hash;
	}

	/**
	* remove a job from crontab based on hash
	*/
	public function remove($JobHash)
	{
		// echo __FILE__ . ':' . __LINE__ . '</br></br>';var_dump($JobHash);exit();
		$jobRemoved = false;
		foreach ($this->_jobs as $jobHash => $value) 
		{
			if( $jobHash === $JobHash )
			{
				unset($this->_jobs[$jobHash]);
				$jobRemoved = true;
				break;
			}	
		}

		return $jobRemoved;
	}

	/**
	* get a job from crontab based on hash
	*/
	public function get($JobHash)
	{
		$job = false;
		foreach ($this->_jobs as $jobHash => $value) 
		{
			if( $jobHash === $JobHash )
			{
				$job = $value;
				break;
			}	
		}

		return $job;
	}

	/**
	* list all jobs added by manager from crontab
	*/
	public function jobs()
	{
		return $this->_jobs;
	}

	/**
	* remvoe all jobs that were added by manager from crontab
	*/
	public function clear()
	{
		$this->_jobs = array();
	}

	/**
	* effectivly writes jobs to crontab
	*/
	public function persist()
	{

		$data = $this->executor->read();
		$actualcontent = implode('',$data);
		$newhash = md5($actualcontent);
		if($this->hash !==  $newhash)
		{
			// means something was changed and we need to reparse
			$this->hash = $newhash;
			$this->parser->parse($data);
			$this->_extrajobs = $data;
		}

		$jobs = $this->parser->prepare($this->_jobs);
		$extraJobs = join(PHP_EOL, $this->_extrajobs);
		$crontabContent = $jobs . PHP_EOL . $extraJobs . PHP_EOL;

		$filename = $this->folder.'/'.$this->hash.'.cjob';
		file_put_contents($filename, $crontabContent);
		if(file_put_contents($this->_tmpfile, $crontabContent, LOCK_EX)){
			$this->executor->write($this->_tmpfile,$filename);
		}else{
			throw new \Exception("Error persisiting crontab. Permission denied!", 1);
		}

	}

	private function init()
	{
		$data = $this->executor->read();
		$this->hash = md5(implode('',$data));
		$this->_jobs = $this->parser->parse($data);
		$this->_extrajobs = $data;
	}

	public static function hash(JobInterface $Job)
	{
		return md5($Job->__toString());
	}


}