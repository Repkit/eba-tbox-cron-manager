<?php

/**
 * @Project: cron-manager
 * @Class:  ExecutorTest
 * @Date:   2016-09-07 18:07:20
 * @Last Modified time: 2016-09-08 15:04:34
 */

namespace TBoxCronManager;

use PHPUnit_Framework_TestCase as TestCase;
use TBoxCronManager\Executor;


class ExecutorTest extends TestCase
{

    protected $Executor;

    private $JobPath;

    private $_tmpfile;

    /**
     * Set up
     */
    public function setUp()
    {
       $this->_setTempFile();
        $this->Executor = new Executor();
        $this->JobPath =  getcwd() . '/data/test.cjob' ;
        $content = '# BEGIN:7d6a1fdcf5015da7eab6be21f383f319
# Comment:cron-manager add job test
*       *       *       *       *       echo "some text"
# END:7d6a1fdcf5015da7eab6be21f383f319';

       file_put_contents($this->_tmpfile,$content, LOCK_EX);


    }

    protected function tearDown()
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

    public function test_read()
    {
        $data = $this->Executor->read();

        $this->assertNotNull($data);
    }

    public function test_write()
    {
        $result = $this->Executor->write($this->_tmpfile,$this->JobPath);
        $this->assertNotNull($result);
        
        file_put_contents($this->_tmpfile,file_get_contents($this->JobPath . '.bak'), LOCK_EX);
        $result = $this->Executor->write($this->_tmpfile);

        unlink($this->JobPath . '.bak');
    }

}