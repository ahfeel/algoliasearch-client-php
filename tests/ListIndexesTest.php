<?php

include __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../algoliasearch.php';


class ListIndexesTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->client = new \AlgoliaSearch\Client(getenv('ALGOLIA_APPLICATION_ID'), getenv('ALGOLIA_API_KEY'));
        $this->index = $this->client->initIndex(safe_name('ListIndexesTest'));
        try {
            $this->index->clearIndex();
        } catch (AlgoliaSearch\AlgoliaException $e) {
            // not fatal
        }
        try {
            $task = $this->client->deleteIndex(safe_name('ListTest2'));
        } catch (AlgoliaSearch\AlgoliaException $e) {
            // ListTest2 does not exist
        }
    }

    public function testListIndexes()
    {
        $res = $this->client->listIndexes();

        $this->index2 = $this->client->initIndex(safe_name('ListTest2'));
        $task = $this->index2->addObject(array("firstname" => "Robin"));
        $this->index2->waitTask($task['taskID']);
        $resAfter = $this->client->listIndexes();

        $this->assertEquals(count($res['items']) + 1, count($resAfter['items']));
        
    }

    private $client;
    private $index;
}
