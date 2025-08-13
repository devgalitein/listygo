<?php

namespace Rtrsp\Helpers;
  
class Functions {  
    public static function archive_page_cats() { 
        $pro_label = '';
        
        return apply_filters('rtrs_archive_page_cats', array(
            'article' => esc_html__("Article", 'review-schema-pro'), 
            'news_article' => esc_html__("News article", 'review-schema-pro'), 
            'blog_posting' => esc_html__("Blog posting", 'review-schema-pro'), 
            'event' => esc_html__("Event", 'review-schema-pro'),  
            'local_business' => esc_html__("Local business", 'review-schema-pro'), 
            'movie' => esc_html__("Movie", 'review-schema-pro'), 
            'audio' => esc_html__("Audio", 'review-schema-pro'), 
            'video' => esc_html__("Video", 'review-schema-pro'), 
            'breadcrumb' => esc_html__("Breadcrumb", 'review-schema-pro'),  
            'itemlist' => esc_html__("ItemList", 'review-schema-pro'),  
            'product' => esc_html__("Product", 'review-schema-pro') . $pro_label, 
            'course' => esc_html__("Course", 'review-schema-pro') . $pro_label,    
            'job_posting' => esc_html__("Job posting", 'review-schema-pro') . $pro_label, 
            'recipe' => esc_html__("Recipe", 'review-schema-pro') . $pro_label,
            'faq' => esc_html__("Faq Page", 'review-schema-pro') . $pro_label,  
            'question_answer' => esc_html__("Q&A Page", 'review-schema-pro') . $pro_label,            
            'how_to' => esc_html__("How to", 'review-schema-pro') . $pro_label,            
            'software_app' => esc_html__("Software App", 'review-schema-pro') . $pro_label,
            'image_license' => esc_html__("Image License", 'review-schema-pro') . $pro_label,
            'special_announcement' => esc_html__("Special announcement", 'review-schema-pro') . $pro_label,    
            'how_to' => esc_html__("How to", 'review-schema-pro') . $pro_label, 
        ));
    }

    public static function check_license() {
        return apply_filters('rtrs_check_license', true);
    }
}
