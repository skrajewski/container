<?php

namespace spec;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

require "TestClasses.php";

class ContainerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Container');
    }

    function it_should_return_instance_of_foo()
    {
        $this->make('spec\Foo')->shouldReturnAnInstanceOf('spec\Foo');
    }

    function it_should_return_instance_of_bar()
    {
        $this->make('spec\Bar')->shouldReturnAnInstanceOf('spec\Bar');
    }

    function it_should_return_instance_of_baz()
    {
        $this->make('spec\Baz')->shouldReturnAnInstanceOf('spec\Baz');
    }

    function it_should_throw_an_exception_during_resolving()
    {
        $this->shouldThrow('Exception')->duringMake('spec\Fizz');
    }

    function it_should_throw_an_exception_during_try_make_abstract_class()
    {
        $this->shouldThrow('Exception')->duringMake('spec\Buzz');
    }

    function it_should_throw_an_exception_during_try_make_interface()
    {
        $this->shouldThrow('Exception')->duringMake('spec\FizzBuzz');
    }

    function it_should_return_concrete_when_make_interface()
    {
        $this->bind('spec\FizzBuzzInterface', 'spec\FizzBuzzConcrete');

        $this->make('spec\FizzBuzzInterface')->shouldReturnAnInstanceOf('spec\FizzBuzzConcrete');
    }

    function it_should_call_closure_when_make_abstract()
    {
        $this->bind('spec\FizzBuzzInterface', function() {
            return new FizzBuzzConcrete();
        });

        $this->make('spec\FizzBuzzInterface')->shouldReturnAnInstanceOf('spec\FizzBuzzConcrete');
    }
}
