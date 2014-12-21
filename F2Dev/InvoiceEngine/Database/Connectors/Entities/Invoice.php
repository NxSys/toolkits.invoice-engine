<?php

/**
 * Invoice
 * $Id: Invoice.php 19 2012-12-27 11:43:04Z wgraber $
 *
 * A Doctrine Entity representing an Invoice.
 * 
 * @link http://f2dev.com/prjs/invoice-engine
 * @package InvoiceEngine
 * @subpackage Database
 *
 * @license http://f2dev.com/prjs/invoice-engine/lic 
 * Please see the license.txt file or the url above for full copyright and license information.
 * @copyright Copyright 2013 F2 Developments, Inc.
 *
 * @author William Graber <wgraber@f2developments.com>
 * @author $LastChangedBy: wgraber $
 *
 * @version $Revision: 19 $
 */

namespace F2Dev\InvoiceEngine\Database\Connectors\Entities;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity @Table(name="invoices")
 **/
class Invoice
{
	/** @Id @Column(type="integer") @GeneratedValue **/
	protected $id;
	
	/** @Column(type="string") **/
	protected $invoiceId;
	
	/** @Column(type="string") **/
	protected $header;
	
	/** @Column(type="string") **/
	protected $footer;
	
	/** @Column(type="string") **/
	protected $customerInfo;
	
	/** @Column(type="string") **/
	protected $customerId;
	
	/** @Column(type="string") **/
	protected $postDate;
	
	/** @Column(type="integer") **/
	protected $paymentTerm;
	
	/** @Column(type="string") **/
	protected $dueDate;
	
	/** @Column(type="string") **/
	protected $description;
	
	/** @Column(type="string") **/
	protected $message;
	
	/** @Column(type="string") **/
	protected $signature;
	
	/**
     * @OneToMany(targetEntity="InvoiceInfo", mappedBy="invoice")
     */
	protected $additionalInfo;
	
	/** @Column(type="float") **/
	protected $taxRate;
	
	/** @Column(type="string") **/
	protected $calculator;
	
	/**
     * @OneToMany(targetEntity="Item", mappedBy="invoice")
     */
	protected $items;
	
	public function __construct()
	{
		$this->items = new ArrayCollection();
		$this->additionalInfo = new ArrayCollection();
	}
	
	public function getId()
	{
			return $this->id;
	}
	
	public function setInvoiceId($newInvoiceid)
	{
			$this->invoiceId = $newInvoiceid;
	}
	
	public function getInvoiceId()
	{
			return $this->invoiceId;
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
	}
	
	public function getPaymentTerm()
	{
			return $this->paymentTerm;
	}
	
	public function setDueDate($newDuedate)
	{
			$this->dueDate = $newDuedate;
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
	
	public function addAdditionalInfo($newAdditionalinfo)
	{
			$this->additionalInfo[] = $newAdditionalinfo;
	}
	
	public function getAdditionalInfo()
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
	
	public function addItem($newItem)
	{
			$this->items[] = $newItem;
	}
	
	public function getItems()
	{
			return $this->items;
	}
}