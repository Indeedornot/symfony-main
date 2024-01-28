<?php

// This script is used to benchmark the application.
// It sends 1000 requests to the application and measures the average response time and used resources.

require_once __DIR__ . '/helpers.php';

const APP_ROUTE = "/welcome";

// Check if Docker Swarm is running
if (strpos(shell_exec("docker info"), "Swarm: active") === false) {
    throw new Exception(format_error("
        Docker Swarm is not running.
        \nPlease start the Swarm cluster before deploying the application.
    "));
}

// Check if the application is deployed
if (strpos(shell_exec("docker stack ls"), "my-stack") === false) {
    throw new Exception(format_error("
        The application is not deployed.
        \nPlease deploy the application before benchmarking it.
    "));
}

// Get the IP address and port of the Docker Swarm manager
$output = shell_exec("docker info");
preg_match("/Manager Addresses:\s*(.+)$/m", $output, $managerAddresses);
$managerAddress = $managerAddresses[1]; //address:port

// Send 1000 async requests to the application
log_state("Sending 1000 async requests to the application...");
$startTime = microtime(true);
$requests = [];
for ($i = 0; $i < 1000; $i++) {
    $requests[] = curl_init("http://$managerAddress" . APP_ROUTE);
}
$multiHandle = curl_multi_init();
foreach ($requests as $request) {
    curl_multi_add_handle($multiHandle, $request);
}
$running = null;
do {
    curl_multi_exec($multiHandle, $running);
} while ($running);
foreach ($requests as $request) {
    curl_multi_remove_handle($multiHandle, $request);
}
curl_multi_close($multiHandle);
log_state("The requests have been sent.");

// Measure the average response time
$endTime = microtime(true);
$averageResponseTime = ($endTime - $startTime) / 1000;
log_state("The average response time is $averageResponseTime seconds.");

// Measure the used resources
log_state("Measuring the used resources...");
$containerId = shell_exec("docker ps -qf name=my-stack_app.1");
$cpuUsage = shell_exec("docker stats --no-stream $containerId --format '{{.CPUPerc}}'");
$memoryUsage = shell_exec("docker stats --no-stream $containerId --format '{{.MemUsage}}'");
log_state("The used CPU is $cpuUsage.");
log_state("The used memory is $memoryUsage.");

log_state("
    \nThe application has been benchmarked.
    \nYou can access it at http://$managerAddress.
");
