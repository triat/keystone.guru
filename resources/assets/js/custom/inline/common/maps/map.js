class CommonMapsMap extends InlineCode {

    constructor(options) {
        super(options);
        this._dungeonMap = null;

        this.settingsTabRoute = new SettingsTabRoute(options);
        this.settingsTabMap = new SettingsTabMap(options);
        this.settingsTabPull = new SettingsTabPull(options);
    }

    /**
     *
     * @returns {null}
     */
    getDungeonMap() {
        return this._dungeonMap;
    }

    /**
     *
     */
    activate() {
        super.activate();

        let self = this;

        this._initDungeonMap();

        if (!this.options.noUI) {
            this.settingsTabRoute.activate();
            this.settingsTabMap.activate();
            this.settingsTabPull.activate();

            // Snackbars
            getState().register('snackbar:add', this, this._onSnackbarAdd.bind(this));
            getState().register('snackbar:remove', this, this._onSnackbarRemove.bind(this));

            // Sidebar
            this._setupRatingSelection();
            this._setupFloorSelection();
            this._setupMapObjectGroupVisibility();
            this._setupEnemyVisualTypes();
            this._setupFavorite();

            // Sharing
            $('#share_modal').on('show.bs.modal', this._fetchMdtExportString.bind(this));

            // MDT clones button
            $('#map_enemy_visuals_map_mdt_clones_to_enemies').bind('change', function () {
                getState().setMdtMappingModeEnabled(
                    $('#map_enemy_visuals_map_mdt_clones_to_enemies').is(':checked')
                );
            });

            // Trigger info popover
            $('#map_dungeon_route_info_popover').popover().on('inserted.bs.popover', function () {
                $('#view_dungeonroute_group_setup').html(
                    handlebarsGroupSetupParse(self.options.dungeonroute.setup)
                );

                // refreshTooltips();
            });

            // Enemy info should be set on mouseover
            getState().register('focusedenemy:changed', this, this._onFocusedEnemyChanged.bind(this));

            $('#userreport_enemy_modal_submit').bind('click', this._submitEnemyUserReport.bind(this));

            this._dungeonMap.leafletMap.on('move', function () {
                $('#enemy_info_container').hide();
            });

            // Live sessions
            $('#stop_live_session_modal select').barrating({
                theme: 'fontawesome-stars',
                onSelect: function (value, text, event) {
                    self._rate(value);
                }
            });
        }
    }

    /**
     * Initializes the dungeon map. Cannot put this in activate() because that leads to the draw controls being messed up.
     */
    _initDungeonMap() {
        if (getState().isMapAdmin()) {
            this._dungeonMap = new AdminDungeonMap('map', this.options);
        } else {
            this._dungeonMap = new DungeonMap('map', this.options);
        }

        if (this.options.sandbox) {
            $('#start_tutorial').bind('click', function () {
                introjs().start();
            });

            // Bind leaflet virtual tour classes
            let selectors = [
                ['intro_sidebar', '#editsidebar', 'right'],
                ['intro_sidebar_toggle', '#editsidebarToggle', 'right'],

                ['intro_visibility_tools', '.visibility_tools', 'right'],
                ['intro_map_enemy_visuals', '#map_enemy_visuals', 'right'],
                ['intro_map_map_object_group_visibility_container', '#map_map_object_group_visibility_container', 'right'],
                ['intro_floor_selection', '#map_floor_selection_container', 'right'],

                ['intro_route_actions', '.route_actions', 'right'],
                ['intro_route_actions_draw_settings', '#map_draw_settings_btn_container', 'right'],
                ['intro_route_actions_map_login_and_continue', '#map_login_and_continue', 'right'],
                ['intro_route_actions_map_register_and_continue', '#map_register_and_continue', 'right'],
                ['intro_route_actions_save_and_continue', '#map_save_and_continue', 'right'],

                ['intro_route_manipulation_tools', '.route_manipulation_tools', 'top'],
                ['intro_draw_path', '.leaflet-draw-draw-path', 'left'],
                ['intro_draw_mapicon', '.leaflet-draw-draw-mapicon', 'left'],
                ['intro_draw_brushline', '.leaflet-draw-draw-brushline', 'left'],
                ['intro_draw_pridefulenemy', '.leaflet-draw-draw-pridefulenemy', 'left'],

                ['intro_draw_edit', '.leaflet-draw-edit-edit', 'left'],
                ['intro_draw_remove', '.leaflet-draw-edit-remove', 'left'],

                ['intro_map_enemy_forces_numbers', '#map_enemy_forces_numbers', 'left'],
                ['intro_new_pull', '#killzones_new_pull', 'left'],
            ];

            this._dungeonMap.register('map:refresh', null, function () {
                // Upon map refresh, re-init the tutorial selectors
                let step = 1;
                for (let i = 0; i < selectors.length; i++) {
                    let $selector = $(selectors[i][1]);
                    // Floor selection may not exist
                    if ($selector.length > 0) {
                        let messageKey = selectors[i][0];
                        $selector.attr('data-intro', lang.get(`messages.${messageKey}`));
                        $selector.attr('data-position', selectors[i][2]);
                        $selector.attr('data-step', step);

                        step++;
                    }
                }
            });
        }

        // Refresh the map; draw the layers on it
        this._dungeonMap.refreshLeafletMap();
    }

    /**
     *
     * @private
     */
    _setupRatingSelection() {
        let self = this;

        // Rating
        let $mapRatingDropdown = $('#map_rating_dropdown');
        $mapRatingDropdown.find('a:not(.disabled)').bind('click', function () {
            let $this = $(this);

            self._rate($this.data('rating'));

            // Reset all back to non-active
            $('#map_rating_dropdown').find('a:not(.disabled)').each(function (index, element) {
                $(element).removeClass('active');
            });

            // Now toggled this floor on
            $this.addClass('active');
        });
    }


    /**
     *
     * @private
     */
    _setupFloorSelection() {
        // Floor selection
        let $mapFloorSelectionDropdown = $('#map_floor_selection_dropdown');
        $mapFloorSelectionDropdown.find('a:not(.disabled)').bind('click', function () {
            let $this = $(this);
            getState().setFloorId($this.data('value'));

            // Reset all back to non-active
            $('#map_floor_selection_dropdown').find('a:not(.disabled)').each(function (index, element) {
                $(element).removeClass('active');
            });

            // Now toggled this floor on
            $this.addClass('active');
        });
    }

    /**
     *
     * @private
     */
    _setupMapObjectGroupVisibility() {
        let self = this;

        // Map object group visibility
        let $mapObjectGroupVisibilityDropdown = $('#map_map_object_group_visibility_dropdown');
        $mapObjectGroupVisibilityDropdown.empty();

        // Add the header
        $mapObjectGroupVisibilityDropdown.append($('<a>', {
            text: lang.get(`messages.header_map_object_group_label`),
            class: 'dropdown-item disabled'
        }));

        let map = getState().getDungeonMap();
        // After load complete, properly toggle the visibility. Then all layers get toggled properly
        map.register('map:mapobjectgroupsloaded', this, this._mapObjectGroupVisibilityChanged);

        let mapObjectGroups = map.mapObjectGroupManager.mapObjectGroups;
        let cookieHiddenMapObjectGroups = JSON.parse(Cookies.get('hidden_map_object_groups'));

        for (let i in mapObjectGroups) {
            if (mapObjectGroups.hasOwnProperty(i)) {
                let group = mapObjectGroups[i];

                // Skip those groups that we don't want to give the user power over to toggle
                if (!group.isUserToggleable()) {
                    continue;
                }

                // If any of the names of a map object group is found in the array, hide the selection
                let selected = true;
                for (let index in group.names) {
                    if (group.names.hasOwnProperty(index) && cookieHiddenMapObjectGroups.includes(group.names[index])) {
                        selected = false;
                        break;
                    }
                }

                $mapObjectGroupVisibilityDropdown.append($('<a>', {
                    text: lang.get(`messages.${group.names[0]}_map_object_group_label`),
                    class: 'dropdown-item ' + (selected ? 'active' : ''),
                    'data-group': group.names[0]
                }));
            }
        }

        // Trigger the change event now to initialize the map object groups
        $mapObjectGroupVisibilityDropdown.find('a:not(.disabled)').bind('click', function () {
            $(this).toggleClass('active');
            self._mapObjectGroupVisibilityChanged();
        });
        this._mapObjectGroupVisibilityChanged();
    }

    /**
     *
     * @private
     */
    _setupEnemyVisualTypes() {
        // Enemy visual types
        $('#map_enemy_visuals_dropdown').find('a:not(.disabled)').bind('click', function () {
            let $this = $(this);
            getState().setEnemyDisplayType($this.data('value'));

            // Reset all back to non-active
            $('#map_enemy_visuals_dropdown').find('a:not(.disabled)').each(function (index, element) {
                $(element).removeClass('active');
            });

            // Now toggled this visual on
            $this.addClass('active');
        });
    }

    /**
     *
     * @private
     */
    _setupFavorite() {
        let self = this;
        $('#favorite').bind('change', function (el) {
            self._favorite($('#favorite').is(':checked'));
        });

        $('.favorite_star').bind('mouseenter', function () {
            $(this).toggleClass('favorited');
        }).bind('mouseout', function () {
            $(this).toggleClass('favorited');
        }).bind('click', function () {
            // If it was not favorited, it is now
            let $favorite = $('#favorite');
            let favorited = parseInt($favorite.val()) === 0;

            self._favorite(favorited);

            $('.favorite_star').removeClass('favorited').hide();
            let $favorited;
            if (favorited) {
                $favorited = $('#route_favorited').addClass('favorited');
            } else {
                $favorited = $('#route_not_favorited').removeClass('favorited');
            }

            $favorited.show();

            $favorite.val(favorited ? '1' : '0');
        });
    }

    /**
     * Called when the focused enemy was changed
     * @param focusedEnemyChangedEvent
     * @private
     */
    _onFocusedEnemyChanged(focusedEnemyChangedEvent) {
        let focusedEnemy = focusedEnemyChangedEvent.data.focusedenemy;
        let isNull = focusedEnemy === null;
        // Show/hide based on being set or not
        // $('#enemy_info_container').toggle(!isNull);
        if (!isNull) {
            let visualData = focusedEnemy.getVisualData();
            if (visualData !== null) {
                $('#enemy_info_container').show().find('.card-title').html(focusedEnemy.npc.name);

                // Update the focused enemy in the sidebar
                let template = Handlebars.templates['map_sidebar_enemy_info_template'];

                $('#enemy_info_key_value_container').html(
                    template(visualData)
                );
                $('#enemy_report_enemy_id').val(focusedEnemy.id);
            }
        }
    }


    /**
     *
     * @private
     */
    _submitEnemyUserReport() {
        let enemyId = $('#enemy_report_enemy_id').val();

        $.ajax({
            type: 'POST',
            url: `/ajax/userreport/enemy/${enemyId}`,
            dataType: 'json',
            data: {
                category: $('#enemy_report_category').val(),
                username: $('#enemy_report_username').val(),
                message: $('#enemy_report_message').val(),
                contact_ok: $('#enemy_report_contact_ok').is(':checked') ? 1 : 0
            },
            beforeSend: function () {
                $('#userreport_enemy_modal_submit').hide();
                $('#userreport_enemy_modal_saving').show();
            },
            success: function (json) {
                $('#userreport_enemy_modal').modal('hide');
                showSuccessNotification(lang.get('messages.user_report_enemy_success'));
            },
            complete: function () {
                $('#userreport_enemy_modal_submit').show();
                $('#userreport_enemy_modal_saving').hide();
            }
        });
    }


    /**
     *
     * @private
     */
    _mapObjectGroupVisibilityChanged() {
        let $mapObjectGroupVisibilityDropdown = $('#map_map_object_group_visibility_dropdown');
        if ($mapObjectGroupVisibilityDropdown.length > 0) {
            let selected = $mapObjectGroupVisibilityDropdown.find('a.active').map(function (index, element) {
                return $(element).data('group');
            }).get();

            // Make a copy so we don't modify the OG array
            let toHide = MAP_OBJECT_GROUP_NAMES.slice();
            // Show everything that needs to be shown
            for (let i = 0; i < selected.length; i++) {
                let name = selected[i];
                let group = getState().getDungeonMap().mapObjectGroupManager.getByName(name);
                group.setVisibility(true);

                // Remove it from the toHide list
                toHide.splice(toHide.indexOf(name), 1);
            }

            // Update our cookie so that we know upon page refresh
            Cookies.set('hidden_map_object_groups', toHide);

            // Hide everything that needs to be hidden
            for (let index in toHide) {
                if (toHide.hasOwnProperty(index)) {
                    let group = getState().getDungeonMap().mapObjectGroupManager.getByName(toHide[index]);
                    if (group instanceof MapObjectGroup) {
                        group.setVisibility(false);
                    } else {
                        console.warn(`Unable to find map object group ${toHide[index]} - was a map object removed or does admin have different map object groups?`);
                    }
                }
            }
        }
    }

    /**
     *
     * @private
     */
    _fetchMdtExportString() {
        $.ajax({
            type: 'GET',
            url: `/ajax/${getState().getMapContext().getPublicKey()}/mdtExport`,
            dataType: 'json',
            beforeSend: function () {
                $('.mdt_export_loader_container').show();
                $('.mdt_export_result_container').hide();
            },
            success: function (json) {
                $('#mdt_export_result').val(json.mdt_string);

                // Inject the warnings, if there are any
                if (json.warnings.length > 0) {
                    (new MdtStringWarnings(json.warnings))
                        .render($('#mdt_export_result_warnings'));
                }

            },
            complete: function () {
                $('.mdt_export_loader_container').hide();
                $('.mdt_export_result_container').show();
            }
        });
    }

    /**
     * Favorites the current dungeon route, or not.
     * @param value bool
     */
    _favorite(value) {
        $.ajax({
            type: !value ? 'DELETE' : 'POST',
            url: `/ajax/${getState().getMapContext().getPublicKey()}/favorite`,
            dataType: 'json',
            success: function (json) {

            }
        });
    }


    /**
     * Rates the current dungeon route or unset it.
     * @param value int
     */
    _rate(value) {
        let isDelete = value === '';
        $.ajax({
            type: isDelete ? 'DELETE' : 'POST',
            url: `/ajax/${getState().getMapContext().getPublicKey()}/rate`,
            dataType: 'json',
            data: {
                rating: value
            },
            success: function (json) {
                // Update the new average rating
                $('#rating').barrating('set', Math.round(json.new_avg_rating));
            }
        });
    }

    /**
     *
     * @param snackbarAddEvent
     * @private
     */
    _onSnackbarAdd(snackbarAddEvent) {
        console.assert(this instanceof CommonMapsMap, 'this is not a CommonMapsMap', this);

        let template = Handlebars.templates['map_controls_snackbar_template'];

        $('#snackbar_container').append(
            template($.extend({}, getHandlebarsDefaultVariables(), {
                id: snackbarAddEvent.data.id,
                html: snackbarAddEvent.data.html
            }))
        );

        // Fire the onDomAdded event if necessary
        if (typeof snackbarAddEvent.data.onDomAdded === 'function') {
            snackbarAddEvent.data.onDomAdded(snackbarAddEvent.data.id);
        }
    }

    /**
     *
     * @param snackbarRemoveEvent
     * @private
     */
    _onSnackbarRemove(snackbarRemoveEvent) {
        console.assert(this instanceof CommonMapsMap, 'this is not a CommonMapsMap', this);

        $(`#${snackbarRemoveEvent.data.id}`).remove();
    }

    cleanup() {
        super.cleanup();

        getState().unregister('focusedenemy:changed', this);
        getState().unregister('snackbar:add', this);
        getState().unregister('snackbar:remove', this);
    }
}