class DungeonrouteDiscoverSearch extends InlineCode {

    constructor(options) {
        super(options);

        this.searchHandler = new SearchHandler();
        // Previous search params are used to prevent searching for the same thing multiple times for no reason
        this._previousSearchParams = null;
        // The current offset
        this.offset = 0;

        this.filters = [
            new SearchFilterDungeons('.grid_dungeon.selectable', this._search.bind(this)),
            new SearchFilterTitle('#title', this._search.bind(this)),
            new SearchFilterLevel('#level', this._search.bind(this), this.options.levelMin, this.options.levelMax),
            new SearchFilterAffixGroups('#filter_affixes', this._search.bind(this)),
            new SearchFilterAffixes('.select_icon.class_icon.selectable', this._search.bind(this)),
            new SearchFilterEnemyForces('#enemy_forces', this._search.bind(this)),
            new SearchFilterRating('#rating', this._search.bind(this)),
            new SearchFilterUser('#user', this._search.bind(this)),
        ];
    }

    /**
     */
    activate() {
        super.activate();

        for (let index in this.filters) {
            if (this.filters.hasOwnProperty(index)) {
                this.filters[index].activate();
            }
        }

        // Show some not very useful routes to get people to start using the filters
        this._search();
    }

    /**
     *
     * @private
     */
    _updateFilters() {
        // $('#route_list_current_filters').html(
        //
        // );
    }

    _search() {
        let searchParams = new SearchParams(this.filters, this.offset);

        console.log('searching!');

        this._updateFilters();

        // Only search if the search parameters have changed
        if (this._previousSearchParams === null || !this._previousSearchParams.equals(searchParams)) {
            this.searchHandler.search(searchParams, $('#route_list'));

            this._previousSearchParams = searchParams;
        }
    }

    cleanup() {
    }
}