<?php
// 1. Añadir soporte para imágenes destacadas (Thumbnails)
add_theme_support('post-thumbnails');

// 2. Registrar el Custom Post Type "Proyectos"
add_action('init', function() {
    register_post_type('project', array(
        'labels' => array(
            'name' => 'Proyectos',
            'singular_name' => 'Proyecto',
            'add_new' => 'Añadir nuevo Proyecto',
            'add_new_item' => 'Añadir nuevo Proyecto',
            'edit_item' => 'Editar Proyecto',
        ),
        'public' => true,
        'has_archive' => false,
        'supports' => array('title', 'thumbnail', 'page-attributes'), // page-attributes habilita el "Orden" (menu_order)
        'menu_icon' => 'dashicons-portfolio',
        'show_in_rest' => true,
    ));
});

// 3. Configurar campos de ACF automáticamente
add_action('acf/init', function() {
    if( function_exists('acf_add_local_field_group') ):

    // Campos para Proyectos (Unificados)
    acf_add_local_field_group(array(
        'key' => 'group_project_details',
        'title' => 'Detalles del Proyecto',
        'fields' => array(
            // --- TAB GENERAL ---
            array(
                'key' => 'field_tab_general',
                'label' => 'General',
                'type' => 'tab',
            ),
            array(
                'key' => 'field_project_type',
                'label' => 'Tipo de Proyecto',
                'name' => 'project_type',
                'type' => 'select',
                'choices' => array(
                    'standard' => 'Estándar (Con Portada y Detalles)',
                    'virtual' => 'Experiencia Virtual 3D (Cubo interactivo)',
                ),
                'default_value' => 'standard',
            ),
            array(
                'key' => 'field_iframe_url',
                'label' => 'URL de la Escena 3D (Iframe)',
                'name' => 'iframe_url',
                'type' => 'text',
                'instructions' => 'Ej: cube-scene.html',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_project_type',
                            'operator' => '==',
                            'value' => 'virtual',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_custom_link',
                'label' => 'Enlace del Proyecto (Link Interno)',
                'name' => 'custom_link',
                'type' => 'text',
                'instructions' => 'Ej: a-sweet-kid-online.html (Dejar vacío para usar la página de detalle dinámica por defecto)',
            ),
            array(
                'key' => 'field_project_desc',
                'label' => 'Descripción Corta',
                'name' => 'desc',
                'type' => 'text',
                'instructions' => 'Ej: Multimedia installation',
            ),
            array(
                'key' => 'field_project_date',
                'label' => 'Fecha/Año',
                'name' => 'date',
                'type' => 'text',
                'instructions' => 'Ej: 2025',
            ),
            array(
                'key' => 'field_project_loc',
                'label' => 'Locación',
                'name' => 'loc',
                'type' => 'text',
                'instructions' => 'Ej: Mexico City',
            ),
            array(
                'key' => 'field_project_subtitle',
                'label' => 'Subtítulo (Para la página de detalle)',
                'name' => 'subtitle',
                'type' => 'text',
            ),
            array(
                'key' => 'field_project_medium',
                'label' => 'Medio',
                'name' => 'medium',
                'type' => 'text',
                'instructions' => 'Ej: Exhibición, Pintura, etc.',
            ),
            array(
                'key' => 'field_project_body',
                'label' => 'Cuerpo del texto (Detalle)',
                'name' => 'body',
                'type' => 'textarea',
                'instructions' => 'Párrafos separados por saltos de línea.',
            ),

            // --- TAB CONFIGURACIÓN SALA 3D ---
            array(
                'key' => 'field_tab_virtual',
                'label' => 'Obras 3D (Virtual)',
                'type' => 'tab',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_project_type',
                            'operator' => '==',
                            'value' => 'virtual',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_audio_override',
                'label' => 'Música de la Sala (Archivo de Audio)',
                'name' => 'audio_override',
                'type' => 'file',
                'return_format' => 'id',
                'mime_types' => 'mp3',
                'instructions' => 'Sube o selecciona un archivo de audio .mp3.',
            ),
            // Pared Frontal
            array(
                'key' => 'field_wall_front_image',
                'label' => 'Obra - Pared Frontal',
                'name' => 'wall_front_image',
                'type' => 'image',
                'return_format' => 'id',
                'wrapper' => array('width' => '40'),
            ),
            array(
                'key' => 'field_wall_front_title',
                'label' => 'Título',
                'name' => 'wall_front_title',
                'type' => 'text',
                'wrapper' => array('width' => '30'),
            ),
            array(
                'key' => 'field_wall_front_desc',
                'label' => 'Descripción/Alt',
                'name' => 'wall_front_desc',
                'type' => 'text',
                'wrapper' => array('width' => '30'),
            ),
            // Pared Izquierda
            array(
                'key' => 'field_wall_left_image',
                'label' => 'Obra - Pared Izquierda',
                'name' => 'wall_left_image',
                'type' => 'image',
                'return_format' => 'id',
                'wrapper' => array('width' => '40'),
            ),
            array(
                'key' => 'field_wall_left_title',
                'label' => 'Título',
                'name' => 'wall_left_title',
                'type' => 'text',
                'wrapper' => array('width' => '30'),
            ),
            array(
                'key' => 'field_wall_left_desc',
                'label' => 'Descripción/Alt',
                'name' => 'wall_left_desc',
                'type' => 'text',
                'wrapper' => array('width' => '30'),
            ),
            // Pared Derecha
            array(
                'key' => 'field_wall_right_image',
                'label' => 'Obra - Pared Derecha',
                'name' => 'wall_right_image',
                'type' => 'image',
                'return_format' => 'id',
                'wrapper' => array('width' => '40'),
            ),
            array(
                'key' => 'field_wall_right_title',
                'label' => 'Título',
                'name' => 'wall_right_title',
                'type' => 'text',
                'wrapper' => array('width' => '30'),
            ),
            array(
                'key' => 'field_wall_right_desc',
                'label' => 'Descripción/Alt',
                'name' => 'wall_right_desc',
                'type' => 'text',
                'wrapper' => array('width' => '30'),
            ),
            // Pared Trasera
            array(
                'key' => 'field_wall_back_image',
                'label' => 'Obra - Pared Trasera',
                'name' => 'wall_back_image',
                'type' => 'image',
                'return_format' => 'id',
                'wrapper' => array('width' => '40'),
            ),
            array(
                'key' => 'field_wall_back_title',
                'label' => 'Título',
                'name' => 'wall_back_title',
                'type' => 'text',
                'wrapper' => array('width' => '30'),
            ),
            array(
                'key' => 'field_wall_back_desc',
                'label' => 'Descripción/Alt',
                'name' => 'wall_back_desc',
                'type' => 'text',
                'wrapper' => array('width' => '30'),
            ),

            // --- TAB GALERÍA (ESTÁNDAR) ---
            array(
                'key' => 'field_tab_gallery',
                'label' => 'Galería (Estándar)',
                'type' => 'tab',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_project_type',
                            'operator' => '==',
                            'value' => 'standard',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_carousel_paths',
                'label' => 'Imágenes del Carrusel',
                'name' => 'carousel_paths',
                'type' => 'textarea',
                'instructions' => 'Una ruta de imagen por línea (relativa a la raíz del sitio). Ej: assets/images/_1.webp',
                'rows' => 12,
                'new_lines' => '',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'project',
                ),
            ),
        ),
    ));

    endif;
});

