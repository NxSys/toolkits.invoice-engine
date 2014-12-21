<?php

/**
 * Invoice
 * $Id: Item.php 19 2012-12-27 11:43:04Z wgraber $
 *
 * A Doctrine Entity representing an Item.
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
 * @Entity @Table(name="items")
 **/
class Item
{
	/** @Id @Column(type="integer") @GeneratedValue **/
	protected $id;
	
	/** @Column(type="string") **/
	protected $itemId;
	
	/** @Column(type="string") **/
	protected $name;
	
	/** @Column(type="string") **/
	protected $description;
	
	/** @Column(type="integer") **/
	protected $quantity;
	
	/** @Column(type="float") **/
	protected $unitCost;
	
	/** @Column(type="float") **/
	protected $discount;
	
	/**
     * @ManyToOne(targetEntity="Invoice", inversedBy="items")
     **/
	protected $invoice;
	
	public function getId()
	{
			return $this->id;
	}
	
	public function setItemId($newItemid)
	{
				$this->itemId = $newItemid;
	}
	
	public function getItemId()
	{
				return $this->itemId;
	}
	
	public function setName($newName)
	{
			$this->name = $newName;
	}
	
	public function getName()
	{
			return $this->name;
	}
	
	public function setDescription($newDescription)
	{
			$this->description = $newDescription;
	}
	
	public function getDescription()
	{
			return $this->description;
	}
	
	public function setQuantity($newQuantity)
	{
			$this->quantity = $newQuantity;
	}
	
	public function getQuantity()
	{
			return $this->quantity;
	}
	
	public function setUnitCost($newUnitcost)
	{
			$this->unitCost = $newUnitcost;
	}
	
	public function getUnitCost()
	{
			return $this->unitCost;
	}
	
	public function setDiscount($newDiscount)
	{
			$this->discount = $newDiscount;
	}
	
	public function getDiscount()
	{
			return $this->discount;
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