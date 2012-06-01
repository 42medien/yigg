<?php

class firstLastStorysPager extends Doctrine_Pager_Layout
{
  public function display( $options = array(), $display = false )
  {

    $pager = $this->getPager();


    $str = '<ul class="pagination">';

    // First page
    $this->addMaskReplacement('title', 'Zurück', true);
    $this->addMaskReplacement('class', 'class="zuruck"', true);
    $this->setTemplate('<li {%class}><a href="{%url}" rel="previous" title="Betrachte Seite {%page}">{%title}</a></li>');
    $this->setSelectedTemplate('<li {%class}><a href="{%url}"  title="Betrachte Seite {%page}">{%title}</a></li>');

    $options['page_number'] = $pager->getPreviousPage();
    $str .= $this->processPage($options);

    // Pages listing
    $this->addMaskReplacement('title',NULL, true);
    $this->addMaskReplacement('class',NULL, true);
    $this->setTemplate('<li {%class}><a href="{%url}" title="Betrachte Seite {%page}">{%page}</a></li>');
    $this->setSelectedTemplate('<li class="selected"><a href="{%url}" title="Betrachte Seite {%page}">{%page}</a></li>');

    $str .= parent::display($options, true);

    // Last page
    $this->addMaskReplacement('title', 'Weiter', true);
    $this->addMaskReplacement('class', 'class="weiter"', true);
    $this->setTemplate('<li {%class}><a href="{%url}" rel="next" title="Betrachte Seite {%page}">{%title}</a></li>');
    $this->setSelectedTemplate('<li {%class}><a href="{%url}" title="Betrachte Seite {%page}">{%title}</a></li>');
    $options['page_number'] = $pager->getNextPage();
    $str .= $this->processPage($options);

    if(true === $this->getPager()->haveToPaginate() )
    {
      echo $str . "</ul>";
    }
  }
}