<?php

namespace EnfantTerrible\Models\Definitions\ArticulosInvitados;

use EnfantTerrible\Models\Vendor\CamaloteWP\Models\Abstracts\AbstractPostType;
use EnfantTerrible\Models\Vendor\CamaloteWP\Models\Interfaces\Hookable;

class PostType extends AbstractPostType implements Hookable {
    protected string $model_name = 'articulos-invitados';
    /**
     * Define the arguments passed to register_post_type().
     *
     * @return array<string, mixed> WordPress post type registration arguments
     */
    protected function args(): array {
        $labels = [
            "name" => esc_html__( "Artículos Invitados", "enfant-terrible-models" ),
            "singular_name" => esc_html__( "Artículo Invitado", "enfant-terrible-models" ),
            "menu_name" => esc_html__( "Artículos Invitados", "enfant-terrible-models" ),
            "all_items" => esc_html__( "Ver todos los Artículos Invitados", "enfant-terrible-models" ),
            "add_new" => esc_html__( "Añadir nuevo", "enfant-terrible-models" ),
            "add_new_item" => esc_html__( "Añadir nuevo Artículo Invitado", "enfant-terrible-models" ),
            "edit_item" => esc_html__( "Editar Artículo Invitado", "enfant-terrible-models" ),
            "new_item" => esc_html__( "Nuevo Artículo Invitado", "enfant-terrible-models" ),
            "view_item" => esc_html__( "Ver Artículo Invitado", "enfant-terrible-models" ),
            "view_items" => esc_html__( "Ver Artículos Invitados", "enfant-terrible-models" ),
            "search_items" => esc_html__( "Buscar Artículo Invitado", "enfant-terrible-models" ),
            "not_found" => esc_html__( "No se han encontrado Artículos Invitados", "enfant-terrible-models" ),
            "not_found_in_trash" => esc_html__( "No se han encontrado Artículos Invitados en la papelera", "enfant-terrible-models" ),
            "parent" => esc_html__( "Artículo Invitado padre:", "enfant-terrible-models" ),
            "featured_image" => esc_html__( "Imagen destacada para este Artículo Invitado", "enfant-terrible-models" ),
            "set_featured_image" => esc_html__( "Establecer imagen destacada para este Artículo Invitado", "enfant-terrible-models" ),
            "remove_featured_image" => esc_html__( "Eliminar imagen destacada para este Artículo Invitado", "enfant-terrible-models" ),
            "use_featured_image" => esc_html__( "Usar como imagen destacada para este Artículo Invitado", "enfant-terrible-models" ),
            "archives" => esc_html__( "Archivos de Artículos Invitados", "enfant-terrible-models" ),
            "insert_into_item" => esc_html__( "Insertar en el Artículo Invitado", "enfant-terrible-models" ),
            "uploaded_to_this_item" => esc_html__( "Subido a este Artículo Invitado", "enfant-terrible-models" ),
            "filter_items_list" => esc_html__( "Filtrar lista de Artículos Invitados", "enfant-terrible-models" ),
            "items_list_navigation" => esc_html__( "Navegación de lista de Artículos Invitados", "enfant-terrible-models" ),
            "items_list" => esc_html__( "Listado de Artículos Invitados", "enfant-terrible-models" ),
            "attributes" => esc_html__( "Atributos de los Artículos Invitados", "enfant-terrible-models" ),
            "name_admin_bar" => esc_html__( "Artículo Invitado", "enfant-terrible-models" ),
            "item_published" => esc_html__( "Artículo Invitado publicado.", "enfant-terrible-models" ),
            "item_published_privately" => esc_html__( "Artículo Invitado publicada como privada.", "enfant-terrible-models" ),
            "item_reverted_to_draft" => esc_html__( "Artículo Invitado devuelvo a borrador.", "enfant-terrible-models" ),
            "item_trashed" => esc_html__( "Artículo Invitado enviado a la papelera", "enfant-terrible-models" ),
            "item_scheduled" => esc_html__( "Artículo Invitado programado.", "enfant-terrible-models" ),
            "item_updated" => esc_html__( "Artículo Invitado actualizado.", "enfant-terrible-models" ),
            "parent_item_colon" => esc_html__( "Artículo Invitado padre:", "enfant-terrible-models" ),
	    ];

        $args = [
            "label" => esc_html__( "Artículos Invitados", "enfant-terrible-models" ),
            "labels" => $labels,
            "description" => "",
            "public" => true,
            "publicly_queryable" => true,
            "show_ui" => true,
            "show_in_rest" => true,
            "has_archive" => false,
            "show_in_menu" => true,
            "show_in_nav_menus" => false,
            "delete_with_user" => false,
            "exclude_from_search" => false,
            "capability_type" => "post",
            "map_meta_cap" => true,
            "hierarchical" => false,
            "can_export" => false,
            "rewrite" => [ "slug" => "articulos-invitados", "with_front" => true ],
            "query_var" => true,
            "supports" => [ "title", "editor", "thumbnail", "excerpt", "author" ],
            "show_in_graphql" => false,
        ];

        return $args;
    }

    /**
     * Define the hooks for the post type.
     *
     * @return array<int, array<string, mixed>> An array of hooks for the post type
     */
    public function get_hooks(): array {
        return [
            [
                'type' => 'action',
                'hook' => 'pre_get_posts',
                'callback' => 'hide_from_rss',
                'priority' => 10,
                'accepted_args' => 1,
            ],
            [
                'type' => 'filter',
                'hook' => 'wp_sitemaps_post_types',
                'callback' => 'hide_from_sitemap',
                'priority' => 10,
                'accepted_args' => 2,
            ],
        ];
    }

    public function hide_from_rss( \WP_Query $query ) {
        if ( $query->is_feed() ) {
            $post_types = $query->get( 'post_type' );
            if ( empty( $post_types ) ) {
                $query->set( 'post_type', get_post_types( [ 'exclude_from_search' => false ] ) );
            }
            // Explicitly strip your CPT out of feed queries
            $current = (array) $query->get( 'post_type' );
            $query->set( 'post_type', array_diff( $current, [ $this->model_name ] ) );
        }
    }

    public function hide_from_sitemap($excluded, $post_type) {
        return $post_type === $this->model_name ? true : $excluded;
    }
}