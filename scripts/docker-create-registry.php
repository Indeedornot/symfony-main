<?php
require_once __DIR__ . '/helpers.php';
//resolve __DIR__ . '/../.env' to get the path to the .env file

$env = file_get_contents(__DIR__ . "/../.env");
$lines = explode("\n", $env);

$env = [];
foreach ($lines as $line) {
    preg_match("/([^#]+)\=(.*)/", $line, $matches);
    if (isset($matches[2])) {
        $name = trim($matches[1]);
        $value = trim($matches[2]);
        $env[$name] = $value;
    }
}

log_state("Env path: $path" . "\n" . "Env: " . print_r($env, true));

if (!$env) {
    throw new Exception(format_error("Unable to locate .env file."));
}

if (!$env) {
    throw new Exception(format_error("Unable to locate .env file."));
}

$registry = $env['IMAGES_PREFIX'];
$response = @file_get_contents("$registry/v2/_catalog", false, stream_context_create(['http' => [
    'method' => 'GET',
    'header' => implode("\r\n", [
        "Accept-language: en",
        "Cookie: foo=bar"
    ])
]]));

if ($response !== false) {
    log_state("Registry is up.", Color::blue);
    return;
}

log_state("Registry is down.");

// check if registry container exists - might be stopped
$containerId = shell_exec("docker ps -aqf name=registry");
if ($containerId) {
    log_state("Registry container exists.", Color::blue);
    log_state("Removing registry container...");
    exec("docker rm -f $containerId");
    log_state("Registry container removed.");
}

log_state("Creating registry...");
exec("docker run -d -p 5000:5000 --restart=always --name registry registry:2.7");

log_state("Registry created.");
