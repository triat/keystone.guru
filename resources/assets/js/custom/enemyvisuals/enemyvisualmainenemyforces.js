class EnemyVisualMainEnemyForces extends EnemyVisualMain {

    constructor(enemyvisual) {
        super(enemyvisual);

        this.iconName = 'enemy_forces';
    }

    _getValidIconNames() {
        // Nothing is valid, we don't work with icon names. One size fits all!
        return ['enemy_forces'];
    }

    _getTemplateData() {
        console.assert(this instanceof EnemyVisualMainEnemyForces, 'this is not an EnemyVisualMainEnemyForces!', this);

        let data = super._getTemplateData();

        let displayText = this._getDisplayText();
        let width = this._getTextWidth();

        // Just append a single class
        data.main_visual_outer_classes += ' enemy_icon_npc_enemy_forces text-center';
        // Slightly hacky fix to get the enemy forces to show up properly (font was changed away from Leaflet default to site default for all others)
        let template = Handlebars.templates['map_enemy_visual_enemy_forces_template'];

        let mainVisualData = $.extend({}, getHandlebarsDefaultVariables(), {
            id: this.enemyvisual.enemy.id,
            width: width,
            displayText: displayText,
        });

        data.main_visual_html = template(mainVisualData);

        return data;
    }

    /**
     * Called whenever the NPC of the enemy has been refreshed.
     */
    _refreshNpc() {
        // Re-draw the visual
        this.setIcon(this.iconName);
    }

    /**
     *
     * @returns {number}
     * @private
     */
    _getDisplayText() {
        return getState().getMapNumberStyle() === NUMBER_STYLE_ENEMY_FORCES ?
            this.enemyvisual.enemy.getEnemyForces() :
            `${getFormattedPercentage(this.enemyvisual.enemy.getEnemyForces(), this.enemyvisual.map.enemyForcesManager.getEnemyForcesRequired())}`;
    }

    /**
     *
     * @returns {*}
     * @private
     */
    _getTextWidth() {
        let displayText = this._getDisplayText();
        let size = this.enemyvisual.mainVisual.getSize();
        let width = size.iconSize[0];

        width -= c.map.enemy.calculateMargin(width);

        // More characters to display..
        if (displayText.length >= 4) {
            width -= 22;
        } else if (displayText.length === 3) {
            width -= 17;
        } else if (displayText.length === 2) {
            width -= 14;
        } else {
            width -= 10;
        }
        // Dangerous = less space
        if (this.enemyvisual.enemy.npc !== null && this.enemyvisual.enemy.npc.dangerous) {
            width -= 2;
        }

        // Inverse zoom
        width += (c.map.settings.maxZoom - getState().getMapZoomLevel());

        return width;
    }

    /**
     *
     */
    refreshSize() {
        super.refreshSize();

        let width = this._getTextWidth();
        $(`#map_enemy_visual_${this.enemyvisual.enemy.id}_enemy_forces`)
            .css('font-size', `${width}px`)
        // .css('line-height', `${width}px`)
        ;
    }

    /**
     *
     * @returns {*}
     */
    shouldRefreshOnNumberStyleChanged() {
        return true;
    }

    cleanup() {
        super.cleanup();

    }
}