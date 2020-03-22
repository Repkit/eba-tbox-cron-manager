Cron Manager
===================

Manages linux crontab file by adding and deleting jobs. 

Installation
------------

* copy files to your project
* include files from src directory or use the autoloader generated by composer
* use it as described below


Usage
-----

Adding a simple task to crontab:

```php
<?php
require '../vendor/autoload.php';


use TBoxCronManager\Manager;
use TBoxCronManager\Executor;
use TBoxCronManager\Parser;
use TBoxCronManager\Job;

$executor = new Executor();
$parser = new Parser();
$manager = new Manager($executor, $parser);



$job = new Job();
$job->on("*\t*\t*\t*\t*\t");
$job->command('echo "another text"');
$job->comment('cron-manager add job test');

$hash = $manager->add($job);
$manager->persist();
```
    
Remove a task from crontab:

```php
<?php

require '../vendor/autoload.php';

use TBoxCronManager\Manager;
use TBoxCronManager\Executor;
use TBoxCronManager\Parser;
use TBoxCronManager\Job;

$executor = new Executor();
$parser = new Parser();
$manager = new Manager($executor, $parser);

$jobHash = '7d6a1fdcf5015da7eab6be21f383f319';
$res = $manager->remove($jobHash);
$manager->persist();
```

List all crontab tasks

```php
<?php

require '../vendor/autoload.php';

use TBoxCronManager\Manager;
use TBoxCronManager\Executor;
use TBoxCronManager\Parser;
use TBoxCronManager\Job;

$executor = new Executor();
$parser = new Parser();
$manager = new Manager($executor, $parser);


$jobs = $manager->jobs();

```

Get a specific job from crontab

```php
<?php

require '../vendor/autoload.php';

use TBoxCronManager\Manager;
use TBoxCronManager\Executor;
use TBoxCronManager\Parser;
use TBoxCronManager\Job;

$executor = new Executor();
$parser = new Parser();
$manager = new Manager($executor, $parser);

$jobHash = '7d6a1fdcf5015da7eab6be21f383f319';
$job = $manager->get($jobHash);

```

Remove all jobs from crontab

```php
<?php

require '../vendor/autoload.php';

use TBoxCronManager\Manager;
use TBoxCronManager\Executor;
use TBoxCronManager\Parser;
use TBoxCronManager\Job;

$executor = new Executor();
$parser = new Parser();
$manager = new Manager($executor, $parser);

$res = $manager->clear();
$manager->persist();

```