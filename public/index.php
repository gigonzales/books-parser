<?php

require_once ('../config/router.php');
require_once('../Command.php');

router('GET', '^/$', function() {
    include("views/index.php");
});

header("HTTP/1.0 404 Not Found");
echo '404 Not Found';
