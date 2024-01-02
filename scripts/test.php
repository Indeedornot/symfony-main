<?php

// Get the IP address and port of the Docker Swarm manager
$output = shell_exec('docker info');
preg_match('/Manager Addresses:\s*(.+)$/m', shell_exec('docker info'), $managerAddresses);
$managerAddress = $managerAddresses[1]; //address:port

echo "
\nYou can access it at http://$managerAddress.
";
