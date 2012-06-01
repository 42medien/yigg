<?php

/**
 * spy actions.
 *
 * @package    yigg
 * @subpackage story
 */
class spyActions extends yiggActions
{
  public function preExecute()
  {
    parent::preExecute();

    $this->filters = array(
      'alles' => 'Alles',
      'nachrichten' => 'Neu eingestellt',
      'bewertungen' => 'Stimmen',
      'kommentare' => 'Kommentare',
      'gelesen' => 'Gerade gelesen',
    );

    $parameterHolder = $this->processFilters( sfContext::getInstance()->getRequest() );

    $internalUrl = htmlentities( strip_tags($parameterHolder->get('categoryUrl') ), ENT_NOQUOTES, 'UTF-8');
  }

  /**
   * Shows the Spy version for yigg
   *
   */
  public function executeSpy( $request )
  {
    // Execute the view with the filters.
    $query = $this->getDbView( $this->filter);
    if($query === false)
    {
      return sfView::ERROR;
    }

    $this->nodeList = $query->execute();

    $this->getResponse()->setSlot('sponsoring', $this->getComponent("sponsoring","sponsoring", array( 'place_id' => 5 ,'limit' => 1)));
    $this->getResponse()->setTitle( "YiGGspion: Live-Aktivität auf YiGG");
    $this->getResponse()->addMeta(
      "description",
      "Der YiGG-Spion hält dich auf dem laufenden darüber was gerade auf YiGG passiert."
    );
    $this->getResponse()->addMeta(
      'keywords',
      "YiGG-Spion, YiGG passiert, live-stream, live, feed, now, YiGG jetzt, jetzt"
    );
    return sfView::SUCCESS;
  }

  /**
   * Handles the ajax javascript updater.
   *
   * @return
   */
  public function executeLatestNodes($request)
  {
    if( true === $this->isAjaxRequest() )
    {
      // Execute the view with the filters.
      $lastEvent = $request->getParameter("timestamp");
      $timestamp = preg_replace( '/[^0-9.]/','', str_replace("-",".", $lastEvent));

      // get filter from r
      $filters = $request->getParameter('filter',"alles");
      if($timestamp)
      {
        // we use BC add becuase of the stupid PHP shit with adding floats or doubles. It can't perform
        // the addition due to no type casting.
        $query = $this->getDbView( $filters, bcadd( '0.00001' , $timestamp , 5) );
        $query->limit(10);

        $this->nodeList = $query->execute();
        if( count( $this->nodeList ) == 0 )
        {
          $response = $this->getResponse();
          $response->setStatusCode(304);
          $response->setHeaderOnly();
          $response->sendHttpHeaders();
          return sfView::HEADER_ONLY;
        }
        return sfView::SUCCESS;
      }
    }
    $this->setTemplate("spy");
    return sfView::ERROR;
  }

  /**
   * Gets the appropriate view syntax for execution.
   * @return sfView
   * @param $filter string the spy mode
   * @param $categories the category filter.
   */
  private function getDbView( $filter , $timestamp = null )
  {
    //  Generate the DQL
    $query = new Doctrine_RawSql();
    $query
      ->setHydrationMode(Doctrine::HYDRATE_ARRAY )
      ->select('{n.*}')
      ->addComponent('n','Node');

    switch( $filter )
    {
      // Show everything
      default:
      case 'alles':
        return $this->getLatestEvents( $query , $timestamp );
      break;

      // Only show new stories
      case 'nachrichten':
        return $this->getLatestStories( $query ,$timestamp );
      break;

      // Only show new ratings
      case 'bewertungen':
        return $this->getLatestRatings( $query ,$timestamp );
      break;

      // Only show comments
      case 'kommentare':
        return $this->getLatestComments( $query ,$timestamp );
      break;

      // Only show Story renders.
      case 'gelesen' :
        return $this->getLatestRenders( $query ,$timestamp );
      break;
    }
  }

