<?php

namespace Tests\Unit;

use Tests\TestCase;

class ConfigurationTest extends TestCase
{
    /** @test */
    public function it_has_loose_mysql_configuration()
    {
        $this->assertFalse(config('database.connections.mysql.strict'));
    }
}