// Ocultar campos irrelevantes para proyectos estáticos/fijos (Evitando mezclar pestañas de ambos)
add_filter('acf/prepare_field', function($field) {
    global $post;
    if ($post && $post->post_type === 'project') {
        $slug = $post->post_name;
        $fixed_slugs = array('a-sweet-kid', 'a-sweet-kid-online');
        
        if (in_array($slug, $fixed_slugs)) {
            // Campos comunes a ocultar en fijos para no romper el diseño
            $common_hidden = array(
                'field_project_type',
                'field_iframe_url',
                'field_custom_link',
                'field_project_subtitle',
                'field_project_medium',
                'field_project_body'
            );
            if (in_array($field['key'], $common_hidden)) {
                return false;
            }

            // Obtener el tipo de proyecto actual
            $type = get_field('project_type', $post->ID) ?: 'standard';
            
            if ($type === 'virtual') {
                // Ocultar pestaña y campos de galería estándar
                $gallery_fields = array(
                    'field_tab_gallery',
                    'field_carousel_paths'
                );
                if (in_array($field['key'], $gallery_fields)) {
                    return false;
                }
            } else {
                // Ocultar pestaña, audio y campos de obras 3D
                $virtual_fields = array(
                    'field_tab_virtual',
                    'field_audio_override',
                    'field_wall_front_image', 'field_wall_front_title', 'field_wall_front_desc',
                    'field_wall_left_image', 'field_wall_left_title', 'field_wall_left_desc',
                    'field_wall_right_image', 'field_wall_right_title', 'field_wall_right_desc',
                    'field_wall_back_image', 'field_wall_back_title', 'field_wall_back_desc'
                );
                if (in_array($field['key'], $virtual_fields)) {
                    return false;
                }
            }
        }
    }
    return $field;
});

