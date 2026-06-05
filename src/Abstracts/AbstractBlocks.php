<?php

namespace CamaloteWP\ZorzalModels\Abstracts;

use CamaloteWP\ZorzalModels\Interfaces\Registerable;
use CamaloteWP\ZorzalModels\Interfaces\Hookable;

abstract class AbstractBlocks implements Registerable, Hookable {
    protected string $model_name;

    public function __construct( string $model_name ) {
        $this->model_name = $model_name;
    }

	public function register(): void {
        $path = $this->get_automatic_path();
        // Safety check in case the folder doesn't exist
        if ( is_dir( $path ) ) {
            foreach ( glob( $path . '/**/block.json' ) as $file ) {
                register_block_type( dirname( $file ) );
            }
        }
    }

    public function get_hooks(): array {
        return [];
    }

    private function get_automatic_path(): string {
        $dir_path = CAMALOTE_WP_ZORZAL_MODELS_PATH . 'dist/blocks/' . $this->model_name;
        return $dir_path;
    }
}