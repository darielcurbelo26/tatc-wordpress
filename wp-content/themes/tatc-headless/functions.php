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

    // Page Gate: cada entrada protege una página del frontend con su propio
    // password. El password nunca se expone vía REST — solo se compara
    // server-side en tatc_verify_page_password(). Para proteger una página
    // nueva, solo hace falta crear una entrada aquí, sin tocar código.
    register_post_type('tatc_gate', array(
        'labels' => array(
            'name' => 'Page Gates',
            'singular_name' => 'Page Gate',
            'add_new' => 'Añadir nuevo Gate',
            'add_new_item' => 'Añadir nuevo Gate',
            'edit_item' => 'Editar Gate',
        ),
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'supports' => array('title'),
        'has_archive' => false,
        'menu_icon' => 'dashicons-lock',
        'show_in_rest' => false,
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

    // Campos para Page Gate
    acf_add_local_field_group(array(
        'key' => 'group_tatc_gate',
        'title' => 'Configuración del Gate',
        'fields' => array(
            array(
                'key' => 'field_gate_page_file',
                'label' => 'Página a proteger',
                'name' => 'page_file',
                'type' => 'text',
                'instructions' => 'Nombre exacto del archivo, ej: a-sweet-kid-online.html',
            ),
            array(
                'key' => 'field_gate_password',
                'label' => 'Password',
                'name' => 'password',
                'type' => 'text',
                'instructions' => 'Este valor nunca se expone públicamente — solo se compara en el servidor cuando alguien intenta entrar.',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'tatc_gate',
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

// 4b. Pantalla de ajustes "TATC Content" — secciones de content.json que se
// migran a gestión por WordPress (artist, global, home, gate texts). Cada
// sección se guarda como un option propio con la MISMA forma que su
// contraparte en content.json — tatc_get_custom_content() las usa para
// sobreescribir esa sección si el option ya fue guardado al menos una vez.
add_action('admin_menu', function () {
    add_options_page('TATC Content', 'TATC Content', 'manage_options', 'tatc-content-settings', 'tatc_render_content_settings_page');
});

add_action('admin_init', function () {
    register_setting('tatc_content_settings', 'tatc_artist', array('type' => 'array', 'default' => array()));
    register_setting('tatc_content_settings', 'tatc_global', array('type' => 'array', 'default' => array()));
    register_setting('tatc_content_settings', 'tatc_home', array('type' => 'array', 'default' => array()));
    register_setting('tatc_content_settings', 'tatc_security_gate', array('type' => 'array', 'default' => array()));
});

function tatc_render_content_settings_page() {
    $artist = get_option('tatc_artist', array());
    $global = get_option('tatc_global', array());
    $nav = $global['nav'] ?? array();
    $social = $global['social'] ?? array();
    $home = get_option('tatc_home', array());
    $security_gate = get_option('tatc_security_gate', array());
    ?>
    <div class="wrap">
        <h1>TATC Content</h1>
        <form method="post" action="options.php">
            <?php settings_fields('tatc_content_settings'); ?>

            <h2>Artist</h2>
            <table class="form-table">
                <tr>
                    <th><label for="artist_meta_title">Meta Title</label></th>
                    <td><input type="text" id="artist_meta_title" name="tatc_artist[meta_title]" class="regular-text" value="<?php echo esc_attr($artist['meta_title'] ?? ''); ?>"></td>
                </tr>
                <tr>
                    <th><label for="artist_label">Label</label></th>
                    <td><input type="text" id="artist_label" name="tatc_artist[label]" class="regular-text" value="<?php echo esc_attr($artist['label'] ?? ''); ?>"></td>
                </tr>
                <tr>
                    <th><label for="artist_name">Name</label></th>
                    <td><input type="text" id="artist_name" name="tatc_artist[name]" class="regular-text" value="<?php echo esc_attr($artist['name'] ?? ''); ?>"></td>
                </tr>
                <tr>
                    <th><label for="artist_bio1">Bio 1</label></th>
                    <td><textarea id="artist_bio1" name="tatc_artist[bio1]" rows="4" class="large-text"><?php echo esc_textarea($artist['bio1'] ?? ''); ?></textarea></td>
                </tr>
                <tr>
                    <th><label for="artist_bio2">Bio 2</label></th>
                    <td><textarea id="artist_bio2" name="tatc_artist[bio2]" rows="4" class="large-text"><?php echo esc_textarea($artist['bio2'] ?? ''); ?></textarea></td>
                </tr>
            </table>

            <h2>Global</h2>
            <table class="form-table">
                <tr>
                    <th><label for="global_brand">Brand</label></th>
                    <td><input type="text" id="global_brand" name="tatc_global[brand]" class="regular-text" value="<?php echo esc_attr($global['brand'] ?? ''); ?>"></td>
                </tr>
                <tr>
                    <th><label for="global_tagline">Tagline</label></th>
                    <td><input type="text" id="global_tagline" name="tatc_global[tagline]" class="regular-text" value="<?php echo esc_attr($global['tagline'] ?? ''); ?>"></td>
                </tr>
                <tr>
                    <th><label for="global_year">Year</label></th>
                    <td><input type="text" id="global_year" name="tatc_global[year]" class="small-text" value="<?php echo esc_attr($global['year'] ?? ''); ?>"></td>
                </tr>
                <tr>
                    <th>Nav — Blog label</th>
                    <td><input type="text" name="tatc_global[nav][blog]" class="regular-text" value="<?php echo esc_attr($nav['blog'] ?? ''); ?>"></td>
                </tr>
                <tr>
                    <th>Nav — Projects label</th>
                    <td><input type="text" name="tatc_global[nav][projects]" class="regular-text" value="<?php echo esc_attr($nav['projects'] ?? ''); ?>"></td>
                </tr>
                <tr>
                    <th>Nav — Info label</th>
                    <td><input type="text" name="tatc_global[nav][info]" class="regular-text" value="<?php echo esc_attr($nav['info'] ?? ''); ?>"></td>
                </tr>
                <tr>
                    <th>Social — Twitter/X URL</th>
                    <td><input type="text" name="tatc_global[social][twitter]" class="regular-text" value="<?php echo esc_attr($social['twitter'] ?? ''); ?>"></td>
                </tr>
                <tr>
                    <th>Social — Email (mailto:...)</th>
                    <td><input type="text" name="tatc_global[social][email]" class="regular-text" value="<?php echo esc_attr($social['email'] ?? ''); ?>"></td>
                </tr>
                <tr>
                    <th>Social — Instagram URL</th>
                    <td><input type="text" name="tatc_global[social][instagram]" class="regular-text" value="<?php echo esc_attr($social['instagram'] ?? ''); ?>"></td>
                </tr>
            </table>

            <h2>Home</h2>
            <table class="form-table">
                <tr>
                    <th><label for="home_meta_title">Meta Title</label></th>
                    <td><input type="text" id="home_meta_title" name="tatc_home[meta_title]" class="regular-text" value="<?php echo esc_attr($home['meta_title'] ?? ''); ?>"></td>
                </tr>
                <tr>
                    <th><label for="home_loader_text">Loader Text</label></th>
                    <td><textarea id="home_loader_text" name="tatc_home[loader_text]" rows="3" class="large-text"><?php echo esc_textarea($home['loader_text'] ?? ''); ?></textarea></td>
                </tr>
                <tr>
                    <th><label for="home_loader_skip_label">Loader Skip Label</label></th>
                    <td><input type="text" id="home_loader_skip_label" name="tatc_home[loader_skip_label]" class="regular-text" value="<?php echo esc_attr($home['loader_skip_label'] ?? ''); ?>"></td>
                </tr>
                <tr>
                    <th><label for="home_hero_headline">Hero Headline</label></th>
                    <td><input type="text" id="home_hero_headline" name="tatc_home[hero_headline]" class="regular-text" value="<?php echo esc_attr($home['hero_headline'] ?? ''); ?>"></td>
                </tr>
            </table>

            <h2>Gate (textos del password gate)</h2>
            <table class="form-table">
                <tr>
                    <th><label for="gate_title">Gate Title</label></th>
                    <td><input type="text" id="gate_title" name="tatc_security_gate[gate_title]" class="regular-text" value="<?php echo esc_attr($security_gate['gate_title'] ?? ''); ?>"></td>
                </tr>
                <tr>
                    <th><label for="gate_description">Gate Description</label></th>
                    <td><input type="text" id="gate_description" name="tatc_security_gate[gate_description]" class="regular-text" value="<?php echo esc_attr($security_gate['gate_description'] ?? ''); ?>"></td>
                </tr>
            </table>

            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

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

    // --- A0. SECCIONES GESTIONADAS DESDE "TATC Content" (wp-admin) ---
    // Solo sobreescribe si el option ya se guardó al menos una vez (evita
    // que content.json se vacíe antes de que alguien abra la pantalla de
    // ajustes por primera vez).
    $tatc_artist = get_option('tatc_artist', array());
    if (!empty($tatc_artist)) {
        $data['artist'] = array_merge($data['artist'] ?? array(), $tatc_artist);
    }

    $tatc_global = get_option('tatc_global', array());
    if (!empty($tatc_global)) {
        $data['global'] = array_merge($data['global'] ?? array(), $tatc_global);
    }

    $tatc_home = get_option('tatc_home', array());
    if (!empty($tatc_home)) {
        $data['home'] = array_merge($data['home'] ?? array(), $tatc_home);
    }

    $tatc_security_gate = get_option('tatc_security_gate', array());
    if (!empty($tatc_security_gate)) {
        $data['security'] = array_merge($data['security'] ?? array(), $tatc_security_gate);
    }

    // --- A-1. BLOG / "THE GIST" — usa las Entradas nativas de WordPress ---
    list($tatc_blog_posts, $tatc_post_entries) = tatc_build_blog_and_posts();
    if (!empty($tatc_blog_posts)) {
        $data['blog']['posts'] = $tatc_blog_posts;
        $data['post']['posts'] = $tatc_post_entries;
    }

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

                // ACF field bucket names ('front'/'left'/'right'/'back') stay as-is —
                // they're just storage slots. The camera in a-sweet-kid-online.js can
                // only pan +/-90deg (3 walls reachable: left/center/right), so the
                // JSON output translates each bucket to the position the frontend
                // actually understands. 'right' bucket previously mapped to the
                // unreachable 4th wall — now it correctly lands on the right wall.
                $walls = array('front', 'left', 'right', 'back');
                $wall_label_map = array('front' => 'left', 'left' => 'center', 'right' => 'right', 'back' => 'right');
                $art_id = 0;
                foreach ($walls as $wall) {
                    $img_id = get_field("wall_{$wall}_image");
                    if ($img_id) {
                        $img_url = wp_get_attachment_image_url($img_id, 'full');
                        if ($img_url) {
                            $gallery_3d_artworks[] = array(
                                'id' => $art_id++,
                                'wall' => $wall_label_map[$wall] ?? $wall,
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

// Convierte los bloques de Gutenberg de una Entrada en el formato body[] que
// post.html espera: { type: paragraph|image|pullquote, ... }. Bloques que no
// son ninguno de estos tres se ignoran (no hay un tipo equivalente todavía).
function tatc_parse_post_body($post_content) {
    $body = array();

    foreach (parse_blocks($post_content) as $block) {
        $name = $block['blockName'];
        $html = $block['innerHTML'] ?? '';

        if ($name === 'core/paragraph') {
            $text = trim(wp_strip_all_tags($html));
            if ($text !== '') {
                $body[] = array('type' => 'paragraph', 'text' => $text);
            }
        } elseif ($name === 'core/image') {
            if (preg_match('/<img[^>]+src="([^"]+)"/', $html, $m_src)) {
                $alt = '';
                if (preg_match('/alt="([^"]*)"/', $html, $m_alt)) {
                    $alt = $m_alt[1];
                }
                $caption = '';
                if (preg_match('/<figcaption[^>]*>(.*?)<\/figcaption>/s', $html, $m_cap)) {
                    $caption = trim(wp_strip_all_tags($m_cap[1]));
                }
                $body[] = array('type' => 'image', 'src' => $m_src[1], 'alt' => $alt, 'caption' => $caption);
            }
        } elseif ($name === 'core/quote' || $name === 'core/pullquote') {
            $text = trim(wp_strip_all_tags($html));
            if ($text !== '') {
                $body[] = array('type' => 'pullquote', 'text' => $text);
            }
        }
    }

    return $body;
}

// Arma blog.posts[] (listado) y post.posts{} (detalle) a partir de las
// Entradas nativas de WordPress, en la misma forma que content.json espera.
// slug = post_name, subject = nombre de la primera categoría asignada.
function tatc_build_blog_and_posts() {
    $posts = get_posts(array(
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ));

    $blog_posts   = array();
    $post_entries = array();

    foreach ($posts as $p) {
        $slug  = $p->post_name;
        $date  = get_the_date('d.m.y', $p);
        $title = get_the_title($p);

        $summary = has_excerpt($p)
            ? get_the_excerpt($p)
            : wp_trim_words(wp_strip_all_tags($p->post_content), 30);

        $blog_posts[] = array(
            'slug'    => $slug,
            'date'    => $date,
            'title'   => $title,
            'summary' => $summary,
            'url'     => 'post.html?slug=' . $slug,
        );

        $categories = get_the_category($p->ID);
        $subject    = !empty($categories) ? $categories[0]->name : '';

        $post_entries[$slug] = array(
            'meta_title'    => 'TATC — ' . $title,
            'date'          => $date,
            'subject'       => $subject,
            'title'         => $title,
            'body'          => tatc_parse_post_body($p->post_content),
            'footer_label'  => 'TATC — THE GIST',
            'footer_credit' => 'ISEEASI — 2026',
        );
    }

    return array($blog_posts, $post_entries);
}

// 6b. "Visit Site" en la barra de admin debe llevar al frontend real, no al
// WordPress headless (que no tiene nada que mostrar visualmente). Actualizar
// esta URL cuando se migre a producción (ver GUIA-PRODUCCION.md).
define('TATC_FRONTEND_URL', 'https://darielcurbelo26.github.io/cms-system-asi/');

add_action('admin_bar_menu', function ($wp_admin_bar) {
    $wp_admin_bar->add_node(array('id' => 'site-name', 'href' => TATC_FRONTEND_URL));
    $wp_admin_bar->add_node(array('id' => 'view-site', 'parent' => 'site-name', 'href' => TATC_FRONTEND_URL, 'title' => 'Visit Site'));
}, 81);

// 7. Verificación de password server-side para páginas protegidas (Page Gate)
// El password real nunca sale de WordPress: el frontend solo recibe true/false.
add_action('rest_api_init', function () {
    register_rest_route('tatc/v1', '/verify-password', array(
        'methods' => 'POST',
        'callback' => 'tatc_verify_page_password',
        'permission_callback' => '__return_true',
        'args' => array(
            'page' => array('required' => true, 'type' => 'string'),
            'password' => array('required' => true, 'type' => 'string'),
        ),
    ));
});

function tatc_verify_page_password(WP_REST_Request $request) {
    $page = sanitize_text_field($request->get_param('page'));
    $password = (string) $request->get_param('password');

    $gates = get_posts(array(
        'post_type' => 'tatc_gate',
        'posts_per_page' => 1,
        'meta_key' => 'page_file',
        'meta_value' => $page,
    ));

    $ok = false;
    if (!empty($gates)) {
        $stored = (string) get_field('password', $gates[0]->ID);
        $ok = $stored !== '' && hash_equals($stored, $password);
    }

    $response = rest_ensure_response(array('ok' => $ok));
    $response->header('Access-Control-Allow-Origin', '*');
    return $response;
}


