
<div id="fb-root"></div>
<script src="http://connect.facebook.net/en_US/all.js">
</script>
<script>
    window.fbAsyncInit = function() {
        FB.init({
            appId        : <?php echo sfConfig::get('app_facebook_app_id') ?>,
            status       : false,
            cookie       : true,
            xfbml        : true,
            oauth        : true
        });
    };

    // Load the SDK Asynchronously
    (function(d){
        var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement('script'); js.id = id; js.async = true;
        js.src = "//connect.facebook.net/en_US/all.js";
        ref.parentNode.insertBefore(js, ref);
    }(document));

    FB.ui({ method: 'apprequests',
        message: 'Here is a new Requests dialog...'});
</script>