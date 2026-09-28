<?php

require_once __DIR__ . "/../config/security.php";

apply_security_headers();

require_admin();