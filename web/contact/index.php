<?php

use App\Controllers\Pages\Contact;

require_once "../../autoload_web.php";

echo (new Contact('Contact', ['/css/style-contact.css']))->index();