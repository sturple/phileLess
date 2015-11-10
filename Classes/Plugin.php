<?php
/**
* Plugin Class PhileLess
*/
namespace Phile\Plugin\Sturple\PhileLess;


class Plugin extends \Phile\Plugin\AbstractPlugin implements \Phile\Gateway\EventObserverInterface {
	
	protected $settings = array();
	protected $logger = null;
	protected $less = null;
	protected $formatter = array('lessjs','compressed','classic');
	protected $config;
	
	public function __construct() {
		\Phile\Event::registerEvent('plugins_loaded', $this);
		\Phile\Core\Event::registerEvent('config_loaded', $this);
		
		$this->logger = (new \Phile\Plugin\Sturple\PhileLogger\Plugin($relDir='lib/cache/logs', $logLevel='debug', $options=array()))->getLogger();
		$this->config = \Phile\Registry::get('Phile_Settings');
		
	}
	
	public function on($eventKey, $data = null)
	{
		$comments = false;
		if ($eventKey == 'config_loaded'){
			$this->less = new \lessc;
			
			//setting the formatter comparing to insure it is a valid choice.
			if (in_array($this->settings['formatter'], $this->formatter))
			{
				$this->less->setFormatter($this->settings['formatter']);
			}
			
			//enable comments or not
			$comments = ($this->settings['comments'] === true) ? true : false;
			$this->less->setPreserveComments($comments);				
			$this->autoCompileLess();
		}		
	}
	
	private function  autoCompileLess()
	{
		$inputFile = $this->settings['inputFile'];
		$outputFile = $this->settings['outputFile'];
		// load the cache
		$cacheFile = $inputFile.".cache";		
		if (file_exists($cacheFile))
		{
			$cache = unserialize(file_get_contents($cacheFile));
		}
		else
		{
			$cache = $inputFile;
		}
		$newCache = $this->less->cachedCompile($cache);
		if (!is_array($cache) || $newCache["updated"] > $cache["updated"]) {
			file_put_contents($cacheFile, serialize($newCache));
			file_put_contents($outputFile, $newCache['compiled']);
		}	
	}
}
