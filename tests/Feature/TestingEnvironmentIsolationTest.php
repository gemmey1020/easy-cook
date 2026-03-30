<?php

namespace Tests\Feature;

use Tests\TestCase;

class TestingEnvironmentIsolationTest extends TestCase
{
    /** @test */
    public function phpunit_runs_against_the_dedicated_testing_database()
    {
        $this->assertSame('testing', config('app.env'));
        $this->assertSame('mysql', config('database.default'));
        $this->assertSame('easycook_25_3_2026_testing', config('database.connections.mysql.database'));
    }
}
