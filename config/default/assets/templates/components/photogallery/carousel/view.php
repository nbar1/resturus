<div id="myCarousel" class="carousel slide">
	<ol class="carousel-indicators">
    	!{loop://images/}
			<li data-target="#myCarousel" data-slide-to="!{loopvar://images/[*]/x/}" !{if://images/[*]/active/}class="active"!{endif://images/[*]/active/}></li>
        !{endloop://images/}
	</ol>
	<!-- Carousel items -->
	<div class="carousel-inner">
    	!{loop://images/}
			<div class="item!{if://images/[*]/active/} active!{endif://images/[*]/active/}">
				<img src="/!{loopvar://images/[*]/src/}"/>
			</div>
        !{endloop://images/}
	</div>
	<!-- Carousel nav -->
	<a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
	<a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a>
</div>