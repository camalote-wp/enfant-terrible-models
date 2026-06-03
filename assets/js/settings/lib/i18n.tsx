// assets/js/settings/i18n.js
import { __ } from '@wordpress/i18n';

// Export commonly used strings as constants for consistency
export const STRINGS = {
    // Page titles
    EDITORIAL_CONTROL: __('Editorial Control', 'camalotewp-editorial-control'),
    COVER: __('Portada', 'camalotewp-editorial-control'),
    
    // Article types
    ARTICLE_PRIMARY: __('Artículo Primario', 'camalotewp-editorial-control'),
    ARTICLE_SECONDARY: __('Artículo Secundario', 'camalotewp-editorial-control'),
    ARTICLE_TERTIARY: __('Artículo Terciario', 'camalotewp-editorial-control'),
    COVER_ARTICLES: __('Artículos de tapa', 'camalotewp-editorial-control'),
    
    // Audiovisual section
    AUDIOVISUAL_SECTION: __('Sección Audiovisual', 'camalotewp-editorial-control'),
    VIDEO_TITLE: __('Título del video', 'camalotewp-editorial-control'),
    VIDEO_URL: __('URL del video', 'camalotewp-editorial-control'),
    VIDEO_DESCRIPTION: __('Descripción del video', 'camalotewp-editorial-control'),
    NO_AUDIOVISUAL_CONTENT: __('No hay contenido audiovisual.', 'camalotewp-editorial-control'),
    EMBED_URL_NOT_COMPATIBLE: __('URL no compatible para incrustar', 'camalotewp-editorial-control'),
    
    // Form controls
    SEARCH_CONTENT: __('Buscar contenido...', 'camalotewp-editorial-control'),
    LOADING: __('Cargando...', 'camalotewp-editorial-control'),
    SAVING: __('Guardando...', 'camalotewp-editorial-control'),
    SAVE_CHANGES: __('Guardar Cambios', 'camalotewp-editorial-control'),
    UNSAVED_CHANGES: __('Cambios sin guardar', 'camalotewp-editorial-control'),
    SETTINGS_SAVED_SUCCESS: __('¡Configuración guardada exitosamente!', 'camalotewp-editorial-control'),
    
    // Validation messages
    VALID_URL_REQUIRED: __('Por favor ingrese una URL válida con protocolo (http:// o https://).', 'camalotewp-editorial-control'),
    
    // Misc
    NO_TITLE: __('(Sin título)', 'camalotewp-editorial-control'),
    REMOVE: __('Eliminar', 'camalotewp-editorial-control'),
};