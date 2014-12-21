<?php
/**
 * CalculatorInterface
 * $Id: CalculatorInterface.php 26 2013-01-23 05:53:13Z wgraber $
 *
 * A user-extensible Interface for a Calculator object, which provides Invoices with definable
 * calculation strategies.
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

namespace F2Dev\InvoiceEngine\Calculator;

use F2Dev\InvoiceEngine\Invoice\Invoice;

/**
 * Interface of Calculator objects.
 */
interface CalculatorInterface
{
	/**
	 * Sets up any general requirements for this Calculator.
	 * Should not be anything specific regarding any invoice.
	 */
	public function __construct();
	
	/**
	 * Using the calculator's totaling strategy, get the total for the given invoice, and return it.
	 *
	 * @var Invoice The invoice to total.
	 *
	 * @return float The total.
	 */
	public function getTotal(Invoice $oInvoice);
	
	
	/**
	 * Using the calculator's subtotaling strategy, get the subtotal from the Invoice Items, without global modifiers, and return it.
	 *
	 * @var Invoice The Invoice to subtotal.
	 *
	 * @return float The subtotal.
	 */
	public function getSubTotal(Invoice $oInvoice);
}