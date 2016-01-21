<?php
/**
 * @file
 * Contains \Drupal\Tests\migrate_source_example\Unit\process\WalkTest.
 */

namespace Drupal\Tests\migrate_source_example\Unit\process {

  use Drupal\migrate\MigrateExecutable;
  use Drupal\migrate\Plugin\migrate\process\Callback;
  use Drupal\migrate\Plugin\migrate\process\StaticMap;
  use Drupal\migrate\Row;
  use Drupal\Tests\migrate\Unit\MigrateTestCase;
  use Drupal\migrate_source_example\Plugin\migrate\process\Walk;

  /**
   * Tests the Walk process plugin.
   *
   * @group migrate_source_example
   */
  class WalkTest extends MigrateTestCase {

    /**
     * @var array
     */
    protected $migrationConfiguration = array(
      'id' => 'test',
    );

    /**
     * @var \Drupal\migrate\Entity\MigrationInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $migration;

    /**
     * {@inheritdoc}
     */
    protected function setUp() {
      $this->migration = $this->getMigration();
      parent::setUp();
    }

    /**
     * Returns the configuration of Walk process plugin which defines how a value
     * should be processed.
     *
     * @return array
     */
    protected function getWalkProcessPluginConfiguration() {
      return [
        'process' => [
          // First, convert all characters to lowercase.
          [
            'plugin' => 'callback',
            'callable' => 'strtolower',
          ],
          // Then convert all characters to uppercase.
          [
            'plugin' => 'callback',
            'callable' => 'ucfirst',
          ],
          // Then convert the value using static map.
          [
            'plugin' => 'static_map',
            'bypass' => TRUE,
            'map' => [
              'Foo' => 'Foo (mapped)',
              'Foobar' => 123,
            ]
          ],
          // Then wrap the value with asterisks.
          [
            'plugin' => 'callback',
            'callable' => [
              'Drupal\migrate_source_example\AsteriskWrap',
              'wrap',
            ],
          ],
        ],
      ];
    }

    /**
     * Returns manually created process plugins which conform to the specs
     * defined in self::getProcessConfiguration().
     *
     * @return array
     */
    protected function getProcessPlugins() {
      $configuration = $this->getWalkProcessPluginConfiguration();

      return [
        [
          new Callback($configuration['process'][0], 'callback', []),
          new Callback($configuration['process'][1], 'callback', []),
          new StaticMap($configuration['process'][2], 'static_map', []),
          new Callback($configuration['process'][3], 'callback', []),
        ],
      ];
    }

    /**
     * Returns migrate executable object.
     *
     * @return \Drupal\migrate\MigrateExecutableInterface
     */
    protected function getMigrateExecutable() {
      $event_dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
      /** @var \Drupal\migrate\MigrateMessageInterface|\PHPUnit_Framework_MockObject_MockObject $migrate_message */
      $migrate_message = $this->getMock('Drupal\migrate\MigrateMessageInterface');
      return new MigrateExecutable($this->migration, $migrate_message, $event_dispatcher);
    }

    /**
     * Tests the walk process plugin with multiple values.
     */
    public function testWalkWithMultipleValues() {
      // Set source value.
      $source_value = ['FOO', 'BAR', 'foobar'];

      // Set expected value;
      $expected_value = ['*Foo (mapped)*', '*Bar*', '*123*'];

      // Manually create the plugins. Migration::getProcessPlugins does this
      // normally, but the plugin system is not available.
      $process_plugins = $this->getProcessPlugins();

      // Define the value of getProcessPlugin() for every time processRow() is
      // called (number of values). See the count of $source_value.
      foreach (range(1, count($source_value)) as $key) {
        $this->migration->expects($this->at($key))
                        ->method('getProcessPlugins')
                        ->will($this->returnValue($process_plugins));
      }

      // Get migrate executable.
      $migrate_executable = $this->getMigrateExecutable();

      // Assert the value after running the process plugins.
      $plugin = new Walk($this->getWalkProcessPluginConfiguration(), 'walk', []);
      $new_value = $plugin->transform($source_value, $migrate_executable, new Row([], []), 'test');
      $this->assertSame($new_value, $expected_value);
    }

  }
}

namespace Drupal\migrate_source_example {

  /**
   * Class AsteriskWrap
   */
  class AsteriskWrap {

    /**
     * Returns a value wrapped in asterisks (*).
     *
     * @param string $value
     *
     * @return string
     */
    public static function wrap($value) {
      return sprintf('*%s*', $value);
    }

  }
}
