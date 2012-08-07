
<div id="fb-root"></div>
<script src="http://connect.facebook.net/en_US/all.js">
</script>
<script>
    FB.init({
        appId:<?php echo sfConfig::get('app_facebook_app_id') ?>,
        cookie:true,
        status:true,
        xfbml:true
    });

    FB.ui({ method: 'apprequests',
        message: 'Here is a new Requests dialog...'});
</script>