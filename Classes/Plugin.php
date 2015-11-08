<?php
/**
* Plugin Class PhileLess
*/
namespace Phile\Plugin\Sturple\PhileLess;


class Plugin extends \Phile\Plugin\AbstractPlugin implements \Phile\Gateway\EventObserverInterface {
	
	public function __construct() {
		\Phile\Event::registerEvent('plugins_loaded', $this);
	}
	
	public function on($eventKey, $data = null){
  
	}


}
