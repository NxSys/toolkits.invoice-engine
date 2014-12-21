<?php

/**
 * InvoiceEngine
 * $Id: InvoiceTest.php 21 2013-01-03 02:35:30Z wgraber $
 *
 * Test of InvoiceEngine.
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
 * @version $Revision: 21 $
 */

namespace F2Dev\InvoiceTest;

require_once __dir__."/vendors/ClassLoader/UniversalClassLoader.php";
require_once "InvoiceEngine.php";

use F2Dev\InvoiceEngine\InvoiceEngine;

echo "\n~~~~~\n\n";
echo "Initializing InvoiceEngine\n";

$ie = new InvoiceEngine("DoctrineDataConnector", array("createDb" => false,
													   "db" => array('driver' => 'pdo_sqlite',
																	 'user' => 'test',
																	 'password' => 'foobar',
																	 'path' => __dir__ . DIRECTORY_SEPARATOR . "test.db")), true);

																	 
echo "Checking for test Invoice\n";
$testInvoice = $ie->getInvoice("TestInvoice1");
if ($testInvoice != false)
{
	echo "Test invoice loaded!\n";
	echo "\n~~~~~\n\n";
}
else
{
	echo "Not found, creating test Invoice\n";
	$testInvoice = $ie->newInvoice("TestInvoice1");
	$testInvoice->setDescription("This is a test Invoice");
	$testInvoice->setPaymentTerm(30);
	$testInvoice->setTaxRate(0.06);
	
	$testInvoice->newItem("TestItem1",
						"Test Item",
						"This is a test item.",
						3,
						5.5,
						0.);
	
	$testInvoice->newItem("TestItem2",
						"Another Test Item",
						"Foobar",
						6,
						17.99,
						0.15);
	
	
	
	echo "\n~~~~~\n\n";
	echo "Attempting to persist Invoice\n";

	$ie->persistInvoice($testInvoice);
}
echo "\n~~~~~\n\n";
echo $testInvoice->getId() . "\n";
echo $testInvoice->getDescription() . "\n";
echo "Due on: " . $testInvoice->getDueDate() . "\n";
echo "Total: " . $testInvoice->getTotal() . "\n";