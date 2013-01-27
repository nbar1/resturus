<?php
class brdi_Portal_Component_Twitter extends brdi_Portal_Component
{
	private $oauth;
	public $config;
	
	protected $_brdi_Portal_Component_Twitter = array(
		'auth' => array(
			'consumer_key' => 's1FJp5hbIJGl4QKTM86TzA',
			'consumer_secret' => 'qWTvI8BOPfxujgs6BXmIEYV3oBkXLLG5Ey9u1GA856A',
			'oauth_access_token' => '1069974367-ydEbIYFy26ZmRY7ENaWDjNjaGflEL6Hv5AGlyYg',
			'oauth_access_token_secret' => 'viqukhvBEz4dJiSLpZSoxsnWAtqdkKCXW7oa4ilYoU',
		),
		'user' => 'resturus',
		'limit' => 10,
		'show_twitter_logo' => true,
		'exclude_replies' => true,
		'scroll_tweets' => true,
		'assets' => array(
			'stylesheets' => array(
				'assets/stylesheets/components/twitter/timeline.css',
			),
		),
		'columns' => 6,
		'offset' => 0,
		'class' => '',
	);

	public function build($config)
	{
		$this->config = array_merge($this->_brdi_Portal_Component_Twitter, $config['config'], array('type' => $config['type']));

		if($this->config['scroll_tweets'] === true)
		{
			$this->config['class'] .= " scroll";
		}

		$response = $this->getTimelineTweets($this->config['user']);
		$tweets = array_slice($response, 0, $this->config['limit']);

		// set component assets
		$this->setAllComponentJavascripts($this->config);
		$this->setAllComponentStylesheets($this->config);

		$template = $this->getComponentTemplate($this->config);

		$template = $this->parseToken($template, "token://tweets", $this->buildTimeline($tweets));

		$template = $this->buildComponentWrapper($template, $this->config);

		return array(array($this->javascripts, $this->stylesheets), $template);
		
	}






	private function buildTimeline($tweets)
	{
		$all_tweets = "";
		if($this->config['show_twitter_logo'] === true)
		{
			$all_tweets .= "<div class='twitter_logo'></div>";
		}
		$x=0;
		foreach($tweets as $tweet)
		{
			$tweet_text = $this->buildTweetEntities($tweet);
			$tweet_user_screen_name = $tweet['user']['screen_name'];
			$tweet_user_display_name = $tweet['user']['name'];
			$tweet_user_image = $tweet['user']['profile_image_url'];
			
			// create date
			$tweet_created = $tweet['created_at'];
			if(date("Ymd", strtotime($tweet_created)) == date("Ymd"))
			{
				$tweet_created = date("g:ia", strtotime($tweet_created));
			}
			elseif(date("Y", strtotime($tweet_created)) == date("Y"))
			{
				$tweet_created = date("M j", strtotime($tweet_created));				
			}
			else
			{
				$tweet_created = date("M j Y", strtotime($tweet_created));
			}
			
			
			$tweet_html = "";
			if($x==0)
			{
				$tweet_html .= "<div class='tweet_user'>";
				$tweet_html .= "<div class='tweet_user_image'><img src='{$tweet_user_image}'></div>";
				$tweet_html .= "<div class='tweet_user_name'>";
				$tweet_html .= "<div class='tweet_user_display_name'><a href='http://twitter.com/{$tweet_user_screen_name}' target='_blank'>{$tweet_user_display_name}</a></div>";
				$tweet_html .= "<div class='tweet_user_screen_name'><a href='http://twitter.com/{$tweet_user_screen_name}' target='_blank'>@{$tweet_user_screen_name}</a></div>";
				$tweet_html .= "</div></div>";
				$tweet_html .= "<div class='tweets_all'>";
				$tweet_html .= "<div class='tweet highlight_tweet'>";
			}
			else {
				$tweet_html .= "<div class='tweet'>";				
			}
			$tweet_html .= "<div class='tweet_text'>{$tweet_text}</div>";
			$tweet_html .= "<div class='tweet_created'>{$tweet_created}</div>";
			$tweet_html .= "</div>";
			$all_tweets .= $tweet_html;
			$x++;
		}
		$all_tweets .= "</div>";
		return $all_tweets;
	}
	
	private function buildTweetEntities($tweet)
	{
		$tweet_text = $tweet['text'];
		// hashtags
		foreach($tweet['entities']['hashtags'] as $hashtag)
		{
			$tweet_text = str_replace("#{$hashtag['text']}", "<a class='twitter_tweet_entity' href='https://twitter.com/search?q=%23{$hashtag['text']}' target='_blank'>#{$hashtag['text']}</a>", $tweet_text);
		}
		// user mentions
		foreach($tweet['entities']['user_mentions'] as $mention)
		{
			$tweet_text = str_replace("@{$mention['screen_name']}", "<a class='twitter_tweet_entity' href='https://twitter.com/{$mention['screen_name']}' target='_blank'>@{$mention['screen_name']}</a>", $tweet_text);
		}
		// urls
		$tweet_text = preg_replace("|(http://t\.co\/\w+)|", "<a class='twitter_tweet_entity' target='_blank' href='$1'>$1</a>", $tweet_text);

		return $tweet_text;
	}


	private function getTimelineTweets($user)
	{
		$this->oauth = array(
			'oauth_consumer_key' => $this->config['auth']['consumer_key'],
			'oauth_nonce' => time(),
			'oauth_signature_method' => 'HMAC-SHA1',
			'oauth_token' => $this->config['auth']['oauth_access_token'],
			'oauth_timestamp' => time(),
			'oauth_version' => '1.0',
			'screen_name' => $user,
			'exclude_replies' => $this->config['exclude_replies'],
		);

		$base_info = $this->buildBaseString("https://api.twitter.com/1.1/statuses/user_timeline.json", "GET", $this->oauth);
		$composite_key = rawurlencode($this->config['auth']['consumer_secret']) . '&' . rawurlencode($this->config['auth']['oauth_access_token_secret']);
		$oauth_signature = base64_encode(hash_hmac('sha1', $base_info, $composite_key, true));
		$this->oauth['oauth_signature'] = $oauth_signature;
		
		$header = array($this->buildOAuthHeader(), 'Expect:');
		$options = array(
			CURLOPT_HTTPHEADER => $header,
			CURLOPT_HEADER => false,
			CURLOPT_URL => "https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=".$user."&exclude_replies=".$this->config['exclude_replies'],
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYPEER => false,
		);

		$twitter = new brdi_Api_Rest($options, true);
		$tweets = $twitter->initialize();
		return $tweets;
	}

	private function buildBaseString($baseURI, $method, $params)
	{
		$r = array();
		ksort($params);
		foreach($params as $k=>$v)
		{
			$r[] = "$k=" . rawurlencode($v);
		}
		return $method."&" . rawurlencode($baseURI) . '&' . rawurlencode(implode('&', $r));
	}

	private function buildOAuthHeader()
	{
		$header = 'Authorization: OAuth ';
		$values = array();
		foreach($this->oauth as $k=>$v)
		{
			$values[] = "$k=\"" . rawurlencode($v) . "\"";
		}
		$header .= implode(', ', $values);
		return $header;
	}
}
?>