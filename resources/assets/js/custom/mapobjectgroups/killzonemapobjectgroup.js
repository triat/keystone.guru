/**
 * @property objects {KillZone[]}
 */
class KillZoneMapObjectGroup extends MapObjectGroup {
    constructor(manager, editable) {
        super(manager, MAP_OBJECT_GROUP_KILLZONE, editable);

        this.title = 'Hide/show killzone';
        this.fa_class = 'fa-bullseye';
    }

    /**
     * @inheritDoc
     **/
    _getRawObjects() {
        return getState().getMapContext().getKillZones();
    }

    _loadMapObject(remoteMapObject, layer = null, user = null) {
        /** @type {KillZone} */
        let mapObject = super._loadMapObject(remoteMapObject, layer, user);

        // If this was received from Echo..
        if (user !== null && remoteMapObject.lat !== null && remoteMapObject.lng !== null) {
            // // Restore the kill area if they created it for us
            this.setLayerToMapObject(this._createLayer(remoteMapObject), mapObject);

            mapObject.redrawConnectionsToEnemies();
        }

        return mapObject;
    }

    /**
     * @inheritDoc
     */
    _createLayer(remoteMapObject) {
        let layer = null;
        if (remoteMapObject.lat !== null && remoteMapObject.lng !== null) {
            layer = (new LeafletKillZoneMarker()).setLatLng(L.latLng(remoteMapObject.lat, remoteMapObject.lng));
        }
        return layer;
    }

    _onObjectDeleted(data) {
        super._onObjectDeleted(data);
        let mapObject = data.context;

        let toSave = [];

        $.each(this.objects, function (i, obj) {
            if (obj.getIndex() >= mapObject.getIndex()) {
                toSave.push(obj);
            }
            obj.setIndex(i + 1);
        });

        // If last pull is deleted, we don't need to change anything to pulls ahead of us (indices)
        if (toSave.length > 1) {
            this.massSave('*', null, toSave);
        }

        mapObject.unregister('killzone:enemyremoved', this);
        mapObject.unregister('killzone:enemyadded', this);
    }

    _createMapObject(layer, options = {}) {
        console.assert(this instanceof KillZoneMapObjectGroup, 'this is not an KillZoneMapObjectGroup', this);

        return new KillZone(this.manager.map, layer);
    }

    _createNewMapObject(layer, options) {
        let mapObject = super._createNewMapObject(layer, options);

        mapObject.register('killzone:enemyremoved', this, this._onKillZoneEnemyRemoved.bind(this));
        mapObject.register('killzone:enemyadded', this, this._onKillZoneEnemyAdded.bind(this));
        mapObject.register('killzone:overpulledenemyremoved', this, this._onKillZoneOverpulledEnemyRemoved.bind(this));
        mapObject.register('killzone:overpulledenemyadded', this, this._onKillZoneOverpulledEnemyAdded.bind(this));
        mapObject.register('killzone:obsoleteenemychanged', this, this._onKillZoneObsoleteEnemyChanged.bind(this));

        return mapObject;
    }

    _onKillZoneEnemyRemoved(killZoneEnemyRemovedEvent) {
        this.signal('killzone:enemyremoved', {
            killzone: killZoneEnemyRemovedEvent.context,
            enemy: killZoneEnemyRemovedEvent.data.enemy
        });
        this.signal('killzone:changed', {killzone: killZoneEnemyRemovedEvent.context});
    }

    _onKillZoneEnemyAdded(killZoneEnemyAddedEvent) {
        this.signal('killzone:enemyadded', {
            killzone: killZoneEnemyAddedEvent.context,
            enemy: killZoneEnemyAddedEvent.data.enemy
        });
        this.signal('killzone:changed', {killzone: killZoneEnemyAddedEvent.context});
    }

    _onKillZoneOverpulledEnemyRemoved(killZoneEnemyRemovedEvent) {
        this.signal('killzone:overpulledenemyremoved', {
            killzone: killZoneEnemyRemovedEvent.context,
            enemy: killZoneEnemyRemovedEvent.data.enemy
        });
        this.signal('killzone:changed', {killzone: killZoneEnemyRemovedEvent.context});
    }

    _onKillZoneOverpulledEnemyAdded(killZoneEnemyAddedEvent) {
        this.signal('killzone:overpulledenemyadded', {
            killzone: killZoneEnemyAddedEvent.context,
            enemy: killZoneEnemyAddedEvent.data.enemy
        });
        this.signal('killzone:changed', {killzone: killZoneEnemyAddedEvent.context});
    }

    _onKillZoneObsoleteEnemyChanged(killZoneObsoleteEnemyChangedEvent) {
        this.signal('killzone:obsoleteenemychanged', {
            killzone: killZoneObsoleteEnemyChangedEvent.context,
            enemy: killZoneObsoleteEnemyChangedEvent.data.enemy
        });
        this.signal('killzone:changed', {killzone: killZoneObsoleteEnemyChangedEvent.context});
    }

    _onObjectChanged(objectChangedEvent) {
        super._onObjectChanged(objectChangedEvent);

        this.signal('killzone:changed', {killzone: objectChangedEvent.context});
    }

