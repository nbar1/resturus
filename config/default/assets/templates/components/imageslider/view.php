<div class="slider-wrapper theme-default">
	<div id="slider" class="nivoSlider">
		!{loop://images/}
			<img src="/!{loopvar://images/[*]/}" />
		!{endloop://images/}
	</div>
</div>
<script>
    $("#slider").nivoSlider();
</script>