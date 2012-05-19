<?php
namespace FS\SolrBundle\Tests\Util;

use FS\SolrBundle\Doctrine\Mapper\RelationMetaInformation;

use FS\SolrBundle\Doctrine\Annotation\OneToOne;

use FS\SolrBundle\Doctrine\Annotation\Field;

use FS\SolrBundle\Tests\Doctrine\Mapper\ValidTestEntity;

use FS\SolrBundle\Doctrine\Mapper\MetaInformation;

class MetaTestInformationFactory {
	/**
	 * @return MetaInformation
	 */
	public static function getMetaInformation() {
		$entity = new ValidTestEntity();
		$entity->setId(2);
	
		$metaInformation = new MetaInformation();
	
		$title = new Field(array('name'=>'title','type'=>'string'));
		$text = new Field(array('name'=>'text','type'=>'text'));
		$createdAt = new Field(array('name'=>'created_at','type'=>'date'));
	
		$metaInformation->setFields(array($title, $text, $createdAt));
		
		$fieldMapping = array(
			'id'		=> 'id',
			'title_s'	=> 'title',
			'text_t'	=> 'text',
			'created_at_dt'=> 'created_at'		
		);
		$metaInformation->setFieldMapping($fieldMapping);
		$metaInformation->setEntity($entity);
		$metaInformation->setDocumentName('validtestentity');
		$metaInformation->setClassName(get_class($entity));
		
		return $metaInformation;
	}
	
	/**
	 * @return MetaInformation
	 */	
	public static function getMetaInformationWithRelations() {
		$metainformation = self::getMetaInformation();
		
		$entity = new ValidTestEntity();
		$entity->setId(10);
		
		$information = new MetaInformation();
		$information->setEntity($entity);
		$information = new RelationMetaInformation($information);
		$information->setFieldname('author_rel_i');
		
		$metainformation->setOneToOne(array($information));
		
		return $metainformation;
	}
	
}

?>