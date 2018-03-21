<?php


use Nfq\Academy\Homework\ClassA;
use Nfq\Academy\Homework\Subpackage\ClassB;
use Acme\ClassAcme;

require_once __DIR__.'/bootstrap.php';


$a = new ClassA();
$a->doSomething();

$b = new ClassB();
$b->doSomething();


$acme = new ClassAcme();
$acme->doSomething();