  private function getLatestStories( $query, $timestamp = null )
  {
    $query->from(
          '(
             SELECT
               story.epoch_time as id,
               story.epoch_time,
               story.created_at,
               story.user_id,
               story.title AS  story_title,
               story.external_url AS story_external_url,
               story.internal_url AS story_internal_url,
               story.created_at AS story_created_at,
               story.user_id AS story_user_id,
               story.id  AS story_reference_id,
               (SELECT COUNT(story_rating.id) FROM story_rating WHERE story_rating.story_id = story.id) as currentRating,
               `user`.username username,
               "story" tablename
             FROM
               story
             INNER JOIN
               `user` ON story.user_id = `user`.id
             WHERE
              story.deleted_at IS NULL
                AND
              story.id > ( SELECT ( max(id) - 500) FROM story )
              ' . ( $timestamp ? ' AND story.epoch_time > ' . $timestamp : '') .'
             ORDER BY
               story.epoch_time DESC
             LIMIT 50
           ) as n
    ');
    return $query;
  }

  private function getLatestRatings( $query, $timestamp = null )
  {
    $query->from(
          '(
              SELECT
                rating.epoch_time AS id,
                rating.epoch_time AS epoch_time,
                rating.created_at AS created_at,
                story_rating.user_id AS user_id,
                story.title AS story_title,
                story.external_url AS story_external_url,
                story.internal_url AS story_internal_url,
               story.created_at AS story_created_at,
                story.user_id AS story_user_id,
                story.id AS story_reference_id,
                (SELECT COUNT(story_rating.id) FROM story_rating WHERE story_rating.story_id = story.id) as currentRating,
                `user`.username AS username,
                "yigg" AS tablename
              FROM
                story_rating
              INNER JOIN
                rating ON story_rating.rating_id = rating.id
              INNER JOIN
                story on story_rating.story_id = story.id
              INNER JOIN
                `user` on story_rating.user_id = `user`.id
              WHERE
                story.deleted_at IS NULL
                AND
                story_rating.user_id != 1
                AND
                story.id > ( SELECT ( max(id) - 500) FROM story )
                ' . ( $timestamp ? 'AND rating.epoch_time > ' . $timestamp : '') .'
              ORDER BY
                rating.epoch_time
              DESC LIMIT 50
           ) as n
    ');
    return $query;
  }

  private function getLatestComments( $query, $timestamp = null )
  {
    $query->from(
          '(
             SELECT
                comment.epoch_time AS id,
                comment.epoch_time AS epoch_time,
                comment.created_at AS created_at,
                comment.user_id AS user_id,
                story.title AS story_title,
                story.external_url AS story_external_url,
                story.internal_url AS story_internal_url,
               story.created_at AS story_created_at,
                story.user_id AS story_user_id,
                story.id AS story_reference_id,
                (SELECT COUNT(story_rating.id) FROM story_rating WHERE story_rating.story_id = story.id) as currentRating,
                `user`.username AS username,
                "comment" AS tablename
              FROM
                  comment
              INNER JOIN
                  story_comment on story_comment.comment_id = comment.id
              INNER JOIN
                  story on story.id = story_comment.story_id
              INNER JOIN
                  `user` on comment.user_id = `user`.id
              WHERE
                story.deleted_at IS NULL
                  AND

                story.id > ( SELECT ( max(id) - 500) FROM story )

                ' . ( $timestamp ? ' AND comment.epoch_time > ' . $timestamp : '') .'

              ORDER BY
                comment.epoch_time DESC
              LIMIT 50
           ) as n
    ');
    return $query;

  }

  private function getLatestRenders( $query, $timestamp = null )
  {
    $query->from(
          '(
             SELECT
              story_render.epoch_time AS id,
              story_render.epoch_time AS epoch_time,
              story_render.created_at AS created_at,
              story.user_id AS user_id,
              story.title AS story_title,
              story.external_url AS story_external_url,
              story.internal_url AS story_internal_url,
               story.created_at AS story_created_at,
              story.user_id AS story_user_id,
              story.id AS story_reference_id,
              (SELECT COUNT(story_rating.id) FROM story_rating WHERE story_rating.story_id = story.id) as currentRating,
              `user`.username AS username,
              "render" AS tablename
              FROM
                  story_render
              INNER JOIN
                  story on story.id = story_render.story_id
              INNER JOIN
                  `user` on story_render.user_id = `user`.id
              WHERE
                story_render.id > ( SELECT ( MAX(id) - 500) FROM story_render )
                  AND
                story.deleted_at IS NULL
                  AND
                story.id > ( SELECT ( MAX(id) - 500) FROM story )

                ' . ( $timestamp ? ' AND story_render.epoch_time > ' . $timestamp : '') .'
              ORDER BY
                  story_render.epoch_time
              DESC LIMIT 50
             ) as n
    ');
    return $query;

  }

