<?php
/**
 * Demo Post Type
 *
 * @package CamaloteWP\EditorialControl\Admin
 */

declare(strict_types = 1);

namespace CamaloteWP\EditorialControl\Admin;

use CamaloteWP\EditorialControl\Vendor\TenupFramework\ModuleInterface;
use CamaloteWP\EditorialControl\Vendor\TenupFramework\Module;
use CamaloteWP\EditorialControl\Vendor\Monolog\Logger;

use CamaloteWP\EditorialControl\Inc\LoggerFactory;

use WP_REST_Request;

/**
 * Admin settings.
 */
class AdminSettings implements ModuleInterface {
    use Module;

    private const OPTION_GROUP = CAMALOTE_WP_VENDOR;
    private const OPTION_NAME = CAMALOTE_WP_EDITORIAL_CONTROL_OPTION;
    
    private const REST_BASE = CAMALOTE_WP_EDITORIAL_CONTROL_REST_NAMESPACE;
    private const REST_ROUTE = '/settings';

    /**
	 * Can the class be registered?
	 *
	 * @return bool
	 */
    public function can_register() {
        return true;
    }

	/**
	 * Register the post type.
	 *
	 * @return void
	 */
    public function register() {
        $this->register_hooks();
    }

    public function register_hooks() {
        add_action('admin_init', [$this, 'register_settings']);
        add_action('rest_api_init', [$this, 'register_rest_routes']);
    }

    /**
     * Register the single option in wp_options
     */
    public function register_settings() {
        register_setting(self::OPTION_GROUP, self::OPTION_NAME, [
            'type'              => 'object',
            'sanitize_callback' => [$this, 'sanitize_settings'],
            'default' => [
                'cover' => [
                    'articles' => [
                        'article_primary' => [],
                        'article_secondary' => [],
                        'article_tertiary' => [],
                    ],
                    'audiovisual' => [
                        'title' => '',
                        'desc' => '',
                        'url' => '',
                    ],
                ],
            ],
        ]);
    }

    /**
     * Sanitize the settings before saving
     */
    public function sanitize_settings($input) {
        $output = [];

        if (isset($input['cover'])) {
            $p = $input['cover'];

            $output['cover']['articles'] = [
                'article_primary'   => $p['articles']['article_primary'] ?? [],
                'article_secondary' => $p['articles']['article_secondary'] ?? [],
                'article_tertiary'  => $p['articles']['article_tertiary'] ?? [],
            ];

            $output['cover']['audiovisual'] = [
                'title' => $p['audiovisual']['title'] ?? '',
                'desc'   => $p['audiovisual']['desc'] ?? '',
                'url'    => $p['audiovisual']['url'] ?? [],
            ];
        }

        return $output;
    }

    /**
     * Register custom REST route
     */
    public function register_rest_routes() {
        $settings_schema = [
            'type' => 'object',
            'properties' => [
                'cover' => [
                    'type' => 'object',
                    'properties' => [
                        'articles' => [
                            'type' => 'object',
                            'properties' => [
                                'article_primary' => ['type' => 'array'],
                                'article_secondary' => ['type' => 'array'],
                                'article_tertiary' => ['type' => 'array'],
                            ],
                        ],
                        'audiovisual' => [
                            'type' => 'object',
                            'properties' => [
                                'title' => ['type' => 'string'],
                                'desc' => ['type' => 'string'],
                                'url' => ['type' => 'string'],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        register_rest_route(self::REST_BASE, self::REST_ROUTE, [
            [
                'methods' => 'GET',
                'callback' => [$this, 'get_settings'],
                'permission_callback' => fn() => current_user_can('manage_options'),
            ],
            [
                'methods' => 'POST',
                'callback' => [$this, 'update_settings'],
                'permission_callback' => fn() => current_user_can('manage_options'),
                'args' => $settings_schema,
            ],
            [
                'methods' => 'PATCH',
                'callback' => [$this, 'patch_settings'],
                'permission_callback' => fn() => current_user_can('manage_options'),
                'args' => $settings_schema,
            ],
        ]);
    }

    /**
     * GET: Return all settings
     */
    public function get_settings() {
        return get_option(self::OPTION_NAME);
    }

    /**
     * POST: Update settings
     */
    public function update_settings(WP_REST_Request $request) {
        $params = $request->get_json_params();
        $sanitized = $this->sanitize_settings($params);
        
        update_option(self::OPTION_NAME, $sanitized);
        
        return $sanitized;
    }
    
    /**
     * PATCH: Update settings with proper JSON Merge Patch semantics (RFC 7396)
     */
    public function patch_settings(WP_REST_Request $request) {
        $current = get_option(self::OPTION_NAME);
        $updates = $request->get_json_params();

        $merged = $this->json_merge_patch($current, $updates);
        $sanitized = $this->sanitize_settings($merged);

        update_option(self::OPTION_NAME, $sanitized);

        return $sanitized;
    }

    /**
     * Implement JSON Merge Patch (RFC 7396) semantics
     * 
     * @param array $target The current data
     * @param array $patch The patch to apply
     * @return array The merged result
     */
    private function json_merge_patch($target, $patch) {
        if (!is_array($patch)) {
            return $patch;
        }

        if (!is_array($target)) {
            $target = [];
        }

        foreach ($patch as $key => $value) {
            if ($value === null) {
                // null means remove the key (RFC 7396 spec)
                unset($target[$key]);
            } elseif (is_array($value) && !$this->is_indexed_array($value) && isset($target[$key]) && is_array($target[$key]) && !$this->is_indexed_array($target[$key])) {
                // Both are associative arrays (objects) - merge recursively
                $target[$key] = $this->json_merge_patch($target[$key], $value);
            } else {
                // All other cases: replace completely
                // This includes: primitives, indexed arrays (including empty []), null-to-value, type changes
                $target[$key] = $value;
            }
        }

        return $target;
    }

    /**
     * Check if array is indexed (list) vs associative (object)
     * Important for JSON Merge Patch semantics
     */
    private function is_indexed_array($array) {
        if (!is_array($array) || empty($array)) {
            return true; // Empty arrays are treated as indexed
        }
        
        return array_keys($array) === range(0, count($array) - 1);
    }

}
