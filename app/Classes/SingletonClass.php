<?php

namespace App\Classes;

class SingletonClass
{
    /**
     * single instance is stored in a static field. This field is an array, because we will allow our singleton to have subclasses. Each item in this array will be an instance of a specific singleton's subclass.
     */
    private static $instances = [];

    /**
     * the singleton constructor should always be private to prevent direct construction calls with the 'new' operator.
     */
    protected function __construct()
    {
    }

    /**
     * singleton should not be cloneable
     */
    protected function __clone()
    {
    }

    /**
     * singleton should not be restorable from string
     */
    public function __wakeup()
    {
        throw new \Exception('cannot unserialize a singleton.');
    }

    /**
     * this is the static method that controls the access to the singleton instance. on the first run, it creates a singleton object and places it into the static field. On subsequent runs, it returns the client existing object stored in the static field
     * 
     * this implementation lets you subclass the singleton class while keeping just one instance of each subclass around.
     */
    public static function getInstance(): SingletonClass
    {
        $cls = static::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static();
        }

        return self::$instances[$cls];
    }

    public function greet()
    {
        return 'Hello dear';
    }
}
