<?php
// run-docker-dev.php
putenv('APP_ENV=dev');
putenv('APP_DEBUG=1');
putenv('SERVER_NAME=localhost');
shell_exec('docker compose -f compose.yaml -f compose.dev.yaml -p symfony_dev up -d');