  private function getLatestEvents( $query,  $timestamp = null )
  {
    $query->from(
        '(
          SELECT * FROM
           (
             SELECT
                comment.epoch_time AS id,
                comment.epoch_time AS epoch_time,
                comment.created_at AS created_at,
                comment.user_id AS user_id,
                story.title AS story_title,
                story.external_url AS story_external_url,
                story.internal_url AS story_internal_url,
               story.created_at AS story_created_at,
                story.user_id AS story_user_id,
                story.id AS story_reference_id,
                (SELECT COUNT(story_rating.id) FROM story_rating WHERE story_rating.story_id = story.id) as currentRating,
                `user`.username AS username,
                "comment" AS tablename
              FROM
                  comment
              INNER JOIN
                  story_comment on comment.id = story_comment.comment_id
              INNER JOIN
                  story on story_comment.story_id = story.id
              INNER JOIN
                  `user` on comment.user_id = `user`.id
              WHERE
                story.id > ( SELECT ( MAX(id) - 500) FROM story )
                  AND
                story.deleted_at IS NULL
                ' . ( $timestamp ? ' AND comment.epoch_time > ' . $timestamp : '') .'
              ORDER BY
                comment.epoch_time DESC
              LIMIT 50
           ) as data
           UNION
           (
            SELECT
               story.epoch_time as id,
               story.epoch_time,
               story.created_at,
               story.user_id,
               story.title AS story_title,
               story.external_url AS story_external_url,
               story.internal_url AS story_internal_url,
               story.created_at AS story_created_at,
               story.user_id AS story_user_id,
               story.id  AS story_reference_id,
               (SELECT COUNT(story_rating.id) FROM story_rating WHERE story_rating.story_id = story.id) as currentRating,
               `user`.username username,
               "story" tablename
             FROM
               story
             INNER JOIN
               `user` ON story.user_id = `user`.id
             WHERE
               story.id >  ( SELECT ( MAX(id) - 500) FROM story )
                AND
               story.deleted_at IS NULL

               ' . ( $timestamp ? ' AND story.epoch_time > ' . $timestamp : '') .'
             ORDER BY
               story.epoch_time DESC
             LIMIT 50
           )
           UNION
           (
            SELECT
                rating.epoch_time AS id,
                rating.epoch_time AS epoch_time,
                rating.created_at AS created_at,
                story_rating.user_id AS user_id,
                story.title AS story_title,
                story.external_url AS story_external_url,
                story.internal_url AS story_internal_url,
               story.created_at AS story_created_at,
                story.user_id AS story_user_id,
                story.id AS story_reference_id,
                (SELECT COUNT(story_rating.id) FROM story_rating WHERE story_rating.story_id = story.id) as currentRating,
                `user`.username AS username,
                "yigg" AS tablename
              FROM
                story_rating
              INNER JOIN
                rating ON story_rating.rating_id = rating.id
              INNER JOIN
                story on story_rating.story_id = story.id
              INNER JOIN
                `user` on story_rating.user_id = `user`.id
              WHERE
                story.deleted_at IS NULL
              AND
                story_rating.user_id != 1
              AND
                rating.created_at > DATE_SUB( NOW() , INTERVAL 1 DAY )
                ' . ( $timestamp ? 'AND rating.epoch_time > ' . $timestamp : '') .'
              ORDER BY
                rating.epoch_time
              DESC LIMIT 50
             )
           ORDER BY
             epoch_time DESC
           LIMIT 50
         )as n ORDER BY epoch_time DESC
    ');
    return $query;
  }
}