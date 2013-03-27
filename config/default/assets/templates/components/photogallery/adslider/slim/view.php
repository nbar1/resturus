<div id="rg-gallery" class="rg-gallery">
    <div class="rg-thumbs">
        <!-- Elastislide Carousel Thumbnail Viewer -->
        <div class="es-carousel-wrapper">
            <div class="es-nav">
                <span class="es-nav-prev">Previous</span>
                <span class="es-nav-next">Next</span>
            </div>
            <div class="es-carousel">
                <ul>
                	!{loop://images/}
                    <li>
                        <a href="#">
                            <img src="/!{loopvar://images/[*]/thumb/}" data-large="/!{loopvar://images/[*]/src/}" alt="image01" data-description="" />
                        </a>
                    </li>
                    !{endloop://images/}
                </ul>
            </div>
        </div>
        <!-- End Elastislide Carousel Thumbnail Viewer -->
    </div><!-- rg-thumbs -->
</div><!-- rg-gallery -->

<script id="img-wrapper-tmpl" type="text/x-jquery-tmpl">  
    <div class="rg-image-wrapper">
        <div class="rg-image"></div>
        {{if itemsCount > 1}}
            <div class="rg-image-nav">
                <a href="#" class="rg-image-nav-prev">Previous Image</a>
                <a href="#" class="rg-image-nav-next">Next Image</a>
            </div>
        {{/if}}
        <div class="rg-loading"></div>
        <div class="rg-caption-wrapper">
            <div class="rg-caption" style="display:none;">
                <p></p>
            </div>
        </div>
    </div>
</script>