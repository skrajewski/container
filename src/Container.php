<?php

class Container {

    /**
     * Array with bindings
     *
     * @var array
     */
    protected $mapped = [];

    /**
     * Resolving class
     *
     * @param $class
     * @return object
     * @throws Exception
     */
    public function make($class)
    {
        /*
         * Check if class is binding into container
         */
        if($this->bindingExists($class))
        {
            /*
             * Check if binding abstract is an instance of Closure
             * and execute them if yes
             */
            if($this->mapped[$class] instanceof Closure)
            {
                return call_user_func($this->mapped[$class]);
            }

            /*
             * Otherwise we expect string in array so
             * we need to replace abstract by concrete
             */
            elseif($this->mapped[$class])
            {
                $class = $this->mapped[$class];
            }
        }

        $reflector = new ReflectionClass($class);

        if( ! $reflector->isInstantiable())
        {
            throw new Exception("{$reflector->name} is not instantiable.");
        }


        if($reflector->hasMethod('__construct'))
        {
            /*
             * Get constructor parameters
             */
            $parameters = $reflector->getConstructor()->getParameters();

            /*
             * In many cases classes has dependencies, so we need
             * to resolve them into the container.
             */
            $arguments = $this->resolveParameters($parameters, $reflector);

            /*
             * Create new instance of class with resolving arguments
             */
            return $reflector->newInstanceArgs($arguments);
        }

        /*
         * Otherwise simply create new instance without parameters
         */
        return $reflector->newInstanceWithoutConstructor();
    }

    /**
     * Bind abstract or interface to concrete
     * via string or Clojure
     *
     * @param $abstract
     * @param $concrete
     * @return bool
     */
    public function bind($abstract, $concrete)
    {
        if($concrete instanceof Closure || is_string($concrete))
        {
            $this->mapped[$abstract] = $concrete;
            return true;
        }

        throw new InvalidArgumentException('Concrete must be string or Clojure.');
    }

    /**
     * Resolving constructor parameters
     *
     * @param $parameters
     * @param $reflector
     * @return array
     * @throws Exception
     */
    private function resolveParameters($parameters, $reflector)
    {
        $arguments = [];

        foreach ($parameters as $param)
        {
            if ($param->isDefaultValueAvailable())
            {
                $arguments[] = $param->getDefaultValue();
            }
            elseif ($param->getClass())
            {
                $arguments[] = $this->make($param->getClass()->name);
            }
            else
            {
                throw new Exception("Cannot resolve " . $param->name . " parameter of class " . $reflector->name . ".");
            }
        }

        return $arguments;
    }

    /**
     * Check if abstract is binding to concrete
     * into container
     *
     * @param $class
     * @return bool
     */
    private function bindingExists($class)
    {
        return array_key_exists($class, $this->mapped);
    }

}
