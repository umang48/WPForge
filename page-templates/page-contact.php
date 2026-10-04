<?php
/**
 * Template Name: Contact Page
 *
 * @package WPForge
 */

// 1. Initialize variables to maintain sticky form values and feedback states
$form_feedback = '';
$form_status   = '';
$name          = '';
$email         = '';
$message       = '';

// 2. Handle the POST request securely BEFORE any HTML is rendered
if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset( $_POST['wpforge_contact_submit'] ) ) {
    
    // CSRF Protection: Verify the nonce
    if ( ! isset( $_POST['wpforge_contact_nonce'] ) || ! wp_verify_nonce( wp_unslash( $_POST['wpforge_contact_nonce'] ), 'wpforge_submit_contact' ) ) {
        $form_feedback = __( 'Security check failed. Please refresh the page and try again.', 'wpforge' );
        $form_status   = 'error';
    } else {
        
        // Input Sanitization: Strip malicious scripts/tags immediately
        $name    = isset( $_POST['contact_name'] ) ? sanitize_text_field( wp_unslash( $_POST['contact_name'] ) ) : '';
        $email   = isset( $_POST['contact_email'] ) ? sanitize_email( wp_unslash( $_POST['contact_email'] ) ) : '';
        $message = isset( $_POST['contact_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['contact_message'] ) ) : '';
        
        // Validation: Ensure data integrity
        if ( empty( $name ) || empty( $email ) || empty( $message ) ) {
            $form_feedback = __( 'Please fill out all required fields.', 'wpforge' );
            $form_status   = 'error';
        } elseif ( ! is_email( $email ) ) {
            $form_feedback = __( 'Please provide a valid email address.', 'wpforge' );
            $form_status   = 'error';
        } else {
            
            // Execution: Fetch dynamic settings
            $options = get_option( 'wpforge_theme_options' );
            
            // Determine recipient (fallback to admin email if not set in theme options)
            $to = ! empty( $options['contact_email'] ) ? sanitize_email( $options['contact_email'] ) : get_option( 'admin_email' );
            
            // Determine success message
            $success_default = __( 'Thank you! Your message has been sent successfully.', 'wpforge' );
            $success_msg = ! empty( $options['contact_msg'] ) ? sanitize_text_field( $options['contact_msg'] ) : $success_default;

            $subject = sprintf( __( 'New Website Inquiry from %s', 'wpforge' ), $name );
            
            // Build safe headers
            $headers   = array();
            $headers[] = 'From: WPForge Website <' . get_option( 'admin_email' ) . '>';
            $headers[] = 'Reply-To: ' . $name . ' <' . $email . '>';
            
            $mail_sent = wp_mail( $to, $subject, $message, $headers );
            
            if ( $mail_sent ) {
                $form_feedback = $success_msg;
                $form_status   = 'success';
                // Reset fields on success
                $name = $email = $message = '';
            } else {
                $form_feedback = __( 'There was a server issue sending your message. Please try again later.', 'wpforge' );
                $form_status   = 'error';
            }
        }
    }
}

get_header();
?>

<main id="primary" class="site-main" style="padding: 60px 0;">
    <div class="container">
        
        <?php while ( have_posts() ) : the_post(); ?>

            <header class="entry-header" style="text-align: center; margin-bottom: 50px;">
                <?php the_title( '<h1 class="entry-title" style="font-size: 3em; color: var(--wp--preset--color--primary);">', '</h1>' ); ?>
            </header>

            <div class="contact-layout" style="display: flex; flex-wrap: wrap; gap: 60px; max-width: 1000px; margin: 0 auto;">
                
                <!-- Left Column: Gutenberg Content (Address, Maps, Intro) -->
                <div class="contact-content" style="flex: 1; min-width: 300px;">
                    <?php the_content(); ?>
                </div>

                <!-- Right Column: The Secure Form -->
                <div class="contact-form-wrapper" style="flex: 1; min-width: 300px; background: var(--wp--preset--color--surface); padding: 40px; border-radius: 8px; border: 1px solid #eee;">
                    
                    <h2 style="margin-top: 0; font-size: 1.5em; margin-bottom: 20px;">
                        <?php esc_html_e( 'Send us a message', 'wpforge' ); ?>
                    </h2>

                    <?php if ( ! empty( $form_feedback ) ) : ?>
                        <div class="form-feedback <?php echo esc_attr( $form_status ); ?>" style="padding: 15px; border-radius: 4px; margin-bottom: 20px; font-weight: bold; <?php echo $form_status === 'success' ? 'background: #d4edda; color: #155724;' : 'background: #f8d7da; color: #721c24;'; ?>">
                            <?php echo esc_html( $form_feedback ); ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo esc_url( get_permalink() ); ?>" method="post" class="wpforge-contact-form" style="display: flex; flex-direction: column; gap: 20px;">
                        
                        <?php wp_nonce_field( 'wpforge_submit_contact', 'wpforge_contact_nonce' ); ?>

                        <div>
                            <label for="contact_name" style="display: block; font-weight: bold; margin-bottom: 5px;"><?php esc_html_e( 'Name *', 'wpforge' ); ?></label>
                            <input type="text" id="contact_name" name="contact_name" value="<?php echo esc_attr( $name ); ?>" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;" />
                        </div>

                        <div>
                            <label for="contact_email" style="display: block; font-weight: bold; margin-bottom: 5px;"><?php esc_html_e( 'Email *', 'wpforge' ); ?></label>
                            <input type="email" id="contact_email" name="contact_email" value="<?php echo esc_attr( $email ); ?>" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;" />
                        </div>

                        <div>
                            <label for="contact_message" style="display: block; font-weight: bold; margin-bottom: 5px;"><?php esc_html_e( 'Message *', 'wpforge' ); ?></label>
                            <textarea id="contact_message" name="contact_message" rows="5" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; resize: vertical;"><?php echo esc_textarea( $message ); ?></textarea>
                        </div>

                        <div>
                            <button type="submit" name="wpforge_contact_submit" class="button" style="width: 100%; font-size: 1.1em; padding: 15px;">
                                <?php esc_html_e( 'Send Message', 'wpforge' ); ?>
                            </button>
                        </div>

                    </form>
                </div>

            </div><!-- .contact-layout -->

        <?php endwhile; ?>
        
    </div><!-- .container -->
</main><!-- #primary -->

<?php get_footer(); ?>