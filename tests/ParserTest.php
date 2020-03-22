<?php

/**
 * @Project: cron-manager
 * @Class:  ParserTest
 * @Date:   2016-09-07 18:40:42
 * @Last Modified time: 2016-09-07 19:05:28
 */

namespace TBoxCronManager;

use PHPUnit_Framework_TestCase as TestCase;
use TBoxCronManager\Parser;


class ParserTest extends TestCase
{

    protected $Parser;


    /**
     * Set up
     */
    public function setUp()
    {
        $this->Parser = new Parser();
    }

    public function crontabProvider()
    {
    	return [
    		[
    			[
				  	'# BEGIN:7d6a1fdcf5015da7eab6be21f383f319'
					,'# Comment:cron-manager add job test'
					,'*	*	*	*	*	echo "some text"'
					,'# END:7d6a1fdcf5015da7eab6be21f383f319'
					,'# BEGIN:7d4b10fe9f1462cafcc633cf82cf7b61'
					,'# Comment:cron-manager add job test'
					,'*	*	*	*	*	echo "another text"'
					,'# END:7d4b10fe9f1462cafcc633cf82cf7b61'
					,'*	*	*	*	*	echo "cocorico"'
				],
				2
    		],
    		[
    			[
				  	'# BEGIN:7d6a1fdcf5015da7eab6be21f383f319'
					,'# Comment:cron-manager add job test'
					,'*	*	*	*	*	echo "some text"'
					,'# END:7d6a1fdcf5015da7eab6be21f383f319'
					,'*	*	*	*	*	echo "cocorico"'
				],
				1
    		]
    	];
    }

    /**
     * @dataProvider crontabProvider
     */
    public function test_parse($Jobs,$Count)
    {

    	$result = $this->Parser->parse($Jobs);
    	$this->assertCount($Count,$result);
    }


}