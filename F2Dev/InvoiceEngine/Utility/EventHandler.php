<?php
/**
 * Event Handler
 * $Id: Invoice.php 26 2013-01-23 05:53:13Z wgraber $
 *
 * Acts on events
 *
 * @link http://f2dev.com/prjs/invoice-engine
 * @package InvoiceEngine
 * @subpackage Utility
 *
 * @license http://f2dev.com/prjs/invoice-engine/lic 
 * Please see the license.txt file or the url above for full copyright and license information.
 * @copyright Copyright 2018 F2 Developments, Inc.
 *
 * @author William Graber <wgraber@f2developments.com>
 * @author $LastChangedBy: wgraber $
 *
 * @version $Revision: 26 $
 */

 namespace F2Dev\InvoiceEngine\Utility;
 
 use F2Dev\InvoiceEngine\Utility\EventInterface;
 use F2Dev\InvoiceEngine\Utility\Exceptions;
 
 class EventHandler
 {
	public $type;
	protected $handler;
	protected $args;
	
	/**
	 * Defines an event handler.
	 * @param string $sEventClass Class of Events to handle. Given class must implement EventInterface.
	 * @param callable $cHandler Callable object to execute on event.
	 * @param array $aHandlerArgs Additional arguments to be passed to the handler function. The event object will always be the first argument.
	 * @throws Exceptions\InvalidEventException When passed a $sEventClass that does not implement EventInterface
	 */
	public function __construct(string $sEventClass, callable $cHandler, array $aHandlerArgs)
	{
		$this->type = $sEventClass;
		
		if (!in_array(EventInterface::class, class_implements($this->type)))
		{
			throw new Exceptions\InvalidEventException("Given class must implement EventInterface.");
		}
		
		$this->handler = $cHandler;
		$this->args = $aHandlerArgs;
	}
	
	public function handle(EventInterface $event)
	{
		if ($event instanceof $this->type)
		{
			$this->handler($event, ...$this->args);
		}
	}
 }