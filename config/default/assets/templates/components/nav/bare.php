<div id="mainnav" class="navbar navbar visible-phone">
	<div class="navbar-inner">
		<a class="brand visible-phone">!{token://page/title/}</a>
		<button type="button" class="btn btn-navbar visible-phone" data-toggle="collapse" data-target="#phone_nav">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
	</div>
	<div id="phone_nav" class="nav-collapse collapse visible-phone">
		<div class="nav_phone ">
			<ul class="nav">
				!{loop://nav/}
					<li class="!{loopvar://nav/[*]/active_class/}"><a href="!{loopvar://nav/[*]/href/}" class="!{loopvar://nav/[*]/class/}">!{loopvar://nav/[*]/title/}</a></li>
				!{endloop://nav/}
			</ul>
		</div>
	</div>
</div>
<div class="pill_nav">
	<ul class="nav nav-pills">
		!{loop://nav/}
		<li class="!{loopvar://nav/[*]/active_class/}">
			<a href="!{loopvar://nav/[*]/href/}" class="!{loopvar://nav/[*]/class/}">!{loopvar://nav/[*]/title/}</a>
		</li>
		!{endloop://nav/}
	</ul>
</div>