<?php namespace spec;

class Foo {}

class ExtendsFoo {
    public function __construct() {}
}

class Bar {
    public function __construct(Foo $foo) {}
}

class Baz {
    public function __construct(Foo $foo, Bar $bar, $optional = null) {}
}

class Fizz {
    public function __construct(Foo $foo, $unknown) {}
}

abstract class Buzz {
    public function __construct(Foo $foo, Baz $baz) {}
}

interface FizzBuzzInterface {}

class FizzBuzzConcrete implements FizzBuzzInterface {}