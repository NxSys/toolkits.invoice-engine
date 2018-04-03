<?php
/**
 * Evented Trait
 * $Id: Invoice.php 26 2013-01-23 05:53:13Z wgraber $
 *
 * A trait which provides a simple event handler to a class.
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

use F2Dev\InvoiceEngine\Utility\Exceptions;
use F2Dev\InvoiceEngine\Utility\EventHandler;
use F2Dev\InvoiceEngine\Utility\EventInterface;

trait Evented
{
	protected $handlers = [];
	
	public function addHandler(EventHandler $oHandler)
	{
		$this->handlers[] = $oHandler;
	}
	
	public function fireEvent(EventInterface $oEvent)
	{
		foreach ($this->handlers as $oHandler)
		{
			$oHandler->handle($oEvent);
		}
	}	
}