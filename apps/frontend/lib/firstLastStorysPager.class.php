<?php

class firstLastStorysPager extends Doctrine_Pager_Layout
{
  public function display( $options = array(), $display = false )
  {

    $pager = $this->getPager();


    $str = '<div class="pagination"><ul>';

    // First page
    $this->addMaskReplacement('title', '&laquo;', true);
    $this->addMaskReplacement('class', 'class="prev"', true);
    $this->setTemplate('<li {%class}><a href="{%url}" rel="previous" title="Betrachte Seite {%page}">{%title}</a></li>');
    $this->setSelectedTemplate('<li {%class}><a href="{%url}"  title="Betrachte Seite {%page}">{%title}</a></li>');

    $options['page_number'] = $pager->getPreviousPage();
    $str .= $this->processPage($options);

    // Pages listing
    $this->addMaskReplacement('title', null, true);
    $this->addMaskReplacement('class', null, true);
    $this->setTemplate('<li {%class}><a href="{%url}" title="Betrachte Seite {%page}">{%page}</a></li>');
    $this->setSelectedTemplate('<li class="active"><a href="{%url}" title="Betrachte Seite {%page}">{%page}</a></li>');

    $str .= parent::display($options, true);

    // Last page
    $this->addMaskReplacement('title', '&raquo;', true);
    $this->addMaskReplacement('class', 'class="next"', true);
    $this->setTemplate('<li {%class}><a href="{%url}" rel="next" title="Betrachte Seite {%page}">{%title}</a></li>');
    $this->setSelectedTemplate('<li {%class}><a href="{%url}" title="Betrachte Seite {%page}">{%title}</a></li>');
    $options['page_number'] = $pager->getNextPage();
    $str .= $this->processPage($options);

    if(true === $this->getPager()->haveToPaginate() )
    {
      echo $str . "</ul></div>";
    }
  }
}