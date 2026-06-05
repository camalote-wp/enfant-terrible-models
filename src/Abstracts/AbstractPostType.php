<?php 

namespace CamaloteWP\ZorzalModels\Abstracts;

use CamaloteWP\ZorzalModels\Interfaces\Registerable;

abstract class AbstractPostType implements Registerable {
    protected string $model_name;

	/**
	 * Initializes the AbstractPostType class and sets its $model_name property.
	 *
	 * @param string $model_name The name of the model to register the post type for.
	 *
	 * @since 1.0.0
	 * @access public
	 */
    public function __construct( string $model_name ) {
        $this->model_name = $model_name;
    }

	/**
	 * Returns an array of arguments to register a post type.
	 * 
	 * @since 1.0.0
	 * @access protected
	 * @return array An array of arguments to register a post type.
	 * @see https://developer.wordpress.org/reference/functions/register_post_type/
	 */
    abstract protected function args(): array;

    /**
     * Registers the post type.
     *
     * Uses the args() method to retrieve the arguments to register the post type.
     *
     * @since 1.0.0
     */
    public function register(): void {
        register_post_type( $this->model_name, $this->args() );
    }
}