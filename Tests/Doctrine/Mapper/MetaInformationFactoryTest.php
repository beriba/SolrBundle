<?php
namespace FS\SolrBundle\Tests\Doctrine\Mapper;

use FS\SolrBundle\Tests\Doctrine\Annotation\Entities\EntityWithOneToOne;

use FS\SolrBundle\Doctrine\Mapper\MetaInformationFactory;
use FS\SolrBundle\Doctrine\Mapper\MetaInformation;

/**
 * 
 * @group mapper
 */
class MetaInformationFactoryTest extends \PHPUnit_Framework_TestCase {
	private function setupDoctrine($namespace) {
		$doctrineConfiguration = $this->getMock('Doctrine\ORM\Configuration', array(), array(), '', false);
		$doctrineConfiguration->expects($this->any())
		->method('getEntityNamespace')
		->will($this->returnValue($namespace));
	
		return $doctrineConfiguration;
	}	
	
	public function testLoadInformation_ShouldLoadAll() {
		$testEntity = new ValidTestEntity();
		$expectedClassName = get_class($testEntity);

		$expectedDocumentName = 'validtestentity';
		
		$doctrineConfiguration = $this->setupDoctrine('FS\SolrBundle\Tests\Doctrine\Mapper');
		
		$factory = new MetaInformationFactory();
		$factory->setDoctrineConfiguration($doctrineConfiguration);
		$actual = $factory->loadInformation('FSBlogBundle:ValidTestEntity');
		
		$this->assertTrue($actual instanceof MetaInformation);
		$this->assertEquals($expectedClassName, $actual->getClassName(), 'wrong classname');
		$this->assertEquals($expectedDocumentName, $actual->getDocumentName(), 'wrong documentname');
		$this->assertEquals(3, count($actual->getFields()), '3 fields are set');
		$this->assertEquals(4, count($actual->getFieldMapping()), '4 fields are mapped');
	}
	
	public function testLoadInformation_LoadInformationFromObject() {
		$testEntity = new ValidTestEntity();
		$expectedClassName = get_class($testEntity);
		
		$expectedDocumentName = 'validtestentity';
		
		$doctrineConfiguration = $this->setupDoctrine('FS\SolrBundle\Tests\Doctrine\Mapper');
		
		$factory = new MetaInformationFactory();
		$factory->setDoctrineConfiguration($doctrineConfiguration);
		$actual = $factory->loadInformation($testEntity);
		
		$this->assertTrue($actual instanceof MetaInformation);
		$this->assertEquals($expectedClassName, $actual->getClassName(), 'wrong classname');
		$this->assertEquals($expectedDocumentName, $actual->getDocumentName(), 'wrong documentname');
		$this->assertEquals(3, count($actual->getFields()), '3 fields are mapped');		
	}

	/**
	 * @expectedException RuntimeException
	 * @expectedExceptionMessage no declaration for document found in entity
	 */
	public function testLoadInformation_EntityHasNoDocumentDeclaration_ShouldThrowException() {
		$doctrineConfiguration = $this->setupDoctrine('FS\SolrBundle\Tests\Doctrine\Mapper');
		
		$factory = new MetaInformationFactory();
		$factory->setDoctrineConfiguration($doctrineConfiguration);
		$factory->loadInformation('FSBlogBundle:NotIndexedEntity');
	}

	/**
	 * @expectedException RuntimeException
	 * @expectedExceptionMessage Unknown entity FSBlogBundle:UnknownEntity
	 */
	public function testLoadInformation_EntityDoesNoExists() {
		$doctrineConfiguration = $this->setupDoctrine('FS\SolrBundle\Tests\Doctrine\Mapper');
		
		$factory = new MetaInformationFactory();
		$factory->setDoctrineConfiguration($doctrineConfiguration);
		$factory->loadInformation('FSBlogBundle:UnknownEntity');		
	}
	
	public function testLoadInformation_FromObject() {
		$doctrineConfiguration = $this->setupDoctrine('FS\SolrBundle\Tests\Doctrine\Mapper');
		
		$factory = new MetaInformationFactory();
		$factory->setDoctrineConfiguration($doctrineConfiguration);
		
		$testEntity = new ValidTestEntity();
		$informations = $factory->loadInformation($testEntity);

		$expected = get_class($testEntity);
		$this->assertEquals($expected, $informations->getClassName(), 'class from object not discovered');
	}
	
	public function testLoadInformation_FromFullClassname() {
		$doctrineConfiguration = $this->setupDoctrine('FS\SolrBundle\Tests\Doctrine\Mapper');
	
		$factory = new MetaInformationFactory();
		$factory->setDoctrineConfiguration($doctrineConfiguration);
	
		$entityClassname = get_class(new ValidTestEntity());
		$informations = $factory->loadInformation($entityClassname);
	
		$expected = $entityClassname;
		$this->assertEquals($expected, $informations->getClassName(), 'class from fullclassname not discovered');
	}
	
	public function testLoadInformation_EntityWithRelation() {
		$doctrineConfiguration = $this->setupDoctrine('FS\SolrBundle\Tests\Doctrine\Mapper');
		
		$factory = new MetaInformationFactory();
		$factory->setDoctrineConfiguration($doctrineConfiguration);
		
		$informations = $factory->loadInformation(new EntityWithOneToOne());

		$this->assertTrue(is_array($informations->getOneToOne()));
		$this->assertTrue(array_key_exists('oneToOne_rel_i', $informations->getOneToOne()), 'document fieldname as key');
		$this->assertEquals(1, count($informations->getOneToOne()));
		
		$relationInformation = array_pop($informations->getOneToOne());
		$this->assertInstanceof('FS\SolrBundle\Doctrine\Mapper\MetaInformation', $relationInformation);
		
		$this->assertEquals('FS\SolrBundle\Tests\Doctrine\Mapper\ValidTestEntity',$relationInformation->getClassName(), 'relation class');
		$this->assertEquals(3, count($relationInformation->getFields()), 'ordinary fields of relation class');
	}
}

?>