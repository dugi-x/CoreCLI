<?php 
/**
 * CoreCLI - sample example
 * @example %command% [--name=alice]
 */
return function ($options){
    
    $name = (!empty($options['name'])) ? $options['name'] : 'Guest';
	echo "Hello {$name}\n";
};