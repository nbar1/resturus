<div class="span3 well" style="padding: 8px 0;">
	<ul class="nav nav-list">
		<li class="nav-header">Account</li>
		!{loop://nav/account/nav/}
			<li><a href="!{loopvar://nav/account/nav/[*]/href/}">!{loopvar://nav/account/nav/[*]/title/}</a></li>
		!{endloop://nav/account/nav/}

		<li class="nav-header">Client</li>
		!{loop://nav/client/nav/}
			<li><a href="!{loopvar://nav/client/nav/[*]/href/}">!{loopvar://nav/client/nav/[*]/title/}</a></li>
		!{endloop://nav/client/nav/}

		<li class="nav-header">Requests</li>
		!{loop://nav/requests/nav/}
			<li><a href="!{loopvar://nav/requests/nav/[*]/href/}">!{loopvar://nav/requests/nav/[*]/title/}</a></li>
		!{endloop://nav/requests/nav/}
	</ul>
</div>