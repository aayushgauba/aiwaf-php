<?php
/**
 * honeypot_field.php
 * Public snippet to render the hidden honeypot input field.
 */

require_once __DIR__ . '/../src/HoneypotChecker.php';
// Echo the hidden honeypot field
echo HoneypotChecker::renderField();