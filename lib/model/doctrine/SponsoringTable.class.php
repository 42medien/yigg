<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class SponsoringTable extends Doctrine_Table
{

  /**
   * Retrieve all Sponsorings for a SponsoringPlace if they are active
   *
   * @param place_id the id of the sponsering place you wish to check
   *
   * @return Doctrine_Collection
   */
  public static function getSponsoringsForPlace( $place_id , $count = false , $hydrationMode = null )
  {
    $query = Doctrine_Query::create();
    $query->select('s.*, d.*, si.*')
            ->from('Sponsoring s')
            ->innerJoin('s.Deal d')
            ->leftJoin('s.Image si')
            ->where('s.place_id = ?', $place_id)
            ->addWhere('s.payed = ? AND d.payed = ?', array(true,true))
            ->addWhere('s.expires > NOW()');

    if(true === $count)
    {
      return $query->count();
    }

    $sponsorings = $query->execute( array(), $hydrationMode );
    return $sponsorings;
  }

  public function retrieveBackendSponsoringList(Doctrine_Query $q)
  {
    $rootAlias = $q->getRootAlias();
    $q->innerJoin($rootAlias . '.SponsoringPlace sp');
    return $q;
  }

}
