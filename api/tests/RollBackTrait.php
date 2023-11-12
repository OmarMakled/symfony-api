<?php

namespace App\Tests;

trait RollBackTrait
{
    protected $em;
    protected $client;

    public function setUp(): void
    {
        self::bootKernel();
        $this->client = $this->createClient(['environment' => 'test']);
        $this->client->disableReboot();
        $this->em = $this->client->getContainer()->get('doctrine.orm.entity_manager');
        $this->em->beginTransaction();
    }

    public function tearDown(): void
    {
        $this->em->rollback();
    }
}
