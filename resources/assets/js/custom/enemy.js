$(function () {
    L.Draw.Enemy = L.Draw.Marker.extend({
        statics: {
            TYPE: 'enemy'
        },
        options: {
            icon: LeafletEnemyIcons['unset']
        },
        initialize: function (map, options) {
            // Save the type so super can fire, need to do this as cannot do this.TYPE :(
            this.type = L.Draw.Enemy.TYPE;

            L.Draw.Feature.prototype.initialize.call(this, map, options);
        }
    });
});

let LeafletEnemyMarker = L.Marker.extend({
    options: {
        icon: LeafletEnemyIcons['unset']
    }
});

class Enemy extends MapObject {
    constructor(map, layer) {
        super(map, layer);

        this.label = 'Enemy';
        // Not actually saved to the enemy, but is used for keeping track of what killzone this enemy is attached to
        this.kill_zone_id = 0;
        this.faction = 'any'; // sensible default
        this.enemy_forces_override = -1;
        // May be set when loaded from server
        this.npc = null;
        this.raid_marker_name = '';

        // Infested variables
        this.infested_yes_votes = 0;
        this.infested_no_votes = 0;
        this.infested_user_vote = null;
        // let hex = "#" + color.values[0].toString(16) + color.values[1].toString(16) + color.values[2].toString(16);

        this.setSynced(true);

        let self = this;
        this.map.register('map:killzoneselectmodechanged', this, function (event) {
            // Remove the popup
            self.layer.unbindPopup();
            // Unselected a killzone
            if (event.data.killzone === null) {
                // Restore it only if necessary
                self._rebuildPopup(event);
            }
        });
    }

    _getPercentageString(enemyForces) {
        // Do some fuckery to round to two decimal places
        return '(' + (Math.round((enemyForces / this.map.getEnemyForcesRequired()) * 10000) / 100) + '%)';
    }

    /**
     * Since the ID may not be known at spawn time, this needs to be callable from when it is known (when it's synced to server).
     *
     * @param event
     * @private
     */
    _rebuildPopup(event) {
        let self = this;

        // Popup trigger function, needs to be outside the synced function to prevent multiple bindings
        // This also cannot be a private function since that'll apparently give different signatures as well.
        let popupOpenFn = function (event) {
            $.each($('.enemy_raid_marker_icon'), function (index, value) {
                let $icon = $(value);

                // If we selected this raid marker..
                if($icon.data('name') === self.raid_marker_name ){
                    $icon.addClass('enemy_raid_marker_icon_selected');
                }

                $icon.unbind('click');
                $icon.bind('click', function () {
                    self.assignRaidMarker($icon.data('name'));
                    // Deselect current raid markers
                    $('.enemy_raid_marker_icon_selected').removeClass('enemy_raid_marker_icon_selected');
                    // Add it to this one
                    $icon.addClass('enemy_raid_marker_icon_selected');
                });
            });
            let $clearMarker = $('#enemy_raid_marker_clear_' + self.id);
            $clearMarker.bind('click', function () {
                // Empty is unassign
                self.assignRaidMarker('');
            });
        };

        let customPopupHtml = $('#enemy_edit_popup_template').html();
        // Remove template so our
        let template = handlebars.compile(customPopupHtml);

        let data = {id: self.id};

        // Build the status bar from the template
        customPopupHtml = template(data);

        let customOptions = {
            'maxWidth': '160',
            'minWidth': '160',
            'className': 'popupCustom'
        };

        self.layer.unbindPopup();
        self.layer.bindPopup(customPopupHtml, customOptions);

        // Have you tried turning it off and on again?
        self.layer.off('popupopen', popupOpenFn);
        self.layer.on('popupopen', popupOpenFn);
    }

    getEnemyForces(){
        return this.enemy_forces_override >= 0 ? this.enemy_forces_override : (this.npc === null ? 0 : this.npc.enemy_forces);
    }

    bindTooltip() {
        console.assert(this instanceof Enemy, this, 'this is not an Enemy');
        let source = $("#map_enemy_tooltip_template").html();
        let template = handlebars.compile(source);

        let data = {};
        if (this.npc !== null) {
            // Determine what to show for enemy forces based on override or not
            let enemy_forces = this.npc.enemy_forces;

            // Admin maps have 0 enemy forces
            if (this.map.getEnemyForcesRequired() > 0) {
                if (this.enemy_forces_override >= 0 || enemy_forces >= 1) {
                    // @TODO This HTML probably needs to go somewhere else
                    if (this.enemy_forces_override >= 0) {
                        enemy_forces = '<s>' + enemy_forces + '</s> ' +
                            '<span style="color: orange;">' + this.enemy_forces_override + '</span> ' + this._getPercentageString(this.enemy_forces_override);
                    } else if (enemy_forces >= 1) {
                        enemy_forces += ' ' + this._getPercentageString(enemy_forces);
                    }
                } else if (enemy_forces === -1) {
                    enemy_forces = 'unknown';
                }
            }

            data = {
                npc_name: this.npc.name,
                enemy_forces: enemy_forces,
                base_health: this.npc.base_health,
                attached_to_pack: this.enemy_pack_id >= 0 ? 'true (' + this.enemy_pack_id + ')' : 'false'
            };
        }

        this.layer.bindTooltip(template(data), {
            offset: [0, 10],
            direction: 'bottom'
        });
    }