// Helper para importar imágenes y archivos físicos locales a la biblioteca de medios de WordPress
function tatc_sideload_physical_image($file_path) {
    if (!file_exists($file_path)) {
        return false;
    }

    $filename = basename($file_path);
    global $wpdb;
    
    // Buscar si ya existe el archivo en la biblioteca
    $attachment_id = $wpdb->get_var($wpdb->prepare(
        "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_wp_attached_file' AND meta_value LIKE %s",
        '%' . $filename
    ));

    if ($attachment_id) {
        return (int)$attachment_id;
    }

    require_once(ABSPATH . 'wp-admin/includes/image.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');

    $wp_upload_dir = wp_upload_dir();
    $unique_name = wp_unique_filename($wp_upload_dir['path'], $filename);
    $new_file_path = $wp_upload_dir['path'] . '/' . $unique_name;

    if (!copy($file_path, $new_file_path)) {
        return false;
    }

    $file_type = wp_check_filetype($new_file_path, null);
    $attachment = array(
        'guid'           => $wp_upload_dir['url'] . '/' . basename($new_file_path), 
        'post_mime_type' => $file_type['type'],
        'post_title'     => preg_replace( '/\.[^.]+$/', '', basename($new_file_path)),
        'post_content'   => '',
        'post_status'    => 'inherit'
    );

    $attach_id = wp_insert_attachment($attachment, $new_file_path);
    if (!is_wp_error($attach_id)) {
        $attach_data = wp_generate_attachment_metadata($attach_id, $new_file_path);
        wp_update_attachment_metadata($attach_id, $attach_data);
        return $attach_id;
    }

    return false;
}

