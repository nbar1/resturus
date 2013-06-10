<ul class="nav nav-tabs hidden-phone">
!{loop://tabs/}
	<li!{if://tabs/[*]/active/} class="active"!{endif://tabs/[*]/active/}>
		<a data-toggle="tab" href="#tabbed_!{loopvar://tabs/[*]/x/}">!{loopvar://tabs/[*]/title/}</a>
	</li>
!{endloop://tabs/}
</ul>
<div class="tab-content">
!{loop://tabs/}
	<div class="tab-pane tpb!{if://tabs/[*]/active/} active!{endif://tabs/[*]/active/}" id="tabbed_!{loopvar://tabs/[*]/x/}">
		<div class="visible-phone tab_title">!{loopvar://tabs/[*]/title/}</div>
		!{loopvar://tabs/[*]/content/}
	</div>
!{endloop://tabs/}
	</div>
</div>