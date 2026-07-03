<?php

namespace EnfantTerrible\Models\Abstracts;

use EnfantTerrible\Models\Interfaces\Hookable;

abstract class AbstractRest implements Hookable {
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

    public function get_hooks(): array {
        return [];
    }
}