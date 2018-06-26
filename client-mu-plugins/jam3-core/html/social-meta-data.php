<meta property="og:locale" content="<?php echo esc_attr( apply_filters( 'jam3_core__social_meta_data__og_locale', 'en_US' ) ); ?>">
<meta property="og:title" content="<?php echo esc_attr( $title ); ?>"/>
<meta property="og:description" content="<?php echo esc_attr( $desc ); ?>"/>
<meta property="og:type" content="<?php echo esc_attr( $content_type ); ?>"/>
<meta property="og:url" content="<?php echo esc_url( $url ); ?>"/>
<meta property="og:site_name" content="<?php echo esc_attr( $title ); ?>"/>
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image" content="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/images/<?php echo esc_attr( self::get_social_media_image() ); ?>">
<meta name="twitter:card" content="summary">
