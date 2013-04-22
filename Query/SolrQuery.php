<?php

namespace FS\SolrBundle\Query;

use FS\SolrBundle\SolrFacade;

class SolrQuery extends AbstractQuery
{

    /**
     * @var array
     */
    private $mappedFields = array();

    /**
     * @var array
     */
    private $searchTerms = array();

    /**
     * @var bool
     */
    private $useAndOperator = false;

    /**
     *
     * @var SolrFacade
     */
    private $solrFacade = null;
    private $rows;
    private $start;
    private $highlight = false;
    private $highlightFL;
    private $highlightSnippets;
    private $highlightSimplePre;
    private $highlightSimplePost;

    /**
     * @param SolrFacade $solr
     */
    public function __construct(SolrFacade $solr)
    {
        parent::__construct();

        $this->solrFacade = $solr;
    }

    /**
     * @return array
     */
    public function getResponse()
    {
        return $this->solrFacade->getResponse($this);
    }

    /**
     * @return array
     */
    public function getResult($response)
    {
        return $this->solrFacade->query($this, $response);
    }

    /**
     * @return array
     */
    public function getHighlights($response)
    {
        return $response->highlighting;
    }

    /**
     * @return array
     */
    public function getMappedFields()
    {
        return $this->mappedFields;
    }

    /**
     * @param array $mappedFields
     */
    public function setMappedFields($mappedFields)
    {
        $this->mappedFields = $mappedFields;
    }

    /**
     * @param bool $strict
     */
    public function setUseAndOperator($strict)
    {
        $this->useAndOperator = $strict;
    }

    /**
     * @return array
     */
    public function getSearchTerms()
    {
        return $this->searchTerms;
    }

    /**
     * @param array $value
     */
    public function queryAllFields($value)
    {
        $this->setUseAndOperator(false);

        foreach ($this->mappedFields as $documentField => $entityField)
        {
            $this->searchTerms[ $documentField ][] = $value;
        }
    }

    /**
     *
     * @param string $field
     * @param string $value
     * @return SolrQuery
     */
    public function addSearchTerm($field, $value)
    {
        $documentFieldsAsValues = array_flip($this->mappedFields);

        if (array_key_exists($field, $documentFieldsAsValues))
        {
            $documentFieldName = $documentFieldsAsValues[ $field ];

            $this->searchTerms[ $documentFieldName ][] = $value;
        }

        return $this;
    }

    /**
     * @param string $field
     * @return SolrQuery
     */
    public function addField($field)
    {
        $entityFieldNames = array_flip($this->mappedFields);
        if (array_key_exists($field, $entityFieldNames))
        {
            $this->solrQuery->addField($entityFieldNames[ $field ]);
        } else
        {
            throw new \Exception('Field ' . $field . ' doesn\'t exist.');
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getQueryString()
    {
        $term = '';
        if (count($this->searchTerms) == 0)
        {
            return $term;
        }

        $logicOperator = 'AND';
        if (!$this->useAndOperator)
        {
            $logicOperator = "\n"; //OR operator
        }

        $termCount = 1;
        foreach ($this->searchTerms as $fieldName => $fieldValues)
        {
            foreach ($fieldValues as $fieldValue)
            {
                if ($termCount > 1)
                {
                    $term .= ' ' . $logicOperator . ' ';
                }
                $term .= $fieldName . ':' . $fieldValue . '';
                $termCount++;
            }
        }

        return $term;
    }

    public function getRows()
    {
        return $this->rows;
    }

    public function getStart()
    {
        return $this->start;
    }

    public function getHighlight()
    {
        return $this->highlight;
    }

    public function getHighlightSimplePre()
    {
        return $this->highlightSimplePre;
    }

    public function getHighlightSimplePost()
    {
        return $this->highlightSimplePost;
    }

    public function setRows($rows)
    {
        $this->rows = $rows;
    }

    public function setStart($start)
    {
        $this->start = $start;
    }

    public function setHighlight($highlight)
    {
        $this->highlight = $highlight;
    }

    public function setHighlightSimplePre($highlightSimplePre)
    {
        $this->highlightSimplePre = $highlightSimplePre;
    }

    public function setHighlightSimplePost($highlightSimplePost)
    {
        $this->highlightSimplePost = $highlightSimplePost;
    }

    public function getHighlightFL()
    {
        return $this->highlightFL;
    }

    public function setHighlightFL($highlightFL)
    {
        $this->highlightFL = $highlightFL;
    }

    public function getHighlightSnippets()
    {
        return $this->highlightSnippets;
    }

    public function setHighlightSnippets($highlightSnippets)
    {
        $this->highlightSnippets = $highlightSnippets;
    }

    public function getTotalRows()
    {
        return $this->getResponse()->response->numFound;
    }

}

?>