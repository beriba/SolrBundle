<?php
namespace FS\SolrBundle\Doctrine\ODM\Listener;

use FS\SolrBundle\SolrQueryFacade;

use FS\SolrBundle\SolrFacade;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;

class DeleteDocumentListener {
    
    /**
     * @var SolrFacade
     */
    private $solrFacade = null;

    /**
     * @param SolrFacade $solrFacade
     */
    public function __construct(SolrFacade $solrFacade) {
        $this->solrFacade = $solrFacade;
    }
    
    /**
     * @param LifecycleEventArgs $args
     */
    public function preRemove(LifecycleEventArgs $args) {
        $entity = $args->getDocument();
        
        try {
            $this->solrFacade->removeDocument($entity);
        } catch (\RuntimeException $e) {}       
    }
}

?>