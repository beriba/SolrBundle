<?php
namespace FS\SolrBundle\Doctrine\Mapper\Mapping;

class CommandFactory {
	private $commands = array();
	
	public function get($command) {
		if (!array_key_exists($command, $this->commands)) {
			throw new \RuntimeException(sprintf('%s is an unknown command', $command));
		}
		
		return $this->commands[$command];
	}
	
	public function add(AbstractDocumentCommand $command, $commandName) {
		$this->commands[$commandName] = $command;
	}
	
	/**
	 * @return AbstractDocumentCommand
	 */
	public function getMapAllFieldsCommand() {
		return new ComposedDocumentCommand();
	} 
}

?>