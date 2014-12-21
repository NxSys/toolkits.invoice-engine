<?php

/**
 * DoctrineDataConnector
 * $Id: DoctrineDataConnector.php 24 2013-01-18 20:09:50Z wgraber $
 *
 * Provides an Interface for the Invoice Engine to the Doctrine ORM.
 * Extends DataCouple 
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
 * @version $Revision: 24 $
 */

namespace F2Dev\InvoiceEngine\Database\Connectors;

use F2Dev\InvoiceEngine\Database;
use Doctrine;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

class DoctrineDataConnector implements Database\DataCouple
{
	public function __construct($aParams = array())
	{
		$oConfig = Setup::createAnnotationMetadataConfiguration(array(__dir__ . DIRECTORY_SEPARATOR . "Entities"), true);
		$em = EntityManager::create($aParams['db'], $oConfig);
		if (isset($aParams["createDb"]) && $aParams["createDb"])
		{
			$oSchemaTool = new \Doctrine\ORM\Tools\SchemaTool($em);
			$classes = array($em->getClassMetadata('\F2Dev\InvoiceEngine\Database\Connectors\Entities\Invoice'),
							 $em->getClassMetadata('\F2Dev\InvoiceEngine\Database\Connectors\Entities\Item'),
							 $em->getClassMetadata('\F2Dev\InvoiceEngine\Database\Connectors\Entities\InvoiceInfo')
							 );
			$oSchemaTool->updateSchema($classes);
		}
		$this->em = $em;
	}
	
	private function getProtoInvoice($sInvoiceID)
	{
		return $this->em->getRepository('F2Dev\InvoiceEngine\Database\Connectors\Entities\Invoice')->findOneBy(array('invoiceId' => $sInvoiceID));
	}
	
	private function getProtoItem($iInvoiceId, $sItemId)
	{
		return $this->em->getRepository('F2Dev\InvoiceEngine\Database\Connectors\Entities\Item')->findOneBy(array('itemId' => $sItemId,
																														 'invoice' => $iInvoiceId));
	}
	
	private function newProtoItem($oProtoInvoice, $oItem)
	{
		//Get Item Entity if it already exists, or creates one.
		if (($oProtoItem = $this->getProtoItem($oProtoInvoice->getId(), $oItem->getId())) == false)
		{
			$oProtoItem = new \F2Dev\InvoiceEngine\Database\Connectors\Entities\Item;
			$oProtoItem->setInvoice($oProtoInvoice);
			$oProtoItem->setItemId($oItem->getId());
		}
		$oProtoItem->setName($oItem->getName());
		$oProtoItem->setDescription($oItem->getDescription());
		$oProtoItem->setQuantity($oItem->getQuantity());
		$oProtoItem->setUnitCost($oItem->getUnitCost());
		$oProtoItem->setDiscount($oItem->getDiscount());
		
		return $oProtoItem;
	}
	
	private function newItem($oProtoItem)
	{
		$oNewItem = new \F2Dev\InvoiceEngine\Invoice\Item\Item($oProtoItem->getItemId());
		$oNewItem->setName($oProtoItem->getName());
		$oNewItem->setDescription($oProtoItem->getDescription());
		$oNewItem->setQuantity($oProtoItem->getQuantity());
		$oNewItem->setUnitCost($oProtoItem->getUnitCost());
		$oNewItem->setDiscount($oProtoItem->getDiscount());
		
		return $oNewItem;
	}
	
	private function getProtoInfo($iInvoiceId, $key)
	{
		return $this->em->getRepository('F2Dev\InvoiceEngine\Database\Connectors\Entities\InvoiceInfo')->findOneBy(array('key' => $key,
																														 'invoice' => $iInvoiceId));
	}
	private function newProtoInfo($oProtoInvoice, $key, $value)
	{
		//Get Info Entity if it already exists, or creates one.
		if (($oProtoInfo = $this->getProtoInfo($oProtoInvoice->getId(), $key)) == false)
		{
			$oProtoInfo = new \F2Dev\InvoiceEngine\Database\Connectors\Entities\InvoiceInfo;
			$oProtoInfo->setKey($key);
			$oProtoInfo->setInvoice($oProtoInvoice);
		}
		$oProtoInfo->setValue($value);
		return $oProtoInfo;
	}
	
