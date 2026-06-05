<?php
namespace CamaloteWP\ZorzalModels\Abstracts;

use CamaloteWP\ZorzalModels\Interfaces\Hookable;
use CamaloteWP\ZorzalModels\Interfaces\AdminPage;
use CamaloteWP\ZorzalModels\Core\AdminPageRegistrar;

abstract class AbstractModelAdminPage implements Hookable, AdminPage {
    protected string $model_name;

    public function __construct( string $model_name ) {
        $this->model_name = $model_name;
    }

    public function get_hooks(): array {
        return [];
    }

    abstract public function get_page_config(): array;
    abstract public function get_asset_config(): array;
    abstract public function render_page(): void;

    public function register_submenu_page(): void {
        AdminPageRegistrar::register_submenu_page( array_merge(
            $this->get_page_config(),
            [ 'render_callback' => [ $this, 'render_page' ] ]
        ));
    }

    public function register_menu_page(): void {
        AdminPageRegistrar::register_menu_page( array_merge(
            $this->get_page_config(),
            [ 'render_callback' => [ $this, 'render_page' ] ]
        ));
    }

    public function enqueue_assets(): void {
        AdminPageRegistrar::enqueue_assets(
            $this->get_page_config()['screen_id'],
            $this->get_asset_config()
        );
    }
}