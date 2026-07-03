<?php

namespace EnfantTerrible\Models\Definitions\ArticulosInvitados;

use EnfantTerrible\Models\Vendor\CamaloteWP\Models\Abstracts\AbstractRest;

class Rest extends AbstractRest
{
    protected string $model_name = 'articulos-invitados';

    public function get_hooks(): array
    {
        return [
            [
                'type' => 'filter',
                'hook' => 'rest_' . $this->model_name . '_query',
                'callback' => [ $this, 'hide_from_rest' ],
                'priority' => 10,
                'accepted_args' => 2,
            ],
        ];
    }

    public function hide_from_rest( $args, $request )
    {
        if ( ! is_user_logged_in() ) {
            $args['post__in'] = [ 0 ]; // forces empty result for anonymous requests
        }
        return $args;
    }
}