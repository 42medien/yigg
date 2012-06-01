<?php
  $path = sfConfig::get('sf_relative_url_root',
    preg_replace('#/[^/]+\.php5?$#', '', isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : (isset($_SERVER['ORIG_SCRIPT_NAME']) ? $_SERVER['ORIG_SCRIPT_NAME'] : ''))
  );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

  <head>

    <link rel="shortcut icon" href="favicon.ico" />

    <!-- metas -->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="title" content="YiGG.de - Nachrichten zum Mitmachen" />

    <meta name="robots" content="index, follow" />
    <meta name="description" content="Yigg.de" />
    <meta name="language" content="de" />
    <title>Passwort zurückgesetzt</title>
  </head>
<body>
        <p>Hallo <strong><?php echo $ResetUser->username; ?></strong>,</p>
        <p>Bitte klicke auf den folgenden Link, um Dein Passwort zurückzusetzen.</p>
        <p>
          <?php
            $url  =  'http://' . $sf_request->getHost() . $sf_request->getRelativeUrlRoot(). DIRECTORY_SEPARATOR;
            $url .= "user" .DIRECTORY_SEPARATOR . "changepassword" . DIRECTORY_SEPARATOR . 'key'. DIRECTORY_SEPARATOR;
            $url .= $ResetPasswordKey;
            echo '<a href="'. $url . '" title="Dein Klick auf diesen Link setzt Dein Passwort zurück.">'. $url . '</a>';
          ?>
        </p>
        <p>Diese email wurde automatisch generiert - bitte nicht an diese E-Mail-Adresse antworten.</p>
        <p>Bei Problemen wende Dich bitte an das YiGG-Team: <a href="mailto:info@yigg.de">info@yigg.de</a></p>
        <p>Viele Grüsse!</p>
        <p>YiGG GmbH </p>
    </body>
</html>