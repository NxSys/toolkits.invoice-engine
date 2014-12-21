<?php

/**
 * Invoice
 * $Id: InvoiceInfo.php 19 2012-12-27 11:43:04Z wgraber $
 *
 * A Doctrine Entity representing additional info about an Invoice.
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

/**
 * @Entity @Table(name="invoice_info")
 **/
class InvoiceInfo
{
	/** @Id @Column(type="integer") @GeneratedValue **/
	protected $id;
	
	/** @Column(type="string") **/
	protected $key;
	
	/** @Column(type="string") **/
	protected $value;
	
	/**
     * @ManyToOne(targetEntity="Invoice", inversedBy="additionalInfo")
     **/
	protected $invoice;
	
	public function getId()
	{
			return $this->id;
	}
	
	public function setKey($newKey)
	{
			$this->key = $newKey;
	}
	
	public function getKey()
	{
			return $this->key;
	}
	
	public function setValue($newValue)
	{
			$this->value = $newValue;
	}
	
	public function getValue()
	{
			return $this->value;
	}
	
	public function setInvoice($newInvoice)
	{
			$this->invoice = $newInvoice;
	}
	
	public function getInvoice()
	{
			return $this->invoice;
	}
}