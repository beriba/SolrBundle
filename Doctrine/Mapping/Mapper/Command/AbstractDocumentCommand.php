<?php
namespace FS\SolrBundle\Doctrine\Mapping\Mapper\Command;

use FS\SolrBundle\Doctrine\Mapping\Mapper\MetaInformation;
use FS\SolrBundle\Doctrine\Annotation\AnnotationReader;

abstract class AbstractDocumentCommand {
	
	/**
	 * @param object $entity
	 * @return \SolrDocument
	 */
	public function createDocument(MetaInformation $meta) {
		$document = new \SolrInputDocument();
		
		$document->addField('id', $meta->getEntityId());
		$document->addField('document_name_s', $meta->getDocumentName());
		$document->setBoost($meta->getBoost());
		
		return $document;
	}	
}

?>