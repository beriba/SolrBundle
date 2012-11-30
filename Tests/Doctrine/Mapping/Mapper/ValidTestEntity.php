<?php
namespace FS\SolrBundle\Tests\Doctrine\Mapping\Mapper;

use FS\SolrBundle\Doctrine\Annotation as Solr;

/**
 * 
 * @Solr\Document(boost="1")
 */
class ValidTestEntity {
	
	/**
	 * @Solr\Id
	 */
	private $id;
	
	/**
	 * @Solr\Field(type="text", boost="1.3")
	 * 
	 * @var text
	 */
	private $text;
	
	/**
	 * @Solr\Field(type="string")
	 * 
	 * @var text
	 */
	private $title;
	
	/**
	 * @Solr\Field(type="date")
	 * 
	 * @var date
	 */
	private $created_at;
	
	public function getId() {
		return $this->id;
	}	
	
	public function setId($id) {
		$this->id = $id;
	}
	
	/**
	 * @return the $text
	 */
	public function getText() {
		return $this->text;
	}

	/**
	 * @return the $title
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @param string $text
	 */
	public function setText($text) {
		$this->text = $text;
	}

	/**
	 * @param string $title
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	
	
}

?>