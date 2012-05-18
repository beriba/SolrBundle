<?php
namespace FS\SolrBundle\Doctrine\Mapper;

use FS\SolrBundle\Doctrine\Mapper\Mapping\AbstractDocumentCommand;
use FS\SolrBundle\Doctrine\Annotation\Index as Solr;
use Doctrine\Common\Annotations\AnnotationReader;

class EntityMapper {
	/**
	 * 
	 * @var CreateDocumentCommandInterface
	 */
	private $mappingCommand = null;
	
	public function setMappingCommand(AbstractDocumentCommand $command) {
		$this->mappingCommand = $command;
	}
	
	/**
	 * 
	 * @param MetaInformation $meta
	 * @return null|\SolrDocument
	 */
	public function toDocument(MetaInformation $meta) {
		if ($this->mappingCommand instanceof AbstractDocumentCommand) {
			return $this->mappingCommand->createDocument($meta);
		}
		
		return null;
	}
	
	/**
	 * 
	 * @param \ArrayAccess $document
	 * @param object $targetEntity
	 * @return object
	 */
	public function toEntity(\ArrayAccess $document, $sourceTargetEntity) {
		if (null === $sourceTargetEntity) {
			throw new \InvalidArgumentException('$sourceTargetEntity should not be null');
		}
		
		$targetEntity = clone $sourceTargetEntity;
		
		$reflectionClass = new \ReflectionClass($targetEntity);
		foreach ($document as $property => $value) {
			try {
				$classProperty = $reflectionClass->getProperty($this->removeFieldSuffix($property));
			} catch (\ReflectionException $e) { 
				continue;
			}

			$classProperty->setAccessible(true);
			$classProperty->setValue($targetEntity, $value);
		}
		
		return $targetEntity;
	}
	
	private function removeFieldSuffix($property) {
		if ($pos = strpos($property, '_')) {
			return substr($property, 0, $pos);
		}
		
		return $property;
	}
}

?>