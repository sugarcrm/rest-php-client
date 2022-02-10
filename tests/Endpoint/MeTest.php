<?php

namespace Sugarcrm\REST\Tests\Endpoint;

use Sugarcrm\REST\Endpoint\Me;

/**
 * Class MeTest
 * @package Sugarcrm\REST\Tests\Endpoint
 * @coversDefaultClass Sugarcrm\REST\Endpoint\Me
 * @group MeTest
 */
class MeTest extends \PHPUnit\Framework\TestCase {

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

    public function testConstruct() {
        $Me = new Me();
        $Reflection = new \ReflectionClass(get_class($Me));
        $actions = $Reflection->getProperty('actions');
        $actions->setAccessible(true);
        $this->assertNotEmpty(
            $actions->getValue($Me)
        );
    }

    /**
     * @covers ::configureURL
     */
    public function testConfigureUrl() {
        $Me = new Me();
        $Reflection = new \ReflectionClass(get_class($Me));
        $configureUrl = $Reflection->getMethod('configureURL');
        $configureUrl->setAccessible(true);
        $action = $Reflection->getProperty('action');
        $action->setAccessible(true);

        $this->assertEquals('me', $configureUrl->invoke($Me, []));
        $action->setValue($Me, $Me::USER_ACTION_PREFERENCES);
        $this->assertEquals('me/preferences', $configureUrl->invoke($Me, []));
        $action->setValue($Me, $Me::USER_ACTION_SAVE_PREFERENCES);
        $this->assertEquals('me/preferences', $configureUrl->invoke($Me, []));
        $action->setValue($Me, $Me::USER_ACTION_CREATE_PREFERENCE);
        $this->assertEquals('me/preference/pref1', $configureUrl->invoke($Me, ['actionArg1' => 'pref1']));
        $action->setValue($Me, $Me::MODEL_ACTION_DELETE);
        $this->assertEquals('me', $configureUrl->invoke($Me, array('action' => 'preference')));
    }

    /**
     * @covers ::configureAction
     */
    public function testConfigureAction() {
        $Me = new Me();
        $Reflection = new \ReflectionClass(get_class($Me));
        $configureAction = $Reflection->getMethod('configureAction');
        $configureAction->setAccessible(true);

        $configureAction->invoke($Me, $Me::USER_ACTION_PREFERENCES);
        $properties = $Me->getProperties();
        $this->assertEquals("GET", $properties['httpMethod']);

        $configureAction->invoke($Me, $Me::USER_ACTION_SAVE_PREFERENCES);
        $properties = $Me->getProperties();
        $this->assertEquals("PUT", $properties['httpMethod']);

        $configureAction->invoke($Me, $Me::USER_ACTION_CREATE_PREFERENCE, ['foo']);
        $properties = $Me->getProperties();
        // FIXME: mrussell to review
        // This change satisfy the test however I'm not sure it is the right way
        $options = $Me->get("options");

        $this->assertEquals("POST", $properties['httpMethod']);
        $this->assertArrayHasKey('actionArg1', $options);
        $this->assertEquals('foo', $options['actionArg1']);

        $configureAction->invoke($Me, $Me::USER_ACTION_GET_PREFERENCE, ['foo']);
        $properties = $Me->getProperties();
        // FIXME: mrussell to review
        // This change satisfy the test however I'm not sure it is the right way
        $options = $Me->get("options");

        $this->assertEquals("GET", $properties['httpMethod']);
        $this->assertArrayHasKey('actionArg1', $options);
        $this->assertEquals('foo', $options['actionArg1']);

        $configureAction->invoke($Me, $Me::USER_ACTION_UPDATE_PREFERENCE, ['foo']);
        $properties = $Me->getProperties();
        // FIXME: mrussell to review
        // This change satisfy the test however I'm not sure it is the right way
        $options = $Me->get("options");

        $this->assertEquals("PUT", $properties['httpMethod']);
        $this->assertArrayHasKey('actionArg1', $options);
        $this->assertEquals('foo', $options['actionArg1']);

        $configureAction->invoke($Me, $Me::USER_ACTION_DELETE_PREFERENCE, ['foo']);
        $properties = $Me->getProperties();
        // FIXME: mrussell to review
        // This change satisfy the test however I'm not sure it is the right way
        $options = $Me->get("options");

        $this->assertEquals("DELETE", $properties['httpMethod']);
        $this->assertArrayHasKey('actionArg1', $options);
        $this->assertEquals('foo', $options['actionArg1']);
    }
}
