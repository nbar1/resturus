<div id="myCarousel" class="carousel !{token://transition/}">
	!{if://show_indicators/}
		<ol class="carousel-indicators">
			!{loop://images/}
				<li data-target="#myCarousel" data-slide-to="!{loopvar://images/[*]/x/}" !{if://images/[*]/active/}class="active"!{endif://images/[*]/active/}></li>
			!{endloop://images/}
		</ol>
	!{endif://show_indicators/}
	<!-- Carousel items -->
	<div class="carousel-inner">
		!{loop://images/}
			<div class="item!{if://images/[*]/active/} active!{endif://images/[*]/active/}">
				<img src="/!{loopvar://images/[*]/src/}"/>
			</div>
		!{endloop://images/}
	</div>
	!{if://show_arrows/}
		<!-- Carousel nav -->
		<a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
		<a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a>
	!{endif://show_arrows/}
</div>