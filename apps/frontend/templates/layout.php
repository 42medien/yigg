<!DOCTYPE html>
<html lang="de-DE" prefix="og: http://ogp.me/ns# article:http://ogp.me/ns/article#">
  <head>
    <?php include_partial("global/header"); ?>
  </head>
  <body class="<?php echo ( $sf_user->isAuthenticated() ? "user-auth" : "user-anon" ); ?> module-<?php echo $sf_context->getModuleName(); ?> action-<?php echo $sf_context->getActionName(); ?>">
    <div id="page">
      <header id="branding">
        <?php include_partial("system/header"); ?>
      </header>

      <div id="main">
        <main id="content" class="h-entry hentry page" itemscope itemtype="http://schema.org/WebPage">
          <?php if (has_slot("page-title")) { ?>
            <h1 class="page-title p-name entry-title" itemprop="name headline"><?php include_slot("page-title"); ?></h1>
          <?php } ?>

          <article class="e-description entry-description" itemprop="description text">
            <?php echo $sf_data->getRaw('sf_content'); ?>
          </article>

          <?php if (has_slot("page-sponsoring")) { ?>
            <?php include_slot("page-sponsoring"); ?>
          <?php } ?>
        </main>

        <aside id="sidebar">
          <?php include_partial("system/sidebar"); ?>
        </aside>

      </div>

      <footer class="site-footer">
        <?php include_partial("system/footer");?>
      </footer>
    </div>
  </body>
</html>