	public function getInvoice($sInvoiceID)
	{
		if ($oProtoInvoice = $this->getProtoInvoice($sInvoiceID))
		{
			$oNewInvoice = new \F2Dev\InvoiceEngine\Invoice\Invoice($sInvoiceID);
			$oNewInvoice->setHeader($oProtoInvoice->getHeader());
			$oNewInvoice->setFooter($oProtoInvoice->getFooter());
			$oNewInvoice->setCustomerInfo($oProtoInvoice->getCustomerInfo());
			$oNewInvoice->setCustomerId($oProtoInvoice->getCustomerId());
			$oNewInvoice->setPostDate($oProtoInvoice->getPostDate());
			$oNewInvoice->setPaymentTerm($oProtoInvoice->getPaymentTerm());
			$oNewInvoice->setDueDate($oProtoInvoice->getDueDate());
			$oNewInvoice->setDescription($oProtoInvoice->getDescription());
			$oNewInvoice->setMessage($oProtoInvoice->getMessage());
			$oNewInvoice->setSignature($oProtoInvoice->getSignature());
			
			foreach ($oProtoInvoice->getAdditionalInfo() as $oInfo)
			{
				$oNewInvoice->addInfo($oInfo->getKey(), $oInfo->getValue());
			}
			
			$oNewInvoice->setTaxRate($oProtoInvoice->getTaxRate());
			
			$sCalculatorClass = $oProtoInvoice->getCalculator();
			$oNewCalculator = new $sCalculatorClass();
			
			foreach ($oProtoInvoice->getItems() as $oProtoItem)
			{
				$oNewInvoice->addItem($this->newItem($oProtoItem));
			}
			
			return $oNewInvoice;
		}
		return false;
	}
	
	public function persistInvoice($oInvoice)
	{
		//Get Invoice Entity if it already exists, or creates one.
		if (($oProtoInvoice = $this->getProtoInvoice($oInvoice->getID())) == false)
		{
			$oProtoInvoice = new \F2Dev\InvoiceEngine\Database\Connectors\Entities\Invoice;
		}
		
		$oProtoInvoice->setInvoiceId($oInvoice->getId());
		$oProtoInvoice->setHeader($oInvoice->getHeader());
		$oProtoInvoice->setFooter($oInvoice->getFooter());
		$oProtoInvoice->setCustomerInfo($oInvoice->getCustomerInfo());
		$oProtoInvoice->setCustomerId($oInvoice->getCustomerId());
		$oProtoInvoice->setPostDate($oInvoice->getPostDate());
		$oProtoInvoice->setPaymentTerm($oInvoice->getPaymentTerm());
		$oProtoInvoice->setDueDate($oInvoice->getDueDate());
		$oProtoInvoice->setDescription($oInvoice->getDescription());
		$oProtoInvoice->setMessage($oInvoice->getMessage());
		$oProtoInvoice->setSignature($oInvoice->getSignature());
		
		foreach ($oInvoice->getAllInfo() as $key => $value)
		{
			$oNewProtoInfo = $this->newProtoInfo($oProtoInvoice, $key, $value);
			$this->em->persist($oNewProtoInfo);
			$oProtoInvoice->addAdditionalInfo($oNewProtoInfo);
		}
		
		$oProtoInvoice->setTaxRate($oInvoice->getTaxRate());
		$oProtoInvoice->setCalculator(get_class($oInvoice->getCalculator()));
		
		foreach ($oInvoice->getItems() as $oItem)
		{
			$oNewProtoItem = $this->newProtoItem($oProtoInvoice, $oItem);
			$this->em->persist($oNewProtoItem);
			$oProtoInvoice->addItem($oNewProtoItem);
		}
		$this->em->persist($oProtoInvoice);
		
		$this->em->flush();
		
	}
	
	public function findInvoiceBy($aSearchArgs)
	{
		$results = $this->em->getRepository("\F2Dev\InvoiceEngine\Database\Connectors\Entities\Invoice")->findBy($aSearchArgs);
		$invoices = array();
		
		foreach ( $results as $result )
		{
			$invoices[] = $this->getInvoice($result->getInvoiceId());
		}
		
		return $invoices;
	}
	
	public function persistAudit($oAudit)
	{
		
	}
	
	public function getAudit($sAuditID)
	{
		
	}
	
	public function findAuditBy($aSearchArgs)
	{
		
	}
}