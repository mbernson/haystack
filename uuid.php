<?php
require 'vendor/autoload.php';

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

try {

    // Generate a version 4 (random) UUID object
    $uuid4 = Uuid::uuid4();
    echo $uuid4->toString() . "\n"; // i.e. 25769c6c-d34d-4bfe-ba98-e0ee856f3e7a


} catch (UnsatisfiedDependencyException $e) {

    // Some dependency was not met. Either the method cannot be called on a
    // 32-bit system, or it can, but it relies on Moontoast\Math to be present.
    echo 'Caught exception: ' . $e->getMessage() . "\n";

}
