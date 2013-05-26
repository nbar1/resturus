<ul class="nav nav-tabs hidden-phone">
!{loop://tabs/}
	<li!{if://tabs/[*]/active/} class="active"!{enfif://tabs/[*]/active/}>
		<a data-toggle="tab" href="#tabbed_!{token://tabs/[*]/x/}">!{html://tabs/[*]/title/}</a>
	</li>
!{endloop://tabs/}
</ul>
<div class="tab-content">
!{loop://tabs/}
	<div class="tab-pane tpb!{if://tabs/[*]/active/} active!{endif://tabs/[*]/active/}" id="tabbed_!{token://tabs/[*]/x/}">
		<div class="visible-phone tab_title">!{html://tabs/[*]/title/}</div>
		!{html://tabs/[*]/content/}
	</div>
!{endloop://tabs/}
	</div>
</div>