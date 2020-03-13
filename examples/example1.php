<?php 
/**
	php example1.php hello world --name=alice
 */

require_once(__DIR__ . '/../CoreCLI.php');

$args = $argv;

$enterypoint = __DIR__ . '/' . basename(array_shift($args));
$cmd         = implode(' ',$args);

//Add commands
//CoreCLI::add_command( $command_name, $some_callable );
CoreCLI::add_command( 'hello world', require(__DIR__ . '/commands/hello-world.php') );

//Run/execute
CoreCLI::run( $cmd );