    load() {
        super.load();

        // Load overpulled enemies in our kill zone
        if (getState().getMapContext() instanceof MapContextLiveSession) {
            let overpulledEnemiesData = getState().getMapContext().getOverpulledEnemies();
            let enemyMapObjectGroup = this.manager.getByName(MAP_OBJECT_GROUP_ENEMY);

            for (let index in overpulledEnemiesData) {
                if (overpulledEnemiesData.hasOwnProperty(index)) {
                    let overpulledEnemyData = overpulledEnemiesData[index];

                    /** @type {KillZone} */
                    let killZone = this.findMapObjectById(overpulledEnemyData.kill_zone_id);
                    /** @type {Enemy} */
                    let enemy = enemyMapObjectGroup.findMapObjectById(overpulledEnemyData.enemy_id);
                    if (killZone !== null && enemy !== null) {
                        killZone.addOverpulledEnemy(enemy);
                    }
                }
            }
        }
    }

    /**
     * Finds a KillZone in this map object group by its index.
     *
     * @param index {Number}
     * @returns {KillZone}
     */
    findKillZoneByIndex(index) {
        console.assert(this instanceof KillZoneMapObjectGroup, 'this is not a KillZoneMapObjectGroup', this);

        let result = null;

        // Do not return an already saving map object which has id -1 of which multiple can exist
        if (index > 0) {
            for (let i = 0; i < this.objects.length; i++) {
                let objectCandidate = this.objects[i];
                if (objectCandidate.index === index) {
                    result = objectCandidate;
                    break;
                }
            }
        }

        return result;
    }

    /**
     * Creates a whole new pull.
     * @param enemyIds {Array} Any enemies that must be in the pull from the start
     * @param afterIndex {Number} Insert the pull after this pull's index. Null to insert as the last pull
     * @returns {KillZone}
     */
    createNewPull(enemyIds = [], afterIndex = null) {
        console.assert(this instanceof KillZoneMapObjectGroup, 'this is not a KillZoneMapObjectGroup', this);
        // Construct an object equal to that received from the server
        let killZoneEnemies = [];
        for (let i = 0; i < enemyIds.length; i++) {
            killZoneEnemies.push({enemy_id: enemyIds[i]});
        }

        let toSave = [];
        // If we're inserting it last - we don't affect existing killzones
        if (afterIndex !== null) {
            // But we do if it was inserted half way - we need to update all indices of everything that's after this pull
            toSave = this.objects.filter(killzone => killzone.index > afterIndex);

            for (let index in toSave) {
                toSave[index].index++;
            }
        }

        let killZone = this._loadMapObject({
            id: -1,
            color: c.map.killzone.polygonOptions.color(this.objects.length > 0 ? this.objects[this.objects.length - 1].color : null),
            floor_id: -1, // Only for the killzone location which is not set from a 'new pull'
            killzoneenemies: killZoneEnemies,
            lat: null,
            lng: null,
            index: (afterIndex ?? this.objects.length) + 1,
            // Bit of a hack, we don't want the synced event to be fired in this case, we only want it _after_ the ID has been
            // set by calling save() below. That will then trigger object:add and the killzone will have it's ID for the UI
            local: true
        });

        // Change the color as necessary - but don't save if we will do it later on
        if (getState().getMapContext().getPullGradientApplyAlways()) {
            // Also save the index we just changed up above
            this.applyPullGradient(afterIndex === null, null);
        }

        // Save both the color and the index
        if (afterIndex !== null) {
            this.massSave(['color', 'index'], null, toSave);
        }

        killZone.save();

        this.signal('killzone:new', {newKillZone: killZone});
        return killZone;
    }

    /**
     * Applies the pull gradient to killzones
     * @param save {Boolean}
     * @param saveOnComplete {function|null}
     * @param saveAdditionalFields {Array}
     */
    applyPullGradient(save = false, saveOnComplete = null, saveAdditionalFields = []) {
        console.assert(this instanceof KillZoneMapObjectGroup, 'this is not a KillZoneMapObjectGroup', this);

        let count = this.objects.length;
        let handlers = getState().getPullGradientHandlers();
        for (let i = 0; i < count; i++) {
            for (let killZoneIndex in this.objects) {
                if (this.objects.hasOwnProperty(killZoneIndex)) {
                    let killZone = this.objects[killZoneIndex];
                    if (killZone.getIndex() === (i + 1)) {
                        // Prevent division by 0
                        killZone.color = pickHexFromHandlers(handlers, count === 1 ? 50 : (i / count) * 100);
                        break;
                    }
                }
            }
        }

        if (save) {
            this.massSave(['color'].concat(saveAdditionalFields), saveOnComplete);
        }
    }

