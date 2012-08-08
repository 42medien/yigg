
<div id="fb-root"></div>
<script src="http://connect.facebook.net/en_US/all.js"></script>
<p>
    <input type="button"
           onclick="sendRequestToRecipients(); return false;"
           value="Send Request to Users Directly"
        />
    <input type="text" value="100001579860431" name="user_ids" />
</p>
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

    function sendRequestToRecipients() {
        var user_ids = document.getElementsByName("user_ids")[0].value;
        FB.ui({method: 'apprequests',
            message: 'My Great Request',
            to: user_ids
        }, requestCallback);
    }

    function sendRequestViaMultiFriendSelector() {
        FB.ui({method: 'apprequests',
            message: 'My Great Request'
        }, requestCallback);
    }

    function requestCallback(response) {
        // Handle callback here
    }
</script>