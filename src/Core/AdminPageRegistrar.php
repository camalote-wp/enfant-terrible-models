<?php
namespace CamaloteWP\ZorzalModels\Core;

class AdminPageRegistrar {

    public static function register_menu_page( array $config ): void {
        add_menu_page(
            $config['page_title'],
            $config['page_title'],
            $config['capability'] ?? 'manage_options',
            $config['menu_slug'],
            $config['render_callback'],
            $config['icon'] ?? '',
            $config['position'] ?? null
        );
    }

    public static function register_submenu_page( array $config ): void {
        add_submenu_page(
            $config['parent_slug'],
            $config['page_title'],
            $config['page_title'],
            $config['capability'] ?? 'manage_options',
            $config['menu_slug'],
            $config['render_callback']
        );
    }

    public static function enqueue_assets( string $screen_id, array $config ): void {
        if ( get_current_screen()->id !== $screen_id ) {
            return;
        }

        $deps_file = $config['asset_dir'] . $config['deps_file'];
        $dependencies = [];
        $version = '1.0.0';

        if ( file_exists( $deps_file ) ) {
            $asset = require $deps_file;
            $dependencies = $asset['dependencies'] ?? [];
            $version = $asset['version'] ?? '1.0.0';
        }

        wp_enqueue_script(
            $config['handle'],
            $config['asset_url'] . $config['script'],
            $dependencies,
            $version,
            true
        );
        wp_enqueue_style(
            $config['handle'],
            $config['asset_url'] . $config['style'],
            [],
            $version
        );
    }
}