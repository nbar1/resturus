!{if://wrapper/prop/row/endfirst/}</div>!{endif://wrapper/prop/row/endfirst/}
!{if://wrapper/prop/row/start/}<div class="row-fluid">!{endif://wrapper/prop/row/start/}

<div class="component visible-phone !{token://wrapper/prop/component/class/}">
	!{if://wrapper/prop/component/showtitle/}
		<div class="comp_header">!{token://wrapper/prop/component/title}</div>
	!{endif://wrapper/prop/component/showtitle/}
	<div class="comp_body">
		!{template://component/}
	</div>
</div>
!{if://wrapper/prop/row/end/}</div>!{endif://wrapper/prop/row/end/}