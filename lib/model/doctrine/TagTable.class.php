<?php

/**
 *
 * @package     yigg
 * @subpackage  story
  */
class TagTable extends Doctrine_Table
{
  /**
   * return instance of current table
   *
   * @return Doctrine_Table
   */
  public static function getTable()
  {
    return Doctrine::getTable('Tag');
  }

  /**
   * returns the tags and their story counts.
   * @param $story_id
   * @param $hydration_mode
   * @return Doctrine_Collection
   */
  public function getStoryCountsForStoryTags($story_id,$hydration_mode = Doctrine::HYDRATE_ARRAY)
  {
    return
      $this->getQueryObject()
      ->select("t.*, (SELECT COUNT(DISTINCT(st2.story_id)) FROM StoryTag st2 WHERE st2.tag_id = t.id) as cnt")
      ->from("Tag t")
      ->innerJoin("t.StoryTag st")
      ->where("st.story_id = ?", $story_id)
      ->execute(array(), $hydration_mode);
  }

  /**
   * returns the tags and their spec counts.
   * @param $story_id
   * @param $hydration_mode
   * @return Doctrine_Collection
   */
  public function getStoryCountsForSpeculationTags($spec_id,$hydration_mode = Doctrine::HYDRATE_ARRAY)
  {
    return
      $this->getQueryObject()
      ->select("t.*, (SELECT COUNT(DISTINCT(st2.speculation_id)) FROM SpeculationTag st2 WHERE st2.tag_id = t.id) as cnt")
      ->from("Tag t")
      ->innerJoin("t.SpeculationTag st")
      ->where("st.speculation_id = ?", $spec_id)
      ->execute(array(), $hydration_mode);
  }

  /**
   * Returns the counts from the tokens provided. using output escaper as wrapper
   * as this allows caching in template.
   *
   * @param sfOutputEscaper tokens
   * @return array
   */
  public function getStoryCountsForTags( $tokens, $hydration_mode = Doctrine::HYDRATE_ARRAY)
  {
    $query = $this->getQueryObject()
      ->select("t.*, (SELECT COUNT(DISTINCT(st.story_id)) FROM StoryTag st WHERE st.tag_id = t.id) as story_count")
      ->from("Tag t")
      ->whereIn("t.name",$tokens->getRawValue());
    return $query->execute(array(), $hydration_mode);
  }

  /**
   * Returns the counts from the tokens provided. using output escaper as wrapper
   * as this allows caching in template.
   *
   * @param sfOutputEscaper tokens
   * @return array
   */
  public function getTagsByCount( $limit = 200, $hydration_mode = Doctrine::HYDRATE_ARRAY ) {
    $query = $this->getQueryObject()
      ->select("t.name, (SELECT COUNT(st.tag_id) FROM StoryTag st WHERE st.tag_id = t.id) as tag_count")
      ->from("Tag t")
      ->orderBy("tag_count DESC")
      ->limit($limit);
    return $query->execute(array(), $hydration_mode);
  }


  /**
   * Returns the results of for the tag cloud via a topN function with
   * linear interpolation on the datasets limited by
   * - this tags stories and an n search of other stories related to this story by the same tag.
   * - popularity based on the results of the dataset compared to the maxs/counts
   * - limited by a cut off so we don't get too many results.
   *
   * @param Story $story
   * @return Array of Tags with count and popularity.
   */
  public static function getTagsWithPopularityFromStory( $story )
  {
    $query = new Doctrine_RawSql();
    $query->setHydrationMode( Doctrine::HYDRATE_ARRAY );
    $query->select('{t.*}, {t.popularity}, {t.count}')
        ->addComponent('t','Tag')
        ->from(
          sprintf(
            '
             (
             SELECT
              DISTINCT( data.id ),
              data.name,
              -- perform the popularity function using a topN function and linear interpolation.
              ( 2 * ( 1.0 + ( 2.5 * data.count - data.max  / 2 ) / data.max  ) -1 ) as popularity,
              data.count
             FROM
              (
                -- calculate the statistics from our result set for the popularity functions
                SELECT
                  tag.*,
                  stats.count,
                  (
                    SELECT
                      MAX( tmp.count ) as story_id
                    FROM
                      (
                        SELECT
                          distinct (tag_id),
                          count(story_id) AS count
                        FROM
                          story_tag
                        GROUP BY
                          tag_id
                      ) as tmp
                  ) as max

                FROM tag

                -- Subquery for generating the related tags.
                INNER JOIN
                  (
                    SELECT
                      DISTINCT( tag_id )
                    FROM
                      story_tag
                    INNER JOIN
                     (
                       -- find the related stories and which tags they have that match this stories tags.
                       SELECT
                         DISTINCT ( story_tag.story_id )
                       FROM
                         story_tag
                       INNER JOIN
                         (
                           SELECT
                             *
                           FROM
                             `story_tag`
                           WHERE story_id = %s
                         ) as data on data.tag_id = story_tag.tag_id
                      ) as data2 on story_tag.story_id = data2.story_id
                  ) as story_tags on tag.id = story_tags.tag_id

                -- get the "popularity" from these tags related to the set. (limited so more optimised)
                INNER JOIN
                  (
                    SELECT
                      DISTINCT(tag_id),
                      COUNT(story_id) AS count
                    FROM
                      story_tag
                    GROUP BY
                      tag_id
                  ) as stats on tag.id = stats.tag_id
                ) as data
             ) as t
              WHERE t.popularity > 0.3
              -- stops 1000s of tags, and makes our results more usefull.
             ',
         $story->id
         )
       );
     return $query->execute();
  }
}