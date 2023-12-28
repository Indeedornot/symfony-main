<?php
// run-docker-dev.php
putenv('APP_ENV=prod');
putenv('APP_DEBUG=0');
putenv('SERVER_NAME=localhost');
shell_exec('docker compose -f compose.yaml -f compose.prod.yaml -p symfony_dev up -d');
