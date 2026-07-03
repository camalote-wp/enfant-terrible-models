<?php

namespace EnfantTerrible\Models\Abstracts;

use EnfantTerrible\Models\Interfaces\Registerable;

abstract class AbstractMeta implements Registerable {
    protected string $model_name;


	/**
	 * Initializes the class and sets its $model_name property.
	 *
	 * @param string $model_name The name of the model to register the post meta keys for.
	 *
	 * @since 1.0.0
	 */    
	public function __construct( string $model_name ) {
        $this->model_name = $model_name;
    }

    abstract protected function schema(): array;

    /**
     * Registers the post meta keys for the model.
     *
     * Loops through the schema provided by the child class and registers each key with the provided arguments.
     *
     * @see https://developer.wordpress.org/reference/functions/register_post_meta/
     * @since 1.0.0
     */
    public function register(): void {
        foreach ( $this->schema() as $key => $args ) {
            register_post_meta( $this->model_name, $key, $args );
        }
    }
}