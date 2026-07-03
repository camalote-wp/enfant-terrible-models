<?php

namespace EnfantTerrible\Models\Interfaces;

interface Hookable {
    public function get_hooks(): array;
}