<?php
class RedirectLog extends BaseRedirectLog
{
  public static function log($request, $redirect)
  {
    $count = Doctrine_Query::create()
         ->from("RedirectLog")
         ->where("ip_crc32 = ?", crc32($request->getRemoteAddress()))
         ->addWhere("redirect_id = ?", $redirect->id)
         ->count();

    if($count > 0) // Only log once.
    {
      return;
    }
    $langs = $request->getLanguages();

    $log = new RedirectLog();
    $log->fromArray(array(
      "language" => $langs[0],
      "referer"  => is_null($request->getReferer()) ? null : $request->getReferer(),
      "user_agent" => substr($request->getHttpHeader("USER_AGENT"), 0, 128),
      "redirect_id" => $redirect->id,
      "ip_crc32" => crc32($request->getRemoteAddress())));

    $log->trySave();
    $log->autoYigg($request, $redirect);
  }

  public function autoYigg($request, $redirect)
  {
    $redirect_cnt = Doctrine::getTable("RedirectLog")->findByRedirectId($redirect->id)->count();
    if($redirect_cnt%5 !== 0)
    {
      return;
    }
    $story = Doctrine::getTable("Story")->findOneByExternalUrl($redirect->url);
    if($story === false)
    {
      return;
    }
    $rating = new Rating();
	  $rating->ip_address = "127.0.0.3";
	  $rating->user_agent = sprintf("Mug.im Autoyigg");
    $story->Ratings->add($rating);
    $story->save();
  }
}
?>