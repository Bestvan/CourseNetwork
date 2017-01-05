<?php

use PDO;

// Allow execute this file only from command line
// Example: > php installation.php
if (php_sapi_name() === 'cli') {
    $installer = new Install;
    $installer->run();
}

/**
 * This class running installation exploded with steps.
 * To add new installation step - create public not static method
 * and add annotation @step to this method. Remember: order is strict.
 *
 * @see https://github.com/krushelnytskyi/CourseNetwork to more information
 * about installation. This class is not secure.
 *
 * Class Install
 */
class Install
{

    /**
     * General data defines
     */
    const MIN_PHP_VERSION = 7.0;

    /**
     * @step
     * Check software requirements
     *
     * @return void
     */
    public function checkSystem()
    {
        echo 'PHP Version: ' . phpversion() . PHP_EOL;

        $this->abortIf(static::MIN_PHP_VERSION > phpversion(), 'Minimum PHP Version: ' . static::MIN_PHP_VERSION);
    }

    /**
     * @step
     */
    public function installDatabase()
    {
        // TODO: create database installation
        // TODO: from file config/database/version_1.sql
        // google doc
    }

    /**
     * @param string|bool $message Abort message
     * @return void
     */
    private function abort($message = false)
    {
        echo 'Aborting installation. ';

        if (false === $message) {
            echo 'Message: ' . $message;
        }

        echo PHP_EOL;
        exit(0);
    }

    /**
     * @param bool        $condition Condition to control aborting
     * @param string|bool $message   Abort Message
     *
     * @return void
     */
    private function abortIf($condition, $message = false)
    {
        if (true === $condition) {
            $this->abort($message);
        }
    }

    /**
     * Run Installation
     * @return void
     */
    public function run()
    {
        $methods = (new ReflectionClass(static::class))
            ->getMethods(ReflectionMethod::IS_PUBLIC);

        echo 'Starting installing process' . PHP_EOL;

        foreach ($methods as $method) {
            if (1 === preg_match('/[\s\t]+@step[\s\t]+/', $method->getDocComment())) {
                echo 'Installing step: ' . $method->getName() . PHP_EOL;
                $this->{$method->getName()}();
            }
        }

        echo 'Installation finished successfully' . PHP_EOL;
    }

}
