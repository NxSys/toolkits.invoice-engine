<?php

/**
 * Invoice
 * $Id: Invoice.php 26 2013-01-23 05:53:13Z wgraber $
 *
 * A class representing a Single Invoice. Provides all necessary functionality for creation and manipulation
 * of Invoices.
 *
 * @link http://f2dev.com/prjs/invoice-engine
 * @package InvoiceEngine
 * @subpackage Invoice
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

namespace F2Dev\InvoiceEngine\Invoice;

use F2Dev\InvoiceEngine\Invoice\Item\Item;
use F2Dev\InvoiceEngine\Calculator\Calculators\SimpleCalculator;

class Invoice
{
	public function __construct($sInvoiceID,
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
		$this->id = $sInvoiceID;
		$this->header = $sInvoiceHeader;
		$this->footer = $sInvoiceFooter;
		$this->customerInfo = $sCustomerInfo;
		$this->customerId = $sCustomerID;
		if ($sInvoiceDate === "")
		{
			$this->postDate = strftime("%m/%d/%Y");
		}
		else
		{
			$this->postDate = $sInvoiceDate;
		}
		$this->paymentTerm = $iPaymentTerm;
		$this->dueDate = strftime("%m/%d/%Y", strtotime($this->postDate) + ($iPaymentTerm * 60 * 24));
		$this->description = $sInvoiceDescription;
		$this->message = $sCustomerMessage;
		$this->signature = $sSigningMessage;
		$this->additionalInfo = $aAdditionalInfo;
		$this->taxRate = $fTaxRate;
		if ($oTotalCalculator === null)
		{
			$this->calculator = new SimpleCalculator();
		}
		else
		{
			$this->calculator = $oTotalCalculator;
		}
		$this->items = array();
	}
	
	public function setId($newID)
	{
		$this->id = $newID;
	}
	
	public function getId()
	{
		return $this->id;
	}
	
	public function setHeader($newHeader)
	{
		$this->header = $newHeader;
	}
	
	public function getHeader()
	{
		return $this->header;
	}
	
	public function setFooter($newFooter)
	{
		$this->footer = $newFooter;
	}
	
	public function getFooter()
	{
		return $this->footer;
	}
	
	public function setCustomerInfo($newCustomerinfo)
	{
		$this->customerInfo = $newCustomerinfo;
	}
	
	public function getCustomerInfo()
	{
		return $this->customerInfo;
	}
	
	public function setCustomerId($newCustomerid)
	{
			$this->customerId = $newCustomerid;
	}
	
	public function getCustomerId()
	{
			return $this->customerId;
	}
	
	public function setPostDate($newPostdate)
	{
			$this->postDate = $newPostdate;
	}
	
	public function getPostDate()
	{
			return $this->postDate;
	}
	
	public function setPaymentTerm($newPaymentterm)
	{
			$this->paymentTerm = $newPaymentterm;
			$this->dueDate = strftime("%m/%d/%Y", strtotime($this->postDate) + ($this->paymentTerm * 60 * 60 * 24));
	}
	
	public function getPaymenTterm()
	{
			return $this->paymentTerm;
	}
	
	public function setDueDate($newDuedate)
	{
			$this->dueDate = $newDuedate;
			$this->paymentTerm = intval((strtotime($this->dueDate) - strtotime($this->postDate)) / (60 * 60 * 24));
	}
	
	public function getDueDate()
	{
			return $this->dueDate;
	}
	
	public function setDescription($newDescription)
	{
			$this->description = $newDescription;
	}
	
	public function getDescription()
	{
			return $this->description;
	}
	
	public function setMessage($newMessage)
	{
			$this->message = $newMessage;
	}
	
	public function getMessage()
	{
			return $this->message;
	}
	
	public function setSignature($newSignature)
	{
			$this->signature = $newSignature;
	}
	
	public function getSignature()
	{
			return $this->signature;
	}
	
	public function addInfo($sInfoName, $sInfoData)
	{
		$this->additionalInfo[$sInfoName] = $sInfoData;
	}
	
	public function removeInfo($sInfoName)
	{
		unset($this->additionalInfo[$sInfoName]);
	}
	
	public function getInfo($sInfoName)
	{
		return $this->additionalInfo[$sInfoName];
	}
	
	public function getAllInfo()
	{
		return $this->additionalInfo;
	}
	
	public function setTaxRate($newTaxrate)
	{
			$this->taxRate = $newTaxrate;
	}
	
	public function getTaxRate()
	{
			return $this->taxRate;
	}
	
	public function setCalculator($newCalculator)
	{
			$this->calculator = $newCalculator;
	}
	
	public function getCalculator()
	{
			return $this->calculator;
	}
	
	public function newItem($sItemID,
							$sItemDisplayName="",
							$sItemDescription="",
							$fItemQuantity=1.,
							$fItemUnitCost=0.,
							$fItemDiscount=0.)
	{
		$oNewItem = new Item($sItemID,
							$sItemDisplayName,
							$sItemDescription,
							$fItemQuantity,
							$fItemUnitCost,
							$fItemDiscount);
		$this->addItem($oNewItem);
		return $oNewItem;
	}
	
	public function addItem($oNewItem)
	{
		$this->items[] = $oNewItem;
	}
	
	public function removeItem($oItemToRemove)
	{
		foreach ($this->items as $iIndex => $oItem)
		{
			if ($oItem === $oItemToRemove)
			{
				unset($this->items[$iIndex]);
			}
		}
	}
	
	public function getItems()
	{
		return $this->items;
	}
	
	public function getTotal()
	{
		return $this->calculator->getTotal($this);
	}
	
	public function getSubTotal()
	{
		return $this->calculator->getSubTotal($this);
	}
	
	public function __toString()
	{
		return $this->id;
	}
}