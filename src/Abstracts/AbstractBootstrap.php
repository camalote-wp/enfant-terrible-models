<?php

namespace CamaloteWP\ZorzalModels\Abstracts;

abstract class AbstractBootstrap {

    protected string $plugin_name;
    protected string $version;
    
    /**
     * The model name. 
     * defined here so the getter works, but left uninitialized 
     * so the child class can set it.
     */
    protected string $model_name;

    final public function __construct( string $plugin_name, string $version ) {
        $this->plugin_name = $plugin_name;
        $this->version     = $version;
    }

    /**
     * Logic abstracted to parent.
     */
    public function get_model_name(): string {
        return $this->model_name;
    }

    /**
     * Child must still provide the list of components.
     */
    abstract public function get_components(): array;

    public function register() {}
}