<?php use_helper("Date"); ?>
<div id="sf_admin_container">
    <h1>User Statistics</h1>

    <div id="sf_admin_content">

        <fieldset>
            <div class="sf_admin_form_row">
                <div>
                    <label>Username</label>
                    <div class="content">
                        <?php echo $user->getUsername();?>
                    </div>
                </div>
            </div>
            <div class="sf_admin_form_row">
                <div>
                    <label>Email</label>
                    <div class="content">
                        <?php echo $user->getEmail();?>
                    </div>
                </div>
            </div>
            <div class="sf_admin_form_row">
                <div>
                    <label>YiGGed News</label>
                    <div class="content">
                        <?php echo $yigged_news;?>
                    </div>
                </div>
            </div>
            <div class="sf_admin_form_row">
                <div>
                    <label>Follower</label>
                    <div class="content">
                        <?php echo $follower;?>
                    </div>
                </div>
            </div>
            <div class="sf_admin_form_row">
                <div>
                    <label>Followees</label>
                    <div class="content">
                        <?php echo $followees;?>
                    </div>
                </div>
            </div>
            <div class="sf_admin_form_row">
                <div>
                    <label>Published News</label>
                    <div class="content">
                        <?php echo $published_news;?>
                    </div>
                </div>
            </div>
            <div class="sf_admin_form_row">
                <div>
                    <label>Last Published News </label>
                    <div class="content">
                        <?php echo $last_story_date?gmstrftime('%d. %B %Y %H:%M', strtotime($last_story_date)):"-"; ?>
                    </div>
                </div>
            </div>
            <div class="sf_admin_form_row">
                <div>
                    <label>Last Comment</label>
                    <div class="content">
                        <?php echo $last_comment_date?gmstrftime('%d. %B %Y %H:%M', strtotime($last_comment_date)):"-"; ?>
                    </div>
                </div>
            </div>
        </fieldset>
        <ul class="sf_admin_actions">
            <li class="sf_admin_action_list">
                <a href="<?php echo url_for('user') ?>">Back to list</a>
            </li>
        </ul>
    </div>

</div>