<?php
/**
* Plugin Class PhileLess
* @author sturple
* @link https://github.com/sturple/phileLess
* @license http://oensource.org/licenses/MIT
* @package Phile\Plugin\Sturple\PhileLess
*
*/
namespace Phile\Plugin\Sturple\PhileLess;

class Plugin extends \Phile\Plugin\AbstractPlugin implements \Phile\Gateway\EventObserverInterface {
	
	protected $settings = array();
	protected $logger = null;
	protected $less = null;
	protected $config;
	protected $events = [
		'config_loaded'=> 'onConfigLoaded'
	];
	
	public function __construct() {
		\Phile\Event::registerEvent('plugins_loaded', $this);
		\Phile\Core\Event::registerEvent('config_loaded', $this);
		
		$this->logger = (new \Phile\Plugin\Sturple\PhileLogger\Plugin($relDir='lib/cache/logs', $logLevel='debug', $options=array()))->getLogger();
		$this->config = \Phile\Registry::get('Phile_Settings');
		
	}
	
	/*
	 * parsing less file and creating output.
	 * 
	 * @param array $eventData
	 */
	
	protected function onConfigLoaded(array $eventData)
	{
		// create new instance of 
		$this->less = new \lessc;
		
		//enable comments or not		
		$comments = false;
		$comments = ($this->settings['comments'] === true) ? true : false;
		$this->less->setPreserveComments($comments);
		
		// setting input file and setting up cache file
		$inputFile = $this->settings['inputFile'];
		$cacheFile = $inputFile.".cache";
		
		if (!file_exists($inputFile))
		{
			throw new \RuntimeException(
				"Input less file {$inputFile} not found.", 3472001	
			);
		}				
		// outupt file
		$outputFile = $this->settings['outputFile'];
		
		//setting the formatter comparing to insure it is a valid choice.
		if (in_array($this->settings['formatter'],  array('lessjs','compressed','classic'))){
			$this->less->setFormatter($this->settings['formatter']);
		}				

		//operation to write to output and cache file.
		if (file_exists($cacheFile))
		{
			$cache = unserialize(file_get_contents($cacheFile));
		}
		else
		{
			$cache = $inputFile;
		}		
		
		// create new cache
		try {
			$newCache = $this->less->cachedCompile($cache);
		} catch (Exception $ex){
			throw new \RuntimeException (
				"Compile Error: ". $ex->getMessage(), 3472003
			);
		}
		
		
		// update files if cache has changed
		if (!is_array($cache) || $newCache["updated"] > $cache["updated"]) {
			
			if (!file_put_contents($cacheFile, serialize($newCache))){
				throw new \RuntimeException (
					"Could not write to cache file {$cacheFile}.", 3472002
				);
			}
			if (!file_put_contents($outputFile, $newCache['compiled'])){
				throw new \RuntimeException (
					"Could not write to output file {$outputFile}.", 3472002
				);
			}
			
		}	
	}

	


}
