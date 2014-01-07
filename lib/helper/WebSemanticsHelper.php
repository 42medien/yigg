<?php
/**
 * Prints a set of <meta> tags according to the response attributes,
 * to be included in the <head> section of a HTML document.
 *
 * <b>Examples:</b>
 * <code>
 *  include_metas();
 *    => <meta name="title" content="symfony - open-source PHP5 web framework" />
 *       <meta name="robots" content="index, follow" />
 *       <meta name="description" content="symfony - open-source PHP5 web framework" />
 *       <meta name="keywords" content="symfony, project, framework, php, php5, open-source, mit, symphony" />
 *       <meta name="language" content="en" /><link href="/stylesheets/style.css" media="screen" rel="stylesheet" type="text/css" />
 * </code>
 *
 * <b>Note:</b> Modify the view.yml or use sfWebResponse::addMeta() to change, add or remove metas.
 *
 * @return string XHTML compliant <meta> tag(s)
 * @see    include_http_metas
 * @see    sfWebResponse::addMeta()
 */
function include_semantic_metas() {
  $context = sfContext::getInstance();
  $i18n = sfConfig::get('sf_i18n') ? $context->getI18N() : null;
  foreach ($context->getResponse()->getMetas() as $name => $content) {
    if (substr($name, 0, 3) == "og:") {
      echo tag('meta', array('property' => $name, 'name' => $name, 'content' => null === $i18n ? $content : $i18n->__($content)))."\n";
    } else {
      echo tag('meta', array('name' => $name, 'content' => null === $i18n ? $content : $i18n->__($content)))."\n";
    }
  }
}