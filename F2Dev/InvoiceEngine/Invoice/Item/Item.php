<?php

/**
 * Item
 * $Id: Item.php 19 2012-12-27 11:43:04Z wgraber $
 *
 * Class representing a Line Item on an Invoice.
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
 * @version $Revision: 19 $
 */

namespace F2Dev\InvoiceEngine\Invoice\Item;

class Item
{
	public function __construct($sItemID,
								$sItemDisplayName="",
								$sItemDescription="",
								$fItemQuantity=1.,
								$fItemUnitCost=0.,
								$fItemDiscount=0.)
	{
		$this->id = $sItemID;
		if ($sItemDisplayName==="")
		{
			$this->name = $sItemID;
		}
		else
		{
			$this->name = $sItemDisplayName;
		}
		$this->description = $sItemDescription;
		$this->quantity = $fItemQuantity;
		$this->unitCost = $fItemUnitCost;
		$this->discount = $fItemDiscount;
	}
	
	public function setId($sNewID)
	{
		$this->id = $sNewID;
	}
	
	public function getId()
	{
		return $this->id;
	}
	
	public function setName($sNewName)
	{
		$this->name = $sNewName;
	}
	
	public function getName()
	{
		return $this->name;
	}
	
	public function setDescription($sNewDescription)
	{
		$this->description = $sNewDescription;
	}
	
	public function getDescription()
	{
		return $this->description;
	}
	
	public function setQuantity($fNewQuantity)
	{
		$this->quantity = $fNewQuantity;
	}
	
	public function getQuantity()
	{
		return $this->quantity;
	}
	
	public function setUnitCost($fNewUnitCost)
	{
		$this->unitCost = $fNewUnitCost;
	}
	
	public function getUnitCost()
	{
		return $this->unitCost;
	}
	
	public function setDiscount($fNewDiscount)
	{
		$this->discount = $fNewDiscount;
	}
	
	public function getDiscount()
	{
		return $this->discount;
	}
	
	public function getTotal()
	{
		return ($this->quantity * $this->unitCost) * (1. - $this->discount);
	}
	
	public function __toString()
	{
		$sOut = "";
		$aDisplayComponents = array($this->ID,
									$this->name,
									$this->description,
									$this->quantity,
									$this->unitCost,
									$this->discount,
									$this->getTotal());
		
		foreach ($aDisplayComponents as $iIndex => $sComponent)
		{
			$sOut = $sOut . $sComponent;
			if ($iIndex < count($aDisplayComponents) - 1)
			{
				$sOut = $sOut . ", ";
			}
		}
		
		return $sOut;
	}
}