<div id="fb-root"></div>
<script src="http://connect.facebook.net/en_US/all.js"></script>

<p>
    <input type="button"
           onclick="sendRequestViaMultiFriendSelector(); return false;"
           value="Send Request to Many Users with MFS"
        />
</p>

<script>
    FB.init({
        appId  : <?php echo sfConfig::get('app_facebook_app_id') ?>,
        frictionlessRequests: true
    });

    function sendRequestViaMultiFriendSelector() {
        FB.ui({method: 'apprequests',
            message: 'My Great Request'
        }, requestCallback);
    }

    function requestCallback(response) {
        console.log("ok");
    }
</script>