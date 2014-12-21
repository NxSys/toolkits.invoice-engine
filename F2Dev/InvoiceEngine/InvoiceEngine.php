<?php

/**
 * InvoiceEngine
 * $Id: InvoiceEngine.php 22 2013-01-17 18:02:49Z wgraber $
 *
 * Main Class of the Invoice Engine, providing access to all functionality.
 *
 * @link http://f2dev.com/prjs/invoice-engine
 * @package InvoiceEngine
 * @subpackage InvoiceEngine
 *
 * @license http://f2dev.com/prjs/invoice-engine/lic 
 * Please see the license.txt file or the url above for full copyright and license information.
 * @copyright Copyright 2013 F2 Developments, Inc.
 *
 * @author William Graber <wgraber@f2developments.com>
 * @author $LastChangedBy: wgraber $
 *
 * @version $Revision: 22 $
 */

namespace F2Dev\InvoiceEngine;

use Symfony\Component\ClassLoader\UniversalClassLoader;

use F2Dev\InvoiceEngine\Invoice\Invoice;
use F2Dev\InvoiceEngine\Invoice\Item\Item;

class InvoiceEngine
{
	
	/**
	 * Contructor for the Invoice Engine. Should be passed an instantiated object implementing
	 * the F2Dev\InvoiceEngine\Database\DataCouple interface.
	 *
	 * @param DataCouple $oDataConnector Instance of a DataConnector object.
	 * @param Array $aParams Associtative array of parameters to pass to the DataConnector.
	 * @param Bool $bAutoload Optional argument, defaulting to false. If true, use the built-in autoloader for InvoiceEngine. If false, ensure you have the InvoiceEngine Namespaces registered with your autoloader.
	 */
	public function __construct($sDataConnector, $aParams, $bAutoload = false)
	{
		if ($bAutoload)
		{
			$this->autoload();
		}
		$sDataConnector = '\F2Dev\InvoiceEngine\Database\Connectors\\' . $sDataConnector;
		
		$this->dataConnector = new $sDataConnector($aParams);
		$this->invoices = array();
	}
	
	/**
	 * Sets up required Autoloading of Invoice Engine.
	 * Needed when not using external autoloading.
	 */
	public function autoload()
	{
		$this->loader = new UniversalClassLoader();
		$this->loader->registerNamespace('F2Dev\InvoiceEngine', __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..');
		$this->loader->registerNamespace('Doctrine', __DIR__ . DIRECTORY_SEPARATOR . "Vendors" . DIRECTORY_SEPARATOR);
		$this->loader->register(true);
	}
	
	/**
	 * Creates and stores a new Invoice. Apart from the InvoiceID, all arguments are optional.
	 * Any parameters whose descriptions begin with Information are used to provide information
	 * to the output of the Invoice. Depending on your output mechanism, you can store markup,
	 * such as html, in these variables. They do not affect any calculations regarding this invoice,
	 * and are simply used to convey information to the final output.
	 *
	 * In a basic linear format, the Invoice should look like:
	 *
	 * InvoiceHeader
	 * CustomerInfo
	 * Post Date and Due Date info.
	 * InvoiceDescription
	 * CustomerMessage
	 * Itemization of Invoice
	 * Totals
	 * SigningMessage
	 * InvoiceFooter
	 *
	 * @param string $sInvoiceID Required, unique ID for this Invoice.
	 * @param string $sInvoiceHeader Information which goes at the beginning of the Invoice. Company info, etc.
	 * @param string $sInvoiceFooter Information which goes at the end of the Invoice. Legal terms, additional contact info, etc.
	 * @param string $sCustomerInfo Information about the customer on the Invoice. Name, address, account information, etc.
	 * @param string $sCustomerID Unique ID denoting the entity to which this Invoice is regarding.
	 * @param string $sInvoiceDate Effective date of this invoice, i.e. when it is sent out, not the due date. Should be in the format "MM/DD/YYYY", but any string accepted by strtotime() is acceptable.
	 * @param int $iPaymentTerm Number of days from the InvoiceDate that the Invoice is due.
	 * @param string $sInvoiceDescription Information describing what this Invoice is for.
	 * @param string $sCustomerMessage Information which should be displayed just before the Items. Greetings, confirmation numbers, or any other message to the recipient.
	 * @param string $sSigningMessage Information which is displayed just after the Items.
	 * @param array $aAdditionalInfo Information which may be used by certain output strategies. An associative array.
	 * @param float $fTaxRate Basic tax rate which is applied to the total in most calculators. No tax is 0.0, 5% is 0.05, etc.
	 * @param CalculatorInterface $oTotalCalculator An instance of an object which implements the CalculatorInterface. Used to total the items.
	 *
	 * @return Invoice The invoice which has just been created.
	 */
	public function newInvoice( $sInvoiceID,
								$sInvoiceHeader = "",
								$sInvoiceFooter = "",
								$sCustomerInfo = "",
								$sCustomerID = "",
								$sInvoiceDate = "",
								$iPaymentTerm = 30,
								$sInvoiceDescription = "",
								$sCustomerMessage = "",
								$sSigningMessage = "",
								$aAdditionalInfo = array(),
								$fTaxRate = 0.0,
								$oTotalCalculator = null)
	{
		$oNewInvoice = new Invoice($sInvoiceID,
								$sInvoiceHeader,
								$sInvoiceFooter,
								$sCustomerInfo,
								$sCustomerID,
								$sInvoiceDate,
								$iPaymentTerm,
								$sInvoiceDescription,
								$sCustomerMessage,
								$sSigningMessage,
								$aAdditionalInfo,
								$fTaxRate,
								$oTotalCalculator);
		$this->invoices[$sInvoiceID] = $oNewInvoice;
		return $oNewInvoice;
	}
	
	public function getInvoices()
	{
		return $this->invoices;
	}
	
	public function getInvoice($sInvoiceID)
	{
		if (isset($this->invoices[$sInvoiceID]))
		{
			return $this->invoices[$sInvoiceID];
		}
		else
		{
			$oInvoice = $this->dataConnector->getInvoice($sInvoiceID);
			if ($oInvoice != null)
			{
				$this->invoices[$sInvoiceID] = $oInvoice;
			}
			return $oInvoice;
		}
	}
	
	public function persistInvoice(Invoice $oInvoice)
	{
		$this->invoices[$oInvoice->getID()] = $oInvoice;
		$this->dataConnector->persistInvoice($oInvoice);
	}
	
	public function persistInvoices()
	{
		foreach ($this->invoices as $oInvoice)
		{
			$this->dataConnector->persistInvoice($oInvoice);
		}
	}
	
	/**
	 * Searches for Invoices by the given arguments.
	 * The aSearchArgs array should be an associative array of column name => value to look for.
	 * For more complex searches, use SearchEngine.
	 *
	 * @var $aSearchArgs Associative array of data to search by.
	 *
	 * @return array Returns an array of Invoice objects.
	 */
	public function findInvoiceBy($aSearchArgs)
	{
		return $this->dataConnector->findInvoiceBy($aSearchArgs);
	}
	
}