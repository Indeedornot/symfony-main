<?php

// Check if Docker Swarm is running
if (strpos(shell_exec('docker info'), 'Swarm: active') === false) {
    throw new Exception("
    \nDocker Swarm is not running.
    \nPlease start the Swarm cluster before deploying the application.
");
}

// Tag the Docker image with the address of the local registry
exec('docker tag app-php localhost:5000/app-php');

// Push the Docker image to the local registry
exec('docker push localhost:5000/app-php');

// Deploy the stack to Swarm
$stackName = 'my-stack';
exec("docker stack deploy -c compose.yaml -c compose.prod.yaml $stackName");

// Get the IP address and port of the Docker Swarm manager
$output = shell_exec('docker info');
preg_match('/Manager Addresses:\s*(.+)$/m', $output, $managerAddresses);
$managerAddress = $managerAddresses[1]; //address:port

echo "
\nThe application has been deployed to Docker Swarm as stack '$stackName'.
\nYou can access it at http://$managerAddress.
";
