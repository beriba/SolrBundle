<?php

namespace FS\SolrBundle\Tests\DependencyInjection;

use Symfony\Component\DependencyInjection\Definition;

use Symfony\Component\DependencyInjection\Reference;

use Symfony\Component\DependencyInjection\Scope;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use FS\SolrBundle\DependencyInjection\FSSolrExtension;

/**
 * 
 * @group extension
 */
class FSSolrExtensionTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var ContainerBuilder
	 */
	private $container = null;
	
	public function setUp() {
		$this->container = new ContainerBuilder();
		
		$definition = new Definition();
		$this->container->setDefinition('doctrine.orm.default_configuration', $definition);
		$this->container->setParameter('kernel.bundles', array());
	}
	
	private function commonConfig() {
		return array('fs_solr' => array(
			'solr'=> array(
				'hostname'=>'1.1.1.1',
				'port'=>'8080'
			),
			'entity_manager'=>'default'
		));
	}
	
	private function multiCoreConfig() {
		return array('fs_solr' => array(
				'solr'=> array(
					'hostname'=>'1.1.1.1',
					'port'=>'8080',
					'path'=> array(
						'core0'=>'/solr/core0',
						'core1'=>'/solr/core1'
					)		
				),
				'entity_manager'=>'default'
		));
	}	
	
	public function testSolrConnection() {
		$config = $this->commonConfig();
		
		$extension = new FSSolrExtension();
		$extension->load($config, $this->container);
		
		$connection = $this->container->getDefinition('solr.connection_factory')->getArguments();
		$connection = array_pop($connection);
		$this->assertTrue(array_key_exists('default', $connection), 'default connection');
		$this->assertEquals('/solr', $connection['default']['path'], 'path to solr');
	}

	public function testSolrConnection_MultiCore() {
		$config = $this->multiCoreConfig();
	
		$extension = new FSSolrExtension();
		$extension->load($config, $this->container);
	
		$connection = $this->container->getDefinition('solr.connection_factory')->getArguments();
		$connection = array_pop($connection);
		
		$this->assertTrue(array_key_exists('core0', $connection), 'core0 connection');
		$this->assertTrue(array_key_exists('core1', $connection), 'core1 connection');
		
		$this->assertEquals('/solr/core0', $connection['core0']['path'], 'path to core0');
		$this->assertEquals('/solr/core1', $connection['core1']['path'], 'path to core1');
		
		$this->assertEquals('1.1.1.1', $connection['core0']['hostname'], 'host of core0');
		$this->assertEquals('1.1.1.1', $connection['core1']['hostname'], 'host of core1');
	}	
	
	public function testDoctrineORMSetup() {
		$config = $this->commonConfig();
	
		$extension = new FSSolrExtension();
		$extension->load($config, $this->container);

		$this->assertTrue($this->container->has('solr.update.document.orm.listener'), 'update listener');
		$this->assertTrue($this->container->has('solr.delete.document.orm.listener'), 'delete listener');
		$this->assertTrue($this->container->has('solr.add.document.orm.listener'), 'insert listener');
		
		$this->assertDefinitionHasTag('solr.update.document.orm.listener', 'doctrine.event_listener');
		$this->assertDefinitionHasTag('solr.delete.document.orm.listener', 'doctrine.event_listener');
		$this->assertDefinitionHasTag('solr.add.document.orm.listener', 'doctrine.event_listener');
		
		$doctrineArguments = $this->container->getDefinition('solr.doctrine.configuration')->getArguments();
		$arguments = array_pop($doctrineArguments);
		$doctrineConfiguration = $arguments;
		
		$this->assertEquals('doctrine.orm.default_configuration',$doctrineConfiguration);		
	}	
	
	public function testDoctrineODMSetup() {
		$config = $this->commonConfig();
	
		$this->container->setParameter('doctrine_mongodb.odm.document_managers', true);
		
		$extension = new FSSolrExtension();
		$extension->load($config, $this->container);
	
		$this->assertTrue($this->container->has('solr.update.document.odm.listener'), 'update listener');
		$this->assertTrue($this->container->has('solr.delete.document.odm.listener'), 'delete listener');
		$this->assertTrue($this->container->has('solr.add.document.odm.listener'), 'insert listener');

		$this->assertDefinitionHasTag('solr.update.document.odm.listener', 'doctrine_mongodb.odm.event_listener');
		$this->assertDefinitionHasTag('solr.delete.document.odm.listener', 'doctrine_mongodb.odm.event_listener');
		$this->assertDefinitionHasTag('solr.add.document.odm.listener', 'doctrine_mongodb.odm.event_listener');
		
		$doctrineArguments = $this->container->getDefinition('solr.doctrine.configuration')->getArguments();
		$arguments = array_pop($doctrineArguments);
		$doctrineConfiguration = $arguments;
	
		$this->assertEquals('doctrine_mongodb.odm.default_configuration',$doctrineConfiguration);
	}
	
	private function assertDefinitionHasTag($definition, $tag) {
		$this->assertTrue($this->container->getDefinition($definition)->hasTag($tag), sprintf('%s with %s tag', $definition, $tag));
	}
}