// 4. Script de Auto-Migración de los Proyectos Fijos con Sideload de Imágenes y Audio Local
add_action('admin_init', function() {
    if (!get_option('tatc_projects_migrated_v9')) {
        // Rutas absolutas a los archivos de imágenes y audio locales en la carpeta del frontend
        $base_workspace = '/Users/darielcurbelo/Desktop/VERSIONES ASI WEBSITE/ASI TEST (A CMS)/';
        
        $img_cover_path = $base_workspace . 'projects/a-sweet-kid-cdmx/images/proyecto_1.jpg';
        $img_front_path = $base_workspace . 'assets/artworks/a_sweet_kid_fragmented_identity_1773578325456.png';
        $img_left_path  = $base_workspace . 'assets/artworks/a_sweet_kid_negative_self_talk_1773578346007.png';
        $img_right_path = $base_workspace . 'assets/artworks/a_sweet_kid_solitary_confinement_1773578370915.png';
        $audio_path     = $base_workspace . 'projects/a-sweet-kid/audio/ambient.mp3';

        // Sideload de imágenes y audio
        $cover_id = tatc_sideload_physical_image($img_cover_path);
        $front_id = tatc_sideload_physical_image($img_front_path);
        $left_id  = tatc_sideload_physical_image($img_left_path);
        $right_id = tatc_sideload_physical_image($img_right_path);
        $audio_id = tatc_sideload_physical_image($audio_path);

        // --- 1. PROYECTO ESTÁNDAR: A Sweet Kid ---
        $post1 = get_page_by_path('a-sweet-kid', OBJECT, 'project');
        $pid1 = $post1 ? $post1->ID : wp_insert_post(array(
            'post_title' => 'A Sweet Kid',
            'post_name' => 'a-sweet-kid',
            'post_type' => 'project',
            'post_status' => 'publish',
            'menu_order' => 1
        ));

        if ($pid1 && !is_wp_error($pid1)) {
            update_field('project_type', 'standard', $pid1);
            update_field('custom_link', 'a-sweet-kid.html', $pid1);
            update_field('desc', 'Multimedia installation', $pid1);
            update_field('date', '2025', $pid1);
            update_field('loc', 'Mexico City — Salon Silicon', $pid1);
            
            // Asignar imagen destacada (portada)
            if ($cover_id) {
                set_post_thumbnail($pid1, $cover_id);
            }

            // Generar la lista completa de imágenes del carrusel desde assets/images/
            $carousel_dir = $base_workspace . 'assets/images/';
            $carousel_lines = array();
            if (is_dir($carousel_dir)) {
                $files = glob($carousel_dir . '*.webp');
                sort($files);
                foreach ($files as $f) {
                    $carousel_lines[] = 'assets/images/' . basename($f);
                }
            }
            if (!empty($carousel_lines)) {
                update_field('carousel_paths', implode("\n", $carousel_lines), $pid1);
            }
        }

        // --- 2. PROYECTO VIRTUAL: A Sweet Kid Online ---
        $post2 = get_page_by_path('a-sweet-kid-online', OBJECT, 'project');
        $pid2 = $post2 ? $post2->ID : wp_insert_post(array(
            'post_title' => 'A Sweet Kid Online',
            'post_name' => 'a-sweet-kid-online',
            'post_type' => 'project',
            'post_status' => 'publish',
            'menu_order' => 2
        ));

        if ($pid2 && !is_wp_error($pid2)) {
            update_field('project_type', 'virtual', $pid2);
            update_field('iframe_url', 'cube-scene.html', $pid2);
            update_field('custom_link', 'a-sweet-kid-online.html', $pid2);
            update_field('desc', 'Virtual Experience', $pid2);
            update_field('date', '2025', $pid2);
            update_field('loc', '', $pid2);
            
            if ($audio_id) {
                update_field('audio_override', $audio_id, $pid2);
            }

            // Asignar obras 3D a sus respectivas paredes
            if ($front_id) {
                update_field('wall_front_image', $front_id, $pid2);
                update_field('wall_front_title', 'Fragmented Identity', $pid2);
                update_field('wall_front_desc', 'Fragmented Identity', $pid2);
            }
            if ($left_id) {
                update_field('wall_left_image', $left_id, $pid2);
                update_field('wall_left_title', 'Negative Self Talk', $pid2);
                update_field('wall_left_desc', 'Negative Self Talk', $pid2);
            }
            if ($right_id) {
                update_field('wall_right_image', $right_id, $pid2);
                update_field('wall_right_title', 'Solitary Confinement', $pid2);
                update_field('wall_right_desc', 'Solitary Confinement', $pid2);
            }
        }

        update_option('tatc_projects_migrated_v9', true);
    }
});

// 5. Registrar la API REST /wp-json/tatc/v1/content
add_action('rest_api_init', function () {
    register_rest_route('tatc/v1', '/content', array(
        'methods' => 'GET',
        'callback' => 'tatc_get_custom_content',
        'permission_callback' => '__return_true'
    ));
});

