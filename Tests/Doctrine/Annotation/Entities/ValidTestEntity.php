<?php
namespace FS\SolrBundle\Tests\Doctrine\Annotation\Entities;

use FS\SolrBundle\Doctrine\Annotation as Solr;

/**
 * 
 * @author Florian
 * @Solr\Document
 */
class ValidTestEntity {
	
	/**
	 * @Solr\Id
	 */
	private $id;
	
	/**
	 * @Solr\Field(type="text")
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
	 * @param \FS\BlogBundle\Tests\Solr\Doctrine\Mapper\text $text
	 */
	public function setText($text) {
		$this->text = $text;
	}

	/**
	 * @param \FS\BlogBundle\Tests\Solr\Doctrine\Mapper\text $title
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	
	
}

?>