<?php

namespace F2Dev\InvoiceEngine\Utility\Exceptions;

use F2Dev\InvoiceEngine\Utility\Exceptions;

class InvalidEventException extends \InvalidArgumentException implements Exceptions\EventExceptionInterface{};