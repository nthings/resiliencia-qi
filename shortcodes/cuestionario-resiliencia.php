<?php
/**
 * Mostrar formulario para inventario de resiliencia
 *
 * [cuestionario-resiliencia]
 *
 * @package	 resiliencia-qi
 * @since    1.0.0
 */
if ( ! function_exists( 'cuestionario_resiliencia_shortcode' ) ) {
	// Add the action.
	add_action( 'plugins_loaded', function() {
		// Add the shortcode.
		add_shortcode( 'cuestionario-resiliencia', 'cuestionario_resiliencia_shortcode' );
	});

	/**
	 * cuestionario-resiliencia shortcode.
	 *
	 * @return string
	 * @since  1.0.0
	 */
	function cuestionario_resiliencia_shortcode() {
        ob_start();
        guardar_cuestionario();
        render_html_form_cuestionario();
        return ob_get_clean();
    }
    
    function render_html_form_cuestionario() {

        global $wpdb;
        $table_name = $wpdb->prefix . "preguntas";
        $preguntas = $wpdb->get_results(
        "SELECT * FROM $table_name"
        );
        $variables = array(
            "%REQUEST_URI%",
            "%PREGUNTAS%",
        );
        $values = array(
            esc_url( $_SERVER['REQUEST_URI'] ),
            $preguntas,
        );
        echo str_replace($variables, $values, file_get_contents( plugin_dir_path( __DIR__ ) . "/templates/cuestionario-resiliencia.html" ));
    }

    function guardar_cuestionario() {
        if ( isset( $_POST['submitted'] ) ) {
            global $wpdb;

            $table_name = $wpdb->prefix . "factores_resiliencia";
            $values = array(
                'nombre'             => $_POST['nombre'],
                'fechadenacimiento'  => date('Y-m-d', strtotime($_POST['fechadenacimiento'])),
                'edad'               => $_POST['edad'],
                'fechaaplicacion'    => current_time( 'mysql' ),
            );
            $wpdb->insert( $table_name, $values, array(
                '%s',
                '%s',
                '%d',
                '%s',
            ));
            echo 'Formulario enviado correctamente';
        }
    }
}