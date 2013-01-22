<?php
class brdi_Portal_Component_Twitter extends brdi_Portal_Component
{
	public function getTimelineTweets($user)
	{
		$twitter = new brdi_Api_Rest("GET", "http://twitter.com/statuses/user_timeline.xml?id={$user}");
		var_dump($twitter);
		return $twitter;
	}
}
?>