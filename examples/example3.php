<?php 
/**
 * php example3.php get users @format=json
 */

require_once(__DIR__ . '/../CoreCLI.php');


function get_option($opt_name,$def=null){

	return CoreCLI::$options[$opt_name] ?? $def;
}

function get_system_tag($tag_name,$def=null){

	return CoreCLI::$system[$tag_name] ?? $def;
}

function print_on_screen($data){
	
	$format = self::get_system_tag('@format');
	
	if ($format == 'json'){
		
		echo json_encode($data);
		
	}else{
		
		echo var_export($data,true);
		
	}
	
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
	CoreCLI::add_command( 'rand', 'some_rand_num' );
	CoreCLI::add_command( 'get users', function($options){
		
		$json = file_get_contents('https://jsonplaceholder.typicode.com/users');
		$data = json_decode($json,true);
		
		return print_on_screen($data);
		
	} );
	
	
	CoreCLI::run( $cmd );
}


init($argv);



