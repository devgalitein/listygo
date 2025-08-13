<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\listygo;

if ( post_password_required() ) {
    return;
}
?>
<div id="comments" class="comments-area single-blog-form">
    <?php if ( have_comments() ): ?>
    <div class="blog-comment">
        <?php
        $rdtheme_comment_count = get_comments_number();
        $rdtheme_comments_text = number_format_i18n( $rdtheme_comment_count ) ;
        if ( $rdtheme_comment_count > 1 ) {
            $rdtheme_comments_text .= esc_html__( ' Comments', 'listygo' );
        }
        else{
            $rdtheme_comments_text .= esc_html__( ' Comment', 'listygo' );
        }
        ?>
        <h3 class="comment-title"><?php echo esc_html( $rdtheme_comments_text ); ?></h3>
        <?php $rdtheme_avatar = get_option( 'show_avatars' ); ?>
        <ul class="comment-list<?php echo empty( $rdtheme_avatar ) ? ' avatar-disabled' : '';?>">
            <?php
                wp_list_comments(
                    array(
                        'style'        => 'ul',
                        'callback'     => 'radiustheme\listygo\Helper::comments_callback',
                        'reply_text'   => '<i class="fa-solid fa-reply"></i>',
                        'avatar_size'  => 100,
                        'format'       => 'html5'
                    ) 
                );
            ?>
        </ul>

        <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :?>
            <nav class="pagination-area comment-pagination">
                <ul>
                    <li class="older-comments"><?php previous_comments_link( esc_html__( 'Older Comments', 'listygo' ) ); ?></li>
                    <li class="newer-comments"><?php next_comments_link( esc_html__( 'Newer Comments', 'listygo' ) ); ?></li>
                </ul>
            </nav>
        <?php endif;?>
    </div>
    <?php endif;?>
    
    <?php if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
        <p class="comments-closed"><?php esc_html_e( 'Comments are closed.', 'listygo' ); ?></p>
    <?php endif; ?>

    <?php
        // Start displaying Comment Form
        $rdtheme_commenter = wp_get_current_commenter();		
        $rdtheme_req = get_option( 'require_name_email' );
        $rdtheme_aria_req = ( $rdtheme_req ? " required" : '' );

        $rdtheme_fields =  array(
            '<div class="row">
                <div class="col-sm-6"><div class="form-group">',
                    'author' => '
                    <input id="author" class="form-control form-input" name="author" value="' . esc_attr( $rdtheme_commenter['comment_author'] ) . '" type="text" placeholder="'.esc_attr__( 'Type your name...', 'listygo' ).'" size="30"' . $rdtheme_aria_req . '/>
                </div></div>
                <div class="col-sm-6"><div class="form-group">',
                    'email' => '
                    <input id="email" class="form-control form-input" name="email" value="' . esc_attr(  $rdtheme_commenter['comment_author_email'] ) . '" type="email" placeholder="'.esc_attr__( 'Type your email...', 'listygo' ).'" size="30"' . $rdtheme_aria_req . '/>
                </div></div>
            </div>',
        );

        $rdtheme_args = array(
            'submit_field'  => '<div class="form-submit">%1$s %2$s</div>',
            'title_reply'   => esc_html__( 'Leave a Comment', 'listygo' ),
            'submit_button' => '<div class="form-btn"><button type="submit" class="submit-btn">'.esc_attr__( 'Post Comment', 'listygo' ).'</button></div>',
            'comment_field' =>  '<div class="form-group comment-form-comment"><textarea id="comment" name="comment" required placeholder="'.esc_attr__( 'Your Comment *', 'listygo' ).'" class="textarea form-control" rows="6" cols="20"></textarea></div>',
            'fields' => apply_filters( 'comment_form_default_fields', $rdtheme_fields ),
    );
    ?>
    <div class="reply-separator"></div>
    <?php comment_form( $rdtheme_args );?>
</div>