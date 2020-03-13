<?php 
/**
 * CoreCLI - small and smart php cli tool
 */
class CoreCLI{

    static $commands_func = []; //List of functions
    static $commands_flat = []; //List of names
    static $commands_map  = []; //Tree map with pointer value

    /* bin <arg1command> arg2 arg3 --oprion1=foo --oprion2=bar --optionbool @systemtag=foo @systembool  */
    static $args    = [];
    static $options = [];
    static $system  = [];

    static function run($cmd=null){

        $argv = preg_split("/[\s]+/", $cmd);
        
        $system = array_filter($argv,function($v){return (substr($v, 0, 1) == '@');});
        foreach ($system as $opt){
            list($key, $val) = explode('=',$opt) + [true,true];
            self::$system[$key] = $val;
        }

        self::$args = array_filter($argv,function($v){return (!empty($v) && !in_array(substr($v, 0, 1),['-','@']));});
        if (empty(self::$args)) 
            self::$args[] = 'help';
        
        $options = array_filter($argv,function($v){return (substr($v, 0, 1) == '-');});
        foreach ($options as $opt){
            $opt = ltrim($opt,'-');
            list($key, $val) = explode('=',$opt) + [true,true];
            self::$options[$key] = $val;
        }
        
        

        $callbacks = self::get_command_by_args(self::$args);
        
        //The idea was to enable multi-call
        foreach ($callbacks as $callback){
            
            call_user_func($callback,self::$options);
        }

    }


       

    static function get_command_by_id($id){

        return self::$commands_func[$id] ?? null;
    }

    static function get_command_by_args($args){

        $items = self::$commands_map;
        
        foreach ($args as $segment) { 
            if ( substr($segment, 0, 1) == '-') 
                break;
            
            if (!is_array($items) || !array_key_exists($segment, $items)) {
                //segment $segment not exists 
                return [];
            }
            
            $items = &$items[$segment];
        }
        
        $items = array_filter($items,'is_int');
        
        return array_map('self::get_command_by_id',$items);
    }
    
    static function get_command($name){
        
        return self::$commands_flat[$name] ?? null;
    }

    
    static function add_command($name,$callback){
        
        $id = count(self::$commands_flat);
        $name = trim(preg_replace('/\s+/', ' ',$name));
        
        self::$commands_flat[$id] = $name;
        self::$commands_func[$id] = $callback;

        $items = &self::$commands_map;

        foreach (preg_split("/[\s]+/", $name) as $k) {
            if (!isset($items[$k]) || !is_array($items[$k])) {
                $items[$k] = [];
            }

            $items = &$items[$k];
        }

        $items[] = $id;
    }

    
}