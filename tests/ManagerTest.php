<?php

/**
 * @Project: cron-manager
 * @Class:  ManagerTest
 * @Date:   2016-09-07 17:00:36
 * @Last Modified time: 2016-09-08 12:07:37
 */

namespace TBoxCronManager;

use PHPUnit_Framework_TestCase as TestCase;
use TBoxCronManager\Manager;
use TBoxCronManager\Executor;
use TBoxCronManager\Parser;
use TBoxCronManager\Job;


class ManagerTest extends TestCase
{

    protected $Manager;

    /**
     * Set up
     */
    public function setUp()
    {
        $executor = new Executor();
		$parser = new Parser();
		$this->Manager = new Manager($executor, $parser);
    }


    public function jobProvider()
    {
        return [
            	'time'    =>    "*\t*\t*\t*\t*\t",
            	'command' => 'echo "some other text"',
            	'comment' => 'cron-manager add job test'
    	];
    }


    public function test_addJob()
    {
    	$Job = $this->jobProvider();
    	$job = new Job();
		$job->on($Job['time']);
		$job->command($Job['command']);
		$job->comment($Job['comment']);

		$hash = $this->Manager->add($job);
		
		$this->assertNotEmpty($hash);

		$this->Manager->persist();

		return $hash;
    }


    

    /**
     * @depends test_addJob
     */
    public function test_getJob($JobHash)
    {
    	$result = $this->Manager->get($JobHash);
    	$this->assertNotEmpty($result);

    	return $JobHash;
    }


    /**
     * @depends test_getJob
     */
    public function test_removeJob($JobHash)
    {
    	
    	$result = $this->Manager->remove($JobHash);
    	$this->assertTrue($result);
    	
    	$this->Manager->persist();

    	return $JobHash;

    }

    /**
     * @depends test_removeJob
     */
    public function test_getJobs($JobHash)
    {
    	$jobs = $this->Manager->jobs();
    	$this->assertArrayNotHasKey($JobHash,$jobs);
    }

    public function test_clear()
    {
    	$this->Manager->clear();
    	$jobs = $this->Manager->jobs();
    	$this->isEmpty($jobs);
    }



}