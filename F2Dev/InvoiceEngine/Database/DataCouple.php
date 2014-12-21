<?php

/**
 * DataCouple
 * $Id: DataCouple.php 19 2012-12-27 11:43:04Z wgraber $
 *
 * Provides an Abstraction layer for pluggable Database Connector Components.
 * Should be implemented in an ORM-Agnostic manner.
 *
 * Data connectors should Extend this class.
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

namespace F2Dev\InvoiceEngine\Database;

interface DataCouple
{
	public function __construct($aParams = array());
	
	public function getInvoice($sInvoiceID);
	
	public function persistInvoice($oInvoice);

	public function findInvoiceBy($aSearchArgs);
	
	public function persistAudit($oAudit);
	
	public function getAudit($sAuditID);
	
	public function findAuditBy($aSearchArgs);
}