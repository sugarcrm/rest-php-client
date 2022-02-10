<?php

namespace Sugarcrm\REST\Tests\Endpoint\Data;

use Sugarcrm\REST\Endpoint\Data\FilterData;
use Sugarcrm\REST\Endpoint\ModuleFilter;

/**
 * Class FilterDataTest
 * @package MRussell\REST\Tests\Endpoint\Data
 * @coversDefaultClass Sugarcrm\REST\Endpoint\Data\FilterData
 * @group FilterDataTest
 */
class FilterDataTest extends \PHPUnit\Framework\TestCase {

    protected $data_simple = array(
        array(
            'name' => array(
                '$starts' => 's'
            ),
        ),
        array(
            'status' => array(
                '$equals' => 'foo'
            ),
        ),
        array(
            'date_entered' => array(
                '$gte' => '2017-01-01'
            )
        )
    );

    protected $data_complex = array(
        array(
            '$and' => array(
                array(
                    '$or' => array(
                        array(
                            "name" => array(
                                '$starts' => 's'
                            )
                        ),
                        array(
                            'name' => array(
                                '$contains' => 'test'
                            )
                        )
                    )
                ),
                array(
                    'assigned_user_id' => array(
                        '$equals' => 'seed_max_id'
                    )
                )
            )
        )
    );

    public static function setUpBeforeClass(): void {
        //Add Setup for static properties here
    }

    public static function tearDownAfterClass(): void {
        //Add Tear Down for static properties here
    }

    public function setUp(): void {
        parent::setUp();
    }

    public function tearDown(): void {
        parent::tearDown();
    }

    /**
     * @covers ::__construct
     * @covers ::setEndpoint
     */
    public function testConstructor() {
        $Filter = new FilterData();
        $ReflectedFilter = new \ReflectionClass('Sugarcrm\REST\Endpoint\Data\FilterData');
        $endpoint = $ReflectedFilter->getProperty('Endpoint');
        $endpoint->setAccessible(true);
        $this->assertEmpty($endpoint->getValue($Filter));
        $Endpoint = new ModuleFilter();
        $Filter->setEndpoint($Endpoint);
        $this->assertNotEmpty($endpoint->getValue($Filter));
        $this->assertEquals($Endpoint, $endpoint->getValue($Filter));

        $Filter = new FilterData($Endpoint);
        $this->assertNotEmpty($endpoint->getValue($Filter));
        $this->assertEquals($Endpoint, $endpoint->getValue($Filter));
    }

    /**
     * @covers ::offsetSet
     * @covers ::offsetGet
     * @covers ::offsetExists
     * @covers ::offsetUnset
     * @covers ::asArray
     * @covers ::compile
     * @covers ::reset
     * @covers ::clear
     * @covers ::update
     * @covers Sugarcrm\REST\Endpoint\Data\Filters\Expression\AbstractExpression::__call
     * @covers Sugarcrm\REST\Endpoint\Data\Filters\Expression\AbstractExpression::compile
     * @covers Sugarcrm\REST\Endpoint\Data\Filters\Expression\AbstractExpression::clear
     */
    public function testDataAccess() {
        $Filter = new ModuleFilter();
        $Data = new FilterData($Filter);
        $Data->set($this->data_simple);
        $this->assertEquals($this->data_simple, $Data->toArray());
        $Data->clear();
        $this->assertEquals(array(), $Data->toArray());
        $compiledData = $Data->starts('name', 's')->equals('status', 'foo')->gte('date_entered', '2017-01-01')->compile();
        $this->assertEquals($this->data_simple, $compiledData);
        $Data->set($this->data_simple);
        $this->assertEquals($this->data_simple, $Data->toArray());
        $Data->reset();
        $this->assertEmpty($Data->toArray(true));
        $Data[] = 'foo';
        $this->assertEquals('foo', $Data[0]);
        unset($Data[0]);
        $this->assertEquals(array(), $Data->toArray(true));
        $Data['$foo'] = 'bar';
        $Data->reset();
        $this->assertEmpty($Data->toArray(true));
        
        $Data->and()
            ->or()
            ->starts('name', 's')
            ->contains('name', 'test')
            ->endOr()
            ->equals('assigned_user_id', 'seed_max_id')
            ->endAnd();
        $this->assertEquals($this->data_complex, $Data->compile());
    }

    /**
     * @covers ::getProperties
     * @covers ::setProperties
     */
    public function testGetProperties() {
        $Filter = new ModuleFilter();
        $Data = new FilterData($Filter);
        $this->assertEmpty($Data->getProperties());
        $this->assertEquals($Data, $Data->setProperties(array('required_data' => 'filter')));
        $this->assertEquals(array('required_data' => 'filter'), $Data->getProperties());
    }

    // FIXME: mrussell to review
    // /**
    //  * @covers ::execute
    //  */
    // public function testExecute() {
    //     $FilterData = new FilterData();
    //     $this->assertEquals(true, $FilterData->execute());
    //     $ModuleFilter = new ModuleFilter();
    //     $ModuleFilter->setBaseUrl('http://localhost/rest/v10');
    //     $ModuleFilter->setModule('test');
    //     $FilterData->setEndpoint($ModuleFilter);
    //     $this->assertEquals($ModuleFilter, $FilterData->execute());
    // }
}
