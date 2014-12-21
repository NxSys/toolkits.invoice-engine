<?php
/**
 * SimpleCalculator
 * $Id: SimpleCalculator.php 26 2013-01-23 05:53:13Z wgraber $
 *
 * A basic calculator, which adds up the price of each Item, applying Item discounts,
 * then adds the Invoice's Tax Rate.
 *
 * @link http://f2dev.com/prjs/invoice-engine
 * @package InvoiceEngine
 * @subpackage Calculator
 *
 * @license http://f2dev.com/prjs/invoice-engine/lic 
 * Please see the license.txt file or the url above for full copyright and license information.
 * @copyright Copyright 2013 F2 Developments, Inc.
 *
 * @author William Graber <wgraber@f2developments.com>
 * @author $LastChangedBy: wgraber $
 *
 * @version $Revision: 26 $
 */

namespace F2Dev\InvoiceEngine\Calculator\Calculators;

use F2Dev\InvoiceEngine\Calculator\CalculatorInterface;
use F2Dev\InvoiceEngine\Invoice\Invoice;


class SimpleCalculator implements CalculatorInterface
{
	public function __construct()
	{
		
	}
	
	/**
	 * Gets the total cost of all items in the invoice, using getTotal() on each Item,
	 * then adds tax via Invoice->getTaxRate().
	 *
	 * @var Invoice Invoice to total.
	 *
	 * @return float Total cost of the invoice.
	 */
	public function getTotal(Invoice $oInvoice)
	{
		$fTotal = 0.0;
		
		$aItems = $oInvoice->getItems();
		
		foreach ($aItems as $iKey => $oItem)
		{
			$fTotal += $oItem->getTotal();
		}
		
		return $fTotal * (1. + $oInvoice->getTaxRate());
	}
	
	
	/**
	 * Gets the total cost of all items in the invoice, using getTotal() on each Item,
	 * but does not apply tax.
	 *
	 * @var Invoice Invoice to total.
	 *
	 * @return float Total cost of the invoice Items.
	 */
	public function getSubTotal(Invoice $oInvoice)
	{
		$fSubTotal = 0.0;
		
		$aItems = $oInvoice->getItems();
		
		foreach ($aItems as $iKey => $oItem)
		{
			$fSubTotal += $oItem->getTotal();
		}
		
		return $fSubTotal;
	}
}