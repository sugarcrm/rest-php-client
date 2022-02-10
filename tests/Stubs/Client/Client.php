<?php

namespace Sugarcrm\REST\Tests\Stubs\Client;


use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use Sugarcrm\REST\Client\SugarApi;

class Client extends SugarApi {
    public $mockResponses;
    public function __construct() {
        $this->mockResponses = new MockHandler([]);
        $this->setHandlerStack(HandlerStack::create($this->mockResponses));
        parent::__construct();
    }
}
