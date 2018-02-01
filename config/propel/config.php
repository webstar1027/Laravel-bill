<?php
$serviceContainer = \Propel\Runtime\Propel::getServiceContainer();
$serviceContainer->checkVersion('2.0.0-dev');
$serviceContainer->setAdapterClass('default', 'mysql');
$manager = new \Propel\Runtime\Connection\ConnectionManagerSingle();
$manager->setConfiguration(array (
  'classname' => 'Propel\\Runtime\\Connection\\ProfilerConnectionWrapper',
  'dsn' => 'mysql:host=127.0.0.1;port=3306;dbname=amatracker_new',
  'user' => 'root',
  'password' => 'mysql',
  'options' =>
  array (
    'ATTR_PERSISTENT' => false,
  ),
  'attributes' =>
  array (
    'ATTR_EMULATE_PREPARES' => false,
    'ATTR_TIMEOUT' => 30,
  ),
  'settings' =>
  array (
    'charset' => 'utf8',
    'queries' =>
    array (
      0 => 'SET NAMES utf8 COLLATE utf8_unicode_ci, COLLATION_CONNECTION = utf8_unicode_ci, COLLATION_DATABASE = utf8_unicode_ci, COLLATION_SERVER = utf8_unicode_ci',
    ),
  ),
  'model_paths' =>
  array (
    0 => 'src',
    1 => 'vendor',
  ),
));
$manager->setName('default');
$serviceContainer->setConnectionManager('default', $manager);
$serviceContainer->setDefaultDatasource('default');
$serviceContainer->setProfilerClass('Propel\Runtime\Util\Profiler');
$serviceContainer->setProfilerConfiguration(array (
  'slowTreshold' => 0.10000000000000001,
  'details' =>
  array (
    'time' =>
    array (
      'name' => 'Time',
      'precision' => 3,
      'pad' => 8,
    ),
    'mem' =>
    array (
      'name' => 'Memory',
      'precision' => 3,
      'pad' => 8,
    ),
    'memDelta' =>
    array (
      'name' => 'Memory Delta',
      'precision' => 3,
      'pad' => 8,
    ),
    'memPeak' =>
    array (
      'name' => 'Memory Peak',
      'precision' => 3,
      'pad' => 8,
    ),
  ),
  'innerGlue' => ': ',
  'outerGlue' => ' | ',
));