    /**
     * Sets the NPC for this enemy based on a remote NPC object.
     * @param npc
     */
    setNpc(npc) {
        console.assert(this instanceof Enemy, this, 'this is not an Enemy');
        this.npc = npc;


        // May be null if not set at all (yet)
        if (npc !== null) {
            this.npc_id = npc.id;
            this.enemy_forces = npc.enemy_forces;
        } else {
            // Not set :(
            this.npc_id = -1;
        }

        this.signal('enemy:set_npc', {npc: npc});

        this.bindTooltip();
    }

    /**
     * Sets the name of the raid marker and changes the icon on the map to that of the raid marker (allowing).
     * @param name
     */
    setRaidMarkerName(name) {
        console.assert(this instanceof Enemy, this, 'this is not an Enemy');
        this.raid_marker_name = name;
        // Trigger a raid marker change event
        this.signal('enemy:set_raid_marker', {name: name});
    }

    /**
     * Sets the killzone for this enemy.
     * @param killZoneId id
     */
    setKillZone(killZoneId) {
        console.assert(this instanceof Enemy, this, 'this is not an Enemy');
        this.kill_zone_id = killZoneId;

        // We only want to trigger these events when the killzone is actively being edited, not when loading in
        // the connections from the server initially
        if (this.map.isKillZoneSelectModeEnabled()) {
            if (this.kill_zone_id >= 0) {
                this.signal('killzone:attached');
            } else {
                this.signal('killzone:detached');
            }
        }
    }

    getDifficultyColor(difficulty) {
        let palette = window.interpolate(c.map.enemy.colors);
        // let rand = Math.random();
        let color = palette(difficulty);
        this.setColors({
            saved: color,
            savedBorder: color,
            edited: color,
            editedBorder: color
        });
    }

    // To be overridden by any implementing classes
    onLayerInit() {
        console.assert(this instanceof Enemy, this, 'this is not an Enemy');
        super.onLayerInit();

        let self = this;

        // Show a permanent tooltip for the pack's name
        this.layer.on('click', function () {
            if (self.killZoneSelectable) {
                self.signal('killzone:selected');
            }
        });

        if (this.isEditable() && this.map.edit) {
            this.onPopupInit();
        }

        // Create the visual when the layer is initializing
        this.visual = new EnemyVisual(this.map, this, this.layer);
    }

    onPopupInit() {
        console.assert(this instanceof Enemy, this, 'this was not an Enemy');
        let self = this;

        // When we're synced, construct the popup.  We don't know the ID before that so we cannot properly bind the popup.
        // Called multiple times, so unreg first
        this.unregister('synced', this, this._rebuildPopup.bind(this));
        // When we're synced, construct the popup.  We don't know the ID before that so we cannot properly bind the popup.
        this.register('synced', this, this._rebuildPopup.bind(this));

        self.map.leafletMap.on('contextmenu', function () {
            if (self.currentPatrolPolyline !== null) {
                self.map.leafletMap.addLayer(self.currentPatrolPolyline);
                self.currentPatrolPolyline.disable();
            }
        });
    }

    /**
     * Checks if this enemy is possibly selectable by a kill zone.
     * @returns {*}
     */
    isKillZoneSelectable() {
        return this.killZoneSelectable;
    }

    /**
     * Set this enemy to be selectable whenever a KillZone wants to possibly kill this enemy.
     * @param value
     */
    setKillZoneSelectable(value) {
        console.assert(this instanceof Enemy, this, 'this is not an Enemy');
        this.killZoneSelectable = value;
        // Refresh the icon
        this.visual.refresh();
    }

    /**
     * Assigns a raid marker to this enemy.
     * @param raidMarkerName The name of the marker, or empty to unset it
     */
    assignRaidMarker(raidMarkerName) {
        console.assert(this instanceof Enemy, this, 'this was not an Enemy');
        let self = this;

        let successFn = function (json) {
            self.setSynced(true);
            self.map.leafletMap.closePopup();
            self.setRaidMarkerName(raidMarkerName);
        };

        // No network traffic!
        if (this.map.isTryModeEnabled()) {
            successFn();
        } else {
            $.ajax({
                type: 'POST',
                url: '/ajax/enemy/raidmarker',
                dataType: 'json',
                data: {
                    dungeonroute: dungeonRoutePublicKey,
                    enemy_id: self.id,
                    raid_marker_name: raidMarkerName
                },
                success: successFn,
            });
        }
    }
}