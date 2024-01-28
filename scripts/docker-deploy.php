<?php
require_once __DIR__ . '/helpers.php';

// Check if Docker Swarm is running
if (strpos(shell_exec("docker info"), "Swarm: active") === false) {
    throw new Exception(format_error("
        Docker Swarm is not running.
        \nPlease start the Swarm cluster before deploying the application.
    "));
}

log_state("Building the Docker image...");
exec("docker-compose -f compose.yaml -f compose.prod.yaml build");
log_state("The Docker image has been built.");

log_state("Pushing the Docker image to the local registry...");
exec("docker-compose -f compose.yaml -f compose.prod.yaml push");
log_state("The Docker image has been pushed to the local registry.");

log_state("Pushing the Docker image to Docker Hub...");
exec("docker-compose push");
log_state("The Docker image has been pushed to Docker Hub.");

log_state("Deploying the application to Docker Swarm...");
$stackName = "my-stack";

$outputs = [];
$code;
exec("docker stack deploy -c ./stacks/compose.php.yaml $stackName", $outputs, $code);
if ($code !== 0) {
    throw new Exception(format_error("
        The application could not be deployed.
        \nPlease check the error message below:
        \n" . implode("\n", $outputs) . "
    "));
}

// Get the IP address and port of the Docker Swarm manager
$output = shell_exec("docker info");
preg_match("/Manager Addresses:\s*(.+)$/m", $output, $managerAddresses);
$managerAddress = $managerAddresses[1]; //address:port

log_state("
\nThe application has been deployed to Docker Swarm as stack '$stackName'.
\nYou can access it at http://$managerAddress.
");
