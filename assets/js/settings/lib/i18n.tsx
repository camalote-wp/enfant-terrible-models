// assets/js/settings/lib/i18n.tsx
import { __ } from '@wordpress/i18n';

// Export commonly used strings as constants for consistency
export const STRINGS = {
    // Page titles
    EDITORIAL_CONTROL: __('Editorial Control', 'camalote-wp-editorial-control'),
    COVER: __('Portada', 'camalote-wp-editorial-control'),
    
    // Article types
    ARTICLE_PRIMARY: __('Artículo Primario', 'camalote-wp-editorial-control'),
    ARTICLE_SECONDARY: __('Artículo Secundario', 'camalote-wp-editorial-control'),
    ARTICLE_TERTIARY: __('Artículo Terciario', 'camalote-wp-editorial-control'),
    COVER_ARTICLES: __('Artículos de tapa', 'camalote-wp-editorial-control'),
    
    // Audiovisual section
    AUDIOVISUAL_SECTION: __('Sección Audiovisual', 'camalote-wp-editorial-control'),
    VIDEO_TITLE: __('Título del video', 'camalote-wp-editorial-control'),
    VIDEO_URL: __('URL del video', 'camalote-wp-editorial-control'),
    VIDEO_DESCRIPTION: __('Descripción del video', 'camalote-wp-editorial-control'),
    NO_AUDIOVISUAL_CONTENT: __('No hay contenido audiovisual.', 'camalote-wp-editorial-control'),
    EMBED_URL_NOT_COMPATIBLE: __('URL no compatible para incrustar', 'camalote-wp-editorial-control'),
    
    // Form controls
    SEARCH_CONTENT: __('Buscar contenido...', 'camalote-wp-editorial-control'),
    LOADING: __('Cargando...', 'camalote-wp-editorial-control'),
    SAVING: __('Guardando...', 'camalote-wp-editorial-control'),
    SAVE_CHANGES: __('Guardar Cambios', 'camalote-wp-editorial-control'),
    UNSAVED_CHANGES: __('Cambios sin guardar', 'camalote-wp-editorial-control'),
    SETTINGS_SAVED_SUCCESS: __('¡Configuración guardada exitosamente!', 'camalote-wp-editorial-control'),
    
    // Validation messages
    VALID_URL_REQUIRED: __('Por favor ingrese una URL válida con protocolo (http:// o https://).', 'camalote-wp-editorial-control'),
    
    // Misc
    NO_TITLE: __('(Sin título)', 'camalote-wp-editorial-control'),
    REMOVE: __('Eliminar', 'camalote-wp-editorial-control'),
};