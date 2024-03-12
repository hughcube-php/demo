<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function testJob(object $job)
    {
        if (method_exists($job, 'setLogChannel')) {
            $job->setLogChannel('stdout');
        }

        $job->handle();

        $this->assertTrue(true);
    }
}
