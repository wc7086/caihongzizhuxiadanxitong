<?php
 namespace lib\mail\PHPMailer; class Exception extends \Exception { public function errorMessage() { return '<strong>' . htmlspecialchars($this->getMessage()) . "</strong><br />\n"; } } 