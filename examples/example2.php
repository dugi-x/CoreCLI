<?php 
/**
 * php example2.php rand --min=1000 --max=9000 --mark
 */

require_once(__DIR__ . '/../CoreCLI.php');


function get_option($opt_name,$def=null){

	return CoreCLI::$options[$opt_name] ?? $def;
}


/**
 * CoreCLI - sample example2
 * @example %command% [--min=1000] [--max=9000] [--mark]
 */
function my_command_rand($options){
	
	$min = get_option('min',10);
	$max = get_option('max',99);
	
	$rand = rand($min,$max);
	
	if ( get_option('mark') === true ){
		
		echo "*** {$rand} ***";
	
	}else{
		
		echo $rand;
		
	}
	
}


function init($args){
	
	$enterypoint = __DIR__ . '/' . basename(array_shift($args));
	$cmd         = implode(' ',$args);
	
	CoreCLI::add_command( 'hello world', require(__DIR__ . '/commands/hello-world.php') );
	CoreCLI::add_command( 'rand', 'my_command_rand' );
	
	
	CoreCLI::run( $cmd );
}


init($argv);



