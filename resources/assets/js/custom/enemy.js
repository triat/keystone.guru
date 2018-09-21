$(function () {
    L.Draw.Enemy = L.Draw.Marker.extend({
        statics: {
            TYPE: 'enemy'
        },
        options: {
            icon: LeafletUnsetEnemyIcon
        },
        initialize: function (map, options) {
            // Save the type so super can fire, need to do this as cannot do this.TYPE :(
            this.type = L.Draw.Enemy.TYPE;

            L.Draw.Feature.prototype.initialize.call(this, map, options);
        }
    });
});

// CSS class for what icon is what (background)
let _aggressiveClass = 'aggressive_enemy_icon';
let _neutralClass = 'neutral_enemy_icon';
let _unfriendlyClass = 'unfriendly_enemy_icon';
let _friendlyClass = 'friendly_enemy_icon';
let _unsetClass = 'unset_enemy_icon';
let _flaggedClass = 'flagged_enemy_icon';
let _bossClass = 'boss_enemy_icon';

// Icon sizes
let _smallIcon = {iconSize: [12, 12]};
let _bigIcon = {iconSize: [32, 32]};

// Default icons
let LeafletAggressiveEnemyIcon = new L.divIcon($.extend({className: _aggressiveClass + ' test_padding'}, _smallIcon));
let LeafletNeutralEnemyIcon = new L.divIcon($.extend({className: _neutralClass}, _smallIcon));
let LeafletUnfriendlyEnemyIcon = new L.divIcon($.extend({className: _unfriendlyClass}, _smallIcon));
let LeafletFriendlyEnemyIcon = new L.divIcon($.extend({className: _friendlyClass}, _smallIcon));
let LeafletUnsetEnemyIcon = new L.divIcon($.extend({className: _unsetClass}, _smallIcon));
let LeafletFlaggedEnemyIcon = new L.divIcon($.extend({className: _flaggedClass}, _smallIcon));
let LeafletBossEnemyIcon = new L.divIcon($.extend({className: _bossClass}, _bigIcon));

// Have to extend this as to not override the above icons
let LeafletAggressiveEnemyIconKillZone = new L.divIcon($.extend({className: _aggressiveClass + ' killzone_enemy_icon_small leaflet-edit-marker-selected'}, _smallIcon));
let LeafletNeutralEnemyIconKillZone = new L.divIcon($.extend({className: _neutralClass + ' killzone_enemy_icon_small leaflet-edit-marker-selected'}, _smallIcon));
let LeafletUnfriendlyEnemyIconKillZone = new L.divIcon($.extend({className: _unfriendlyClass + ' killzone_enemy_icon_small leaflet-edit-marker-selected'}, _smallIcon));
let LeafletFriendlyEnemyIconKillZone = new L.divIcon($.extend({className: _friendlyClass + ' killzone_enemy_icon_small leaflet-edit-marker-selected'}, _smallIcon));
let LeafletUnsetEnemyIconKillZone = new L.divIcon($.extend({className: _unsetClass + ' killzone_enemy_icon_small leaflet-edit-marker-selected'}, _smallIcon));
let LeafletFlaggedEnemyIconKillZone = new L.divIcon($.extend({className: _flaggedClass + ' killzone_enemy_icon_small leaflet-edit-marker-selected'}, _smallIcon));
let LeafletBossEnemyIconKillZone = new L.divIcon($.extend({className: _bossClass + ' killzone_enemy_icon_big leaflet-edit-marker-selected'}, _bigIcon));

let LeafletEnemyMarker = L.Marker.extend({
    options: {
        icon: LeafletUnsetEnemyIcon
    }
});

class Enemy extends MapObject {
    constructor(map, layer) {
        super(map, layer);

        this.label = 'Enemy';
        this.iconName = '';
        this.divIcon = null;
        // Not actually saved to the enemy, but is used for keeping track of what killzone this enemy is attached to
        this.kill_zone_id = 0;
        this.faction = 'any'; // sensible default
        this.enemy_forces_override = -1;
        // May be set when loaded from server
        this.npc = null;
        // console.log(rand);
        // let hex = "#" + color.values[0].toString(16) + color.values[1].toString(16) + color.values[2].toString(16);

        this.setSynced(true);
    }

