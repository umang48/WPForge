<?php
/**
 * Custom Meta Boxes for WPForge
 *
 * @package WPForge
 */

/**
 * Register meta boxes.
 */
function wpforge_add_meta_boxes() {
    add_meta_box(
        'wpforge_project_details',                 // Unique ID
        __( 'Project Details', 'wpforge' ),        // Box title
        'wpforge_project_details_html',            // Content callback
        'project',                                 // Post type
        'normal',                                  // Context (normal, side, advanced)
        'high'                                     // Priority
    );
}
add_action( 'add_meta_boxes', 'wpforge_add_meta_boxes' );

/**
 * HTML callback for the project details meta box.
 *
 * @param WP_Post $post Current post object.
 */
function wpforge_project_details_html( $post ) {
    // 1. Create a nonce field for security validation on save
    wp_nonce_field( 'wpforge_save_project_details', 'wpforge_project_nonce' );

    // 2. Retrieve existing values from the database
    $client = get_post_meta( $post->ID, '_wpforge_project_client', true );
    $tech   = get_post_meta( $post->ID, '_wpforge_project_tech', true );
    $url    = get_post_meta( $post->ID, '_wpforge_project_url', true );

    // 3. Render the fields (Always escape output!)
    ?>
    <div style="display: flex; flex-direction: column; gap: 15px; margin-top: 10px;">
        <p>
            <label for="wpforge_project_client" style="font-weight: bold; display: block; margin-bottom: 5px;">
                <?php esc_html_e( 'Client Name', 'wpforge' ); ?>
            </label>
            <input type="text" id="wpforge_project_client" name="wpforge_project_client" value="<?php echo esc_attr( $client ); ?>" style="width: 100%; max-width: 400px;" />
        </p>

        <p>
            <label for="wpforge_project_tech" style="font-weight: bold; display: block; margin-bottom: 5px;">
                <?php esc_html_e( 'Core Technologies Used', 'wpforge' ); ?>
            </label>
            <input type="text" id="wpforge_project_tech" name="wpforge_project_tech" value="<?php echo esc_attr( $tech ); ?>" style="width: 100%; max-width: 400px;" placeholder="e.g., React, Laravel, Tailwind" />
        </p>

        <p>
            <label for="wpforge_project_url" style="font-weight: bold; display: block; margin-bottom: 5px;">
                <?php esc_html_e( 'Live Project URL', 'wpforge' ); ?>
            </label>
            <input type="url" id="wpforge_project_url" name="wpforge_project_url" value="<?php echo esc_url( $url ); ?>" style="width: 100%; max-width: 400px;" placeholder="https://" />
        </p>
    </div>
    <?php
}

/**
 * Save meta box content.
 *
 * @param int $post_id Post ID.
 */
function wpforge_save_project_meta( $post_id ) {
    // 1. Verify the nonce (CSRF protection)
    if ( ! isset( $_POST['wpforge_project_nonce'] ) || ! wp_verify_nonce( $_POST['wpforge_project_nonce'], 'wpforge_save_project_details' ) ) {
        return;
    }

    // 2. Prevent saving during autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // 3. Check the user's permissions (Privilege escalation protection)
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    // 4. Sanitize and save Client Name
    if ( isset( $_POST['wpforge_project_client'] ) ) {
        $client_sanitized = sanitize_text_field( wp_unslash( $_POST['wpforge_project_client'] ) );
        update_post_meta( $post_id, '_wpforge_project_client', $client_sanitized );
    }

    // 5. Sanitize and save Technology
    if ( isset( $_POST['wpforge_project_tech'] ) ) {
        $tech_sanitized = sanitize_text_field( wp_unslash( $_POST['wpforge_project_tech'] ) );
        update_post_meta( $post_id, '_wpforge_project_tech', $tech_sanitized );
    }

    // 6. Sanitize and save URL
    if ( isset( $_POST['wpforge_project_url'] ) ) {
        $url_sanitized = esc_url_raw( wp_unslash( $_POST['wpforge_project_url'] ) );
        update_post_meta( $post_id, '_wpforge_project_url', $url_sanitized );
    }
}
add_action( 'save_post_project', 'wpforge_save_project_meta' ); // Target specifically the 'project' post type