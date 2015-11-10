<?php
/**
* Plugin Class PhileLess
*/
namespace Phile\Plugin\Sturple\PhileLess;


class Plugin extends \Phile\Plugin\AbstractPlugin implements \Phile\Gateway\EventObserverInterface {
	
	protected $settings = array();
	protected $logger = null;
	
	public function __construct() {
		\Phile\Event::registerEvent('plugins_loaded', $this);
		\Phile\Core\Event::registerEvent('config_loaded', $this);
		
		$this->logger = (new \Phile\Plugin\Sturple\PhileLogger\Plugin($relDir='lib/cache/logs', $logLevel='debug', $options=array()))->getLogger();
		
	}
	
	public function on($eventKey, $data = null)
	{		
		if ($eventKey == 'config_loaded'){
			$this->logger->notice('Configs loaded');			
			$less = new \leafo\lessphp\lesscs;
			$this->logger->notice($less->compile('body{ #footer { padding: 3 + 4px}}'));
		}		
	}
}
