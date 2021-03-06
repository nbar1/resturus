<div id="mainnav" class="navbar navbar navbar-fixed-top" data-spy="affix">
	<div class="navbar-inner">
		<a class="brand hidden-phone" href="/">!{token://client/name/}</a>
		<a class="brand visible-phone">!{token://page/title/}</a>
		<ul class="nav hidden-phone">
			!{loop://nav/}
				<li class="divider-vertical !{loopvar://nav/[*]/first/}"></li>
				<li class="!{loopvar://nav/[*]/active_class/}"><a href="!{loopvar://nav/[*]/href/}" class="!{loopvar://nav/[*]/class/}">!{loopvar://nav/[*]/title/}</a></li>
			!{endloop://nav/}
			<li class='divider-vertical'></li>
		</ul>
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