<?
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );

class Events
{
	var $_events = array();
	
	function registerFunction($event, $function)
	{
		$this->_events[$event][] = $function;
	}
	
	function trigger( $event, $args=null)
	{
		$result = array();

		if ($args === null) {
			$args = array();
		}
		
		if (isset( $this->_events[$event] )) {
			foreach ($this->_events[$event] as $func) {
				if (function_exists( $func )) {
					$result[] = call_user_func_array( $func, $args );
				}
			}
		}
		return $result;
	}
	
	function call( $event ) {
		$args =func_get_args();
		array_shift( $args );

		if (isset( $this->_events[$event] )) {
			foreach ($this->_events[$event] as $func) {
				if (function_exists( $func )) {
					return call_user_func_array( $func, $args );
				}
			}
		}
		return null;
	}
}

$events = new Events();