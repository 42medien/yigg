<?php
class searchActions extends yiggActions
{
  public function executeSearch($request)
  {
    $search = $request->getParameter("q");
    $search = explode(" ",$search);
    $search_string = "";
    foreach($search as $term)
    {
      $search_string = "+$term ";
    }
    

    $query = Doctrine_Query::create()
             ->from('Story s')
             ->where('s.id IN (SELECT s2.id FROM StoryIndex s2 WHERE MATCH(s2.title, s2.description) AGAINST (? IN BOOLEAN MODE))', $search_string)
             ->orderBy('s.id DESC');

    $pager = $this->setPagerQuery($query, "/search?q={$request->getParameter("q")}");
    $this->stories = $pager->execute();
  }
}