<?php
namespace FS\SolrBundle\Tests\Doctrine\Mapping\Mapper\Command;

abstract class SolrDocumentTest extends \PHPUnit_Framework_TestCase {
	const FIELDS_ALWAYS_MAPPED = 2;
	
	protected function assertHasDocumentFields($document, $expectedFields) {
		$actualFields = $document->getFieldNames();
		foreach ($expectedFields as $expectedField) {
			$this->assertTrue(in_array($expectedField, $actualFields), 'field'. $expectedField .' not in document');
		}
	}
	
	protected function assertFieldCount($expectedCount, \SolrInputDocument $document, $message = '') {
		$this->assertEquals($expectedCount+self::FIELDS_ALWAYS_MAPPED, $document->getFieldCount(), $message);
	}
}

?>