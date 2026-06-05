<?php

namespace CamaloteWP\ZorzalModels\Definitions\Page;

use CamaloteWP\ZorzalModels\Abstracts\AbstractBlocks;

final class Blocks extends AbstractBlocks {

	/**
	 * Return hooks to register.
	 *
	 * @return array
	 */
	public function get_hooks(): array {
		return [
			[
				// 'type' => 'filter',
				// 'hook' => 'allowed_block_types_all',
				// 'callback' => 'restrict',
				// 'priority' => 10,
				// 'accepted_args' => 2,
			],
		];
	}

	/**
	 * Restrict available blocks for this post type.
	 *
	 * @param array|bool $allowed Allowed block types.
	 * @param object     $context Editor context.
	 *
	 * @return array|bool
	 */
	public function restrict( $allowed, $context ): array|bool {
		if ( $context->post?->post_type === $this->model_name ) {
			return [
				'plugin/example-block',
			];
		}

		return $allowed;
	}
}