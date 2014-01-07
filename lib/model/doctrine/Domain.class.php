<?php
class Domain extends BaseDomain
{
  public function isSubscriber($user)
  {
    $query = UserDomainSubscriptionTable::getSubscriberQuery($user->id, $this->id);
    return $query->count() === 1;
  }

  public function subscribe(User $user)
  {
    $subscription = new UserDomainSubscription();
    $subscription->user_id = $user->id;
    $subscription->domain_id = $this->id;
    $subscription->save();
  }

  public function unSubscribe(User $user)
  {
    $query = UserDomainSubscriptionTable::getSubscriberQuery($user->id, $this->id);
    $subscription = $query->execute();
    $subscription->delete();
  }

  public function updateStats()
  {
  	$this->stories = Doctrine_Query::create()
  	                 ->from("Story")
  	                 ->where("external_url LIKE 'http://$this->hostname%'")
  	                 ->count();

    $this->yiggs = Doctrine_Query::create()
    				         ->select("COUNT(r.id) as ratings, s.id")
  	                 ->from("Story s")
  	                 ->leftJoin("s.Ratings r")
  	                 ->where("s.external_url LIKE 'http://$this->hostname%'")
  	                 ->fetchOne()->ratings;

  	$this->distinct_users = Doctrine_Query::create()
  	                 ->select("COUNT(DISTINCT(user_id)) as users")
  	                 ->from("Story")
  	                 ->where("external_url LIKE 'http://$this->hostname%'")
  	                 ->fetchOne()->users;
  }
}