// 6. Función que genera el JSON para el Frontend
function tatc_get_custom_content() {
    $json_path = get_stylesheet_directory() . '/content.json';
    if (!file_exists($json_path)) {
        return new WP_Error('no_file', 'content.json not found in theme', array('status' => 404));
    }
    
    $json_content = file_get_contents($json_path);
    $data = json_decode($json_content, true);

    // --- A. GESTIÓN DE PROYECTOS ---
    $projects_query = new WP_Query(array(
        'post_type' => 'project',
        'posts_per_page' => -1,
        'orderby' => 'menu_order date',
        'order' => 'ASC'
    ));

    if ($projects_query->have_posts()) {
        $next_id = 1;
        $wp_projects = array();
        $wp_details = array();
        $gallery_3d_artworks = array();
        $global_audio = null;
        
        while ($projects_query->have_posts()) {
            $projects_query->the_post();
            $pid = get_the_ID();
            $slug = get_post_field('post_name', $pid);
            
            $src = '';
            if (has_post_thumbnail()) $src = get_the_post_thumbnail_url($pid, 'full');

            $type = get_field('project_type') ?: 'standard';
            $custom_link = get_field('custom_link');
            $link = $custom_link ? $custom_link : 'project-page.html?project=' . $slug;

            $item = array(
                'id' => $next_id++,
                'title' => get_the_title(),
                'desc' => get_field('desc') ?: '',
                'date' => get_field('date') ?: '',
                'loc' => get_field('loc') ?: '',
                'link' => $link
            );

            if ($type === 'virtual') {
                $item['iframe'] = get_field('iframe_url') ?: 'cube-scene.html';
                
                // Extraer obras 3D del proyecto virtual para reconstruir la estructura 'gallery_3d'
                $audio_field_val = get_field('audio_override');
                if ($audio_field_val) {
                    // Si retorna ID o URL, nos aseguramos de sacar la URL
                    $global_audio = is_numeric($audio_field_val) ? wp_get_attachment_url($audio_field_val) : $audio_field_val;
                }

                $walls = array('front', 'left', 'right', 'back');
                $art_id = 0;
                foreach ($walls as $wall) {
                    $img_id = get_field("wall_{$wall}_image");
                    if ($img_id) {
                        $img_url = wp_get_attachment_image_url($img_id, 'full');
                        if ($img_url) {
                            $gallery_3d_artworks[] = array(
                                'id' => $art_id++,
                                'wall' => $wall,
                                'title' => get_field("wall_{$wall}_title") ?: '',
                                'alt' => get_field("wall_{$wall}_desc") ?: '',
                                'src' => $img_url
                            );
                        }
                    }
                }
            } else {
                $item['src'] = $src ? $src : 'assets/images/placeholder.jpg';
            }

            $wp_projects[] = $item;

            // Extraer las imágenes de galería/carrusel del proyecto estándar
            $gallery_urls = array();
            if ($type === 'standard') {
                $paths_raw = get_field('carousel_paths');
                if ($paths_raw) {
                    $lines = array_filter(array_map('trim', explode("\n", $paths_raw)));
                    foreach ($lines as $line) {
                        if (!empty($line)) {
                            $gallery_urls[] = $line;
                        }
                    }
                }
            }
            if (empty($gallery_urls) && $src) {
                $gallery_urls[] = $src;
            }

            $body_text = get_field('body') ?: '';
            $body_array = array_filter(array_map('trim', explode("\n", $body_text)));

            $wp_details[$slug] = array(
                'meta_title' => get_the_title() . ' — TATC',
                'title' => get_the_title(),
                'subtitle' => get_field('subtitle') ?: '',
                'date' => get_field('date') ?: '',
                'location' => get_field('loc') ?: '',
                'medium' => get_field('medium') ?: '',
                'body' => array_values($body_array),
                'gallery_images' => $gallery_urls
            );
        }

        $data['projects']['items'] = $wp_projects;
        $data['projects_detail'] = $wp_details;
        
        // Re-inyectar la galería 3D armada desde el proyecto virtual
        if (!empty($gallery_3d_artworks)) {
            $data['gallery_3d']['artworks'] = $gallery_3d_artworks;
        }
        if ($global_audio) {
            $data['gallery_3d']['audio_src'] = $global_audio;
        }
    }

    $response = rest_ensure_response($data);
    $response->header('Access-Control-Allow-Origin', '*');
    return $response;
}


