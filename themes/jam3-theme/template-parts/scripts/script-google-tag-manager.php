<?php
//vars
$code_id = prj_get_google_tag_manager_id();
?>
<script>!function(e,t,a,n,g){e[n]=e[n]||[],e[n].push({"gtm.start":(new Date).getTime(),event:"gtm.js"});var m=t.getElementsByTagName(a)[0],r=t.createElement(a);r.async=!0,r.src="https://www.googletagmanager.com/gtm.js?id=<?php echo esc_attr( $code_id ); ?>",m.parentNode.insertBefore(r,m)}(window,document,"script","dataLayer");</script>
