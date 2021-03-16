class DungeonrouteDiscoverDiscover extends InlineCode {

    /**
     */
    activate() {
        super.activate();

        $('.owl-carousel').owlCarousel({
            nav: false,
            loop: true,
            dots: false,
            lazyLoad: true,
            lazyLoadEager: 1,
            items: 1
        });

        $('[data-toggle="popover"]').popover();
    }

    cleanup() {
    }
}