    /**
     * Saves all KillZones using the mass update endpoint.
     * @param fields {String|Array}
     * @param onComplete {function|null} Called when massSave completed
     * @param killZones {array}
     */
    massSave(fields = '*', onComplete = null, killZones = []) {
        console.assert(this instanceof KillZoneMapObjectGroup, 'this is not a KillZoneMapObjectGroup', this);

        // All killzones if not supplied
        if (killZones.length === 0) {
            killZones = this.objects;
        }

        let killZonesData = [];
        for (let i = 0; i < killZones.length; i++) {
            let killZone = killZones[i];

            // Only those that can be saved
            if (killZone.id > 0) {
                killZonesData.push(killZone.getSaveData(fields));
            }
        }

        $.ajax({
            type: 'PUT',
            url: `/ajax/${getState().getMapContext().getPublicKey()}/${MAP_OBJECT_GROUP_KILLZONE}`,
            dataType: 'json',
            data: {
                killzones: killZonesData
            },
            success: function (json) {
                for (let i = 0; i < killZones.length; i++) {
                    killZones[i].setSynced(true, true);
                    killZones[i].onSaveSuccess(json, true);
                }
            },
            complete: function () {
                if (typeof onComplete === 'function') {
                    onComplete();
                }
            }
        });
    }

    /**
     * Checks if a specific enemy is killed by any kill zone.
     * @param enemyId {Number}
     * @returns {boolean}
     */
    isEnemyKilled(enemyId) {
        let result = false;

        for (let i = 0; i < this.objects.length; i++) {
            let killZone = this.objects[i];
            if (killZone.enemies.concat(killZone.overpulledEnemies).includes(enemyId)) {
                result = true;
                break;
            }
        }

        return result;
    }

    /**
     * Checks if the user has killed all required enemies, if not, returns false. True otherwise
     * @returns {boolean}
     */
    hasKilledAllRequiredEnemies() {
        let result = true;

        let enemyMapObjectGroup = this.manager.getByName(MAP_OBJECT_GROUP_ENEMY);
        let mapContext = getState().getMapContext();

        for (let i = 0; i < enemyMapObjectGroup.objects.length; i++) {
            let enemy = enemyMapObjectGroup.objects[i];
            // If this enemy SHOULD have been killed by the user
            if (enemy.required &&
                // If not teeming, OR if enemy is teeming AND we're teeming, or inverse that. THEN this enemy counts, otherwise it does not
                (enemy.teeming === null || (enemy.teeming === 'visible' && mapContext.getTeeming()) || (enemy.teeming === 'invisible' && !mapContext.getTeeming()))
            ) {
                // But if it's not..
                if (!this.isEnemyKilled(enemy.id)) {
                    // console.warn(`Has not killed enemy ${enemy.id}!`);
                    result = false;
                    break;
                }
            }
        }

        return result;
    }

    /**
     * Deletes all kill zones in this route
     * @param callback Callable
     */
    deleteAll(callback = null) {
        let self = this;

        $.ajax({
            type: 'POST',
            url: `/ajax/${getState().getMapContext().getPublicKey()}/${MAP_OBJECT_GROUP_KILLZONE}`,
            dataType: 'json',
            data: {
                _method: 'DELETE',
                confirm: 'yes',
            },
            success: function (json) {

                for (let i = self.objects.length - 1; i >= 0; i--) {
                    let killZone = self.objects[i];
                    killZone.localDelete(true);
                    killZone.onDeleteSuccess(json, true);
                }

                let enemyMapObjectGroup = self.manager.getByName(MAP_OBJECT_GROUP_ENEMY);
                for (let i = 0; i < enemyMapObjectGroup.objects.length; i++) {
                    let enemy = enemyMapObjectGroup.objects[i];
                    if (enemy instanceof PridefulEnemy && enemy.isAssigned()) {
                        // Prideful enemies override the delete method, so we can delete them without deleting the actual enemy
                        enemy.unsetAssignedLocation();
                    }
                }

                if (callback !== null) {
                    callback();
                }

                showSuccessNotification(lang.get('messages.delete_all_pulls_successful'));
            }
        });
    }

    // _fetchSuccess(response) {
    //     // no super call, we're handling this by ourselves
    //     console.assert(this instanceof KillZoneMapObjectGroup, 'this is not a KillZoneMapObjectGroup', this);
    //
    //     if (!this.initialized) {
    //         let killZones = getState().getMapContext().getKillZones();
    //
    //         // Now draw the enemies on the map, if any
    //         for (let index in killZones) {
    //             // Only if actually set
    //             if (killZones.hasOwnProperty(index)) {
    //                 let killZone = killZones[index];
    //                 // Only restore enemies for the current floor
    //                 this._loadMapObject(killZone);
    //             }
    //         }
    //
    //         this.initialized = true;
    //
    //         this.signal('loadcomplete');
    //     } else {
    //         // Show any killzones that are on the new floor
    //         for (let index in this.objects) {
    //             if (this.objects.hasOwnProperty(index)) {
    //                 let killZone = this.objects[index];
    //                 // Re-set the enemy list
    //                 killZone.setEnemies([...killZone.enemies]);
    //
    //                 // Only display the kill zone's kill area if it's on our current floor
    //                 if (killZone.layer !== null && killZone.floor_id === getState().getCurrentFloor().id) {
    //                     this.setMapObjectVisibility(killZone, true);
    //                 }
    //             }
    //         }
    //
    //         this.setVisibility(true);
    //     }
    // }
}
