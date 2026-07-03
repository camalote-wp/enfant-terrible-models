<?php

namespace EnfantTerrible\Models\Interfaces;

interface AdminPage {
    public function get_page_config(): array;
    public function get_asset_config(): array;
    public function register_submenu_page(): void;
    public function register_menu_page(): void;
    public function render_page(): void;
}