    _getPercentageString(enemyForces) {
        // Do some fuckery to round to two decimal places
        return '(' + (Math.round((enemyForces / this.map.getEnemyForcesRequired()) * 10000) / 100) + '%)';
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
            if( this.map.getEnemyForcesRequired() > 0 ){
                if (this.enemy_forces_override >= 0 || enemy_forces >= 1) {

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

        this.layer.bindTooltip(template(data));
    }

    /**
     * Sets the NPC for this enemy based on a remote NPC object.
     * @param npc
     */
    setNpc(npc) {
        console.assert(this instanceof Enemy, this, 'this is not an Enemy');

        // May be null if not set at all (yet)
        if (npc !== null) {
            this.npc = npc;
            this.npc_id = npc.id;
            this.enemy_forces = npc.enemy_forces;
            if (npc.enemy_forces === -1) {
                this.setIcon('flagged');
            }
            // TODO Hard coded 3 = boss
            else if (npc.classification_id === 3) {
                this.setIcon('boss');
            } else {
                this.setIcon(npc.aggressiveness);
            }
        } else {
            // Not set :(
            this.npc_id = -1;
            this.setIcon('unset');
        }

        this.bindTooltip();
    }

    /**
     * Sets the killzone for this enemy.
     * @param killZoneId id
     */
    setKillZone(killZoneId) {
        console.assert(this instanceof Enemy, this, 'this is not an Enemy');
        this.kill_zone_id = killZoneId;

        // @TODO Perhaps this should be different? I don't like the dependency on killzone.js
        // We only want to trigger these events when the killzone is actively being edited, not when loading in
        // the connections from the server initially
        if (KillZoneSelectModeEnabled) {
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

        this.onPopupInit();
    }

    onPopupInit(){
        console.assert(this instanceof Enemy, this, 'this was not an Enemy');
        let self = this;

        // Popup trigger function, needs to be outside the synced function to prevent multiple bindings
        // This also cannot be a private function since that'll apparently give different signatures as well.
        let popupOpenFn = function (event) {
            // $('#enemy_edit_popup_teeming_' + self.id).val(self.teeming);
            // $('#enemy_edit_popup_faction_' + self.id).val(self.faction);
            // $('#enemy_edit_popup_enemy_forces_override_' + self.id).val(self.enemy_forces_override);
            // $('#enemy_edit_popup_npc_' + self.id).val(self.npc_id);

            // Refresh all select pickers so they work again
            // let $selectpicker = $('.selectpicker');
            // $selectpicker.selectpicker('refresh');
            // $selectpicker.selectpicker('render');

            $.each($('.raid_marker_icon'), function(index, value){
                let $icon = $(value);
                $icon.unbind('click');
                $icon.bind('click', function () {
                    self.assignRaidMarker($icon.data('id'));
                });
            });

            let $submitBtn = $('#enemy_edit_popup_submit_' + self.id);

            $submitBtn.unbind('click');
            $submitBtn.bind('click', function () {
                // self.teeming = $('#enemy_edit_popup_teeming_' + self.id).val();
                // self.faction = $('#enemy_edit_popup_faction_' + self.id).val();
                // self.enemy_forces_override = $('#enemy_edit_popup_enemy_forces_override_' + self.id).val();
                // self.npc_id = $('#enemy_edit_popup_npc_' + self.id).val();

                self.edit();
            });
        };

        // When we're synced, construct the popup.  We don't know the ID before that so we cannot properly bind the popup.
        this.register('synced', this, function(event){
            let customPopupHtml = $('#enemy_edit_popup_template').html();
            // Remove template so our
            let template = handlebars.compile(customPopupHtml);

            let data = {id: self.id};

            // Build the status bar from the template
            customPopupHtml = template(data);

            let customOptions = {
                'maxWidth': '128',
                'minWidth': '128',
                'className': 'popupCustom'
            };

            self.layer.unbindPopup();
            self.layer.bindPopup(customPopupHtml, customOptions);

            // Have you tried turning it off and on again?
            self.layer.off('popupopen', popupOpenFn);
            self.layer.on('popupopen', popupOpenFn);
        });

        self.map.leafletMap.on('contextmenu', function(){
            if( self.currentPatrolPolyline !== null ){
                self.map.leafletMap.addLayer(self.currentPatrolPolyline);
                self.currentPatrolPolyline.disable();
            }
        });
    }

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
        this.setIcon(this.iconName);
    }

    /**
     * Sets the icon of this enemy based on a name
     * @param name
     */
    setIcon(name) {
        console.assert(this instanceof Enemy, this, 'this is not an Enemy');

        switch (name) {
            case 'aggressive':
                this.divIcon = this.killZoneSelectable ? LeafletAggressiveEnemyIconKillZone : LeafletAggressiveEnemyIcon;

                break;
            case 'neutral':
                this.divIcon = this.killZoneSelectable ? LeafletNeutralEnemyIconKillZone : LeafletNeutralEnemyIcon;

                break;
            case 'unfriendly':
                this.divIcon = this.killZoneSelectable ? LeafletUnfriendlyEnemyIconKillZone : LeafletUnfriendlyEnemyIcon;

                break;
            case 'friendly':
                this.divIcon = this.killZoneSelectable ? LeafletFriendlyEnemyIconKillZone : LeafletFriendlyEnemyIcon;

                break;
            case 'unset':
                this.divIcon = this.killZoneSelectable ? LeafletUnsetEnemyIconKillZone : LeafletUnsetEnemyIcon;

                break;
            case 'flagged':
                this.divIcon = this.killZoneSelectable ? LeafletFlaggedEnemyIconKillZone : LeafletFlaggedEnemyIcon;

                break;
            case 'boss':
                this.divIcon = this.killZoneSelectable ? LeafletBossEnemyIconKillZone : LeafletBossEnemyIcon;

                break;
        }

        this.layer.setIcon(this.divIcon);
        this.iconName = name;
    }
}