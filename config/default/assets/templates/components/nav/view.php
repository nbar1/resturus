<div id="mainnav" class="navbar navbar navbar-fixed-top">
	<div class="navbar-inner">
		<a class="brand hidden-phone" href="/">!{token://clientName}</a>
		<a class="brand visible-phone">!{token://page/title}</a>
		<ul class="nav hidden-phone">
			!{token://pageNav}
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
				!{token://mobileNav}
			</ul>
		</div>
	</div>
</div>