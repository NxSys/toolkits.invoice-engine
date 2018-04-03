<?php

namespace F2Dev\InvoiceEngine\Invoice\Events;

use F2Dev\InvoiceEngine\Invoice\Invoice;
use F2Dev\InvoiceEngine\Utility;

class InvoiceEvent implements Utility\EventInterface
{
	public function __construct(Invoice $oInvoice)
	{
		$this->invoice = $oInvoice;
	}
	
	public function getEventData()
	{
		return $this->invoice;
	}
}