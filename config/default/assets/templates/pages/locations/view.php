<div class="title_bar">
	<div class="title">Location</div>
</div>
<div id="map_canvas" style="width:100%; height: 400px;"></div>

<script>initialize("<?php global $client; echo $client->getAddress(); ?>");</script>