<?php

namespace WPGraphQLGutenberg\Blocks;

use stdClass;

require_once ABSPATH . 'wp-admin/includes/admin.php';

class Utils
{
	public static function array_to_object($arr){
		if (!$arr) {
			return new stdClass();
		}

		if (!is_array($arr) || !is_string(array_key_first($arr))) {
			return $arr;
		}

		return (object) array_map( __FUNCTION__, $arr);
	}

	public static function visit_blocks($blocks, $callback)
	{
		return array_map(function ($block) use ($callback) {
			$inner_blocks = self::visit_blocks(
				$block['innerBlocks'],
				$callback
			);

			$visited_block = $callback($block);
			$visited_block['innerBlocks'] = $inner_blocks;

			return $visited_block;
		}, $blocks);
	}

	public static function get_editor_post_types()
	{
		return apply_filters(
			'graphql_gutenberg_editor_post_types',
			array_filter(get_post_types_by_support('editor'), function (
				$post_type
			) {
				return use_block_editor_for_post_type($post_type);
			})
		);
	}
}
