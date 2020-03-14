class EnemyVisualMain extends EnemyVisualIcon {
    constructor(enemyvisual) {
        super(enemyvisual);

        let self = this;

        // Listen to changes in the NPC to update the icon and re-draw the visual
        this.enemyvisual.enemy.register('enemy:set_npc', this, this._refreshNpc.bind(this));

        this.circleMenu = null;

        this.enemyvisual.register('enemyvisual:builtvisual', this, function () {
            let id = self.enemyvisual.enemy.id;
            // When the visual exists, bind a click method to it (to increase performance)
            $('#map_enemy_visual_' + id).find('.enemy_icon').bind('click', self._visualClicked.bind(self));
        });

        this.enemyvisual.map.leafletMap.on('zoomend', this._zoomEnd, this);

        // getState().register('mapzoomlevel:changed', this, function () {
        //     self.enemyvisual.refresh();
        // });
    }

    _zoomEnd() {
        this.enemyvisual.refresh();
    }

    _getTemplateData(width, height, margin) {
        let data = super._getTemplateData(width, height, margin);

        let mainVisualOuterClasses = [];
        let mainVisualInnerClasses = ['enemy_icon', this.iconName];

        // Handle Teeming display
        if (this.enemyvisual.enemy.teeming === 'visible' || this.enemyvisual.enemy.teeming === 'hidden') {
            mainVisualOuterClasses.push('teeming');
        }
        let npc = this.enemyvisual.enemy.npc;
        if (npc !== null) {
            mainVisualOuterClasses.push(npc.aggressiveness);
            if (npc.dungeon_id === -1) {
                mainVisualOuterClasses.push('awakened');
            }

            mainVisualInnerClasses.push(npc.dangerous ? 'dangerous' : '');
        }

        // Any additional classes to add for when the enemy is selectable
        let selectionClasses = [];
        if (this.enemyvisual.enemy.isSelectable()) {
            selectionClasses.push('selected_enemy_icon');
        }

        return $.extend(data, {
            // Set the main icon
            main_visual_outer_classes: mainVisualOuterClasses.join(' '),
            main_visual_inner_classes: mainVisualInnerClasses.join(' '),
            selection_classes: selectionClasses.join(' ')
        });
    }

    /**
     * Called whenever the root visual object was clicked
     * @private
     */
    _visualClicked() {
        console.assert(this instanceof EnemyVisualMain, 'this is not an EnemyVisualMain!', this);
        let self = this;

        // Some exclusions as to when the menu should not pop up
        if (self.enemyvisual.map.options.edit &&
            !self.enemyvisual.map.isEnemySelectionEnabled() &&
            self.enemyvisual.enemy.constructor.name !== 'AdminEnemy') {

            if (self.circleMenu === null) {
                let template = Handlebars.templates['map_enemy_raid_marker_template'];
                let id = self.enemyvisual.enemy.id;

                let data = $.extend({
                    id: id
                }, getHandlebarsDefaultVariables());

                let $container = $('#map_enemy_visual_' + id);
                $container.append(template(data));

                let $circleMenu = $('#map_enemy_raid_marker_radial_' + id);

                let $enemyDiv = $container.find('.enemy_icon');

                let size = this.getSize().iconSize[0];
                let margin = c.map.enemy.calculateMargin(size);

                // Force the circle menu to appear in the center of the enemy visual
                $circleMenu.css('position', 'absolute');
                // Compensate of the 24x24 square
                $circleMenu.css('left', ((size / 2) + margin - 12) + 'px');
                $circleMenu.css('top', ((size / 2) + margin - 12) + 'px');

                // Init circle menu and open it
                self.circleMenu = $circleMenu.circleMenu({
                    direction: 'full',
                    step_in: 5,
                    step_out: 0,
                    trigger: 'click',
                    transition_function: 'linear',
                    // Radius
                    circle_radius: size + margin,
                    // Positioning
                    item_diameter: 24,
                    speed: 200,
                    init: function () {
                        refreshTooltips();
                    },
                    open: function () {
                        self.enemyvisual.enemy.unbindTooltip();
                    },
                    close: function () {
                        // Unassigned when opened
                        self.enemyvisual.enemy.bindTooltip();

                        // Delete ourselves again
                        self._cleanupCircleMenu();
                    },
                    select: function (evt, index) {
                        // Unassigned when opened
                        self.enemyvisual.enemy.bindTooltip();

                        // Assign the selected raid marker
                        self.enemyvisual.enemy.assignRaidMarker($(index).data('name'));

                        // Delete ourselves again
                        self._cleanupCircleMenu();
                    }
                });

                // Force open the menu
                self.circleMenu.circleMenu('open');

                // Unbind this function so we don't get repeat clicks
                $enemyDiv.unbind('click');
                // Give the user a way to close the menu by clicking the enemy again
                $enemyDiv.bind('click', function () {
                    self.circleMenu.circleMenu('close', false);
                    // Prevent multiple clicks triggering the close
                    $enemyDiv.unbind('click');
                });
            }
        }
    }

    /**
     * Cleans up the circle menu, removing it from the object completely.
     * @private
     */
    _cleanupCircleMenu() {
        console.assert(this instanceof EnemyVisualMain, 'this is not an EnemyVisualMain!', this);

        let self = this;
        let id = self.enemyvisual.enemy.id;
        let $enemyDiv = $('#map_enemy_visual_' + id).find('.enemy_icon');

        // Clear any stray tooltips
        refreshTooltips();

        // Delay it by 500 ms so the animations have a chance to complete
        $('#map_enemy_raid_marker_radial_' + id).delay(500).queue(function () {
            $(this).remove().dequeue();
            self.circleMenu = null;

            // Re-bind this function
            $enemyDiv.unbind('click');
            $enemyDiv.bind('click', self._visualClicked.bind(self));
        });
    }

    /**
     * Must be overriden by implementing classes
     * @protected
     */
    _refreshNpc() {

    }

    isCircleMenuOpen(){
        return this.circleMenu === null;
    }

    getSize() {
        let health = this.enemyvisual.enemy.npc === null ? 0 : this.enemyvisual.enemy.npc.base_health;
        if (this.enemyvisual.enemy.npc === null) {
            console.warn('Enemy has no NPC!', this.enemyvisual.enemy);
        } else {
            // Special catch for all dungeon enemies
            if (this.enemyvisual.enemy.npc.dungeon_id === -1) {
                health = (this.enemyvisual.map.options.npcsMinHealth + this.enemyvisual.map.options.npcsMaxHealth) / 2;
            }
        }
        let calculatedSize = c.map.enemy.calculateSize(
            health,
            this.enemyvisual.map.options.npcsMinHealth,
            this.enemyvisual.map.options.npcsMaxHealth
        );

        return {
            // 2px border; so + 4
            iconSize: [calculatedSize + 4, calculatedSize + 4]
        };
    }

    cleanup() {
        super.cleanup();
        console.assert(this instanceof EnemyVisualMain, 'this is not an EnemyVisualMain!', this);

        this.enemyvisual.enemy.unregister('enemy:set_npc', this);
        this.enemyvisual.unregister('enemyvisual:builtvisual', this);
        // getState().unregister('mapzoomlevel:changed', this);
        this.enemyvisual.map.leafletMap.off('zoomend', this._zoomEnd, this);
    }
}