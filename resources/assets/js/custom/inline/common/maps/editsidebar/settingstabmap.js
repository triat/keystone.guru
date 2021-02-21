class SettingsTabMap extends SettingsTab {

    constructor(map, options) {
        super(map, options);

    }

    activate() {
        // Setup line weight
        $('#edit_route_freedraw_options_weight').bind('change', function (changeEvent) {
            let weight = $('#edit_route_freedraw_options_weight :selected').val();

            c.map.polyline.defaultWeight = weight;

            Cookies.set('polyline_default_weight', weight);

            getState().getDungeonMap().refreshPather();
        })// -1 for value to index conversion
        .val(c.map.polyline.defaultWeight);


        // Unkilled enemy opacity
        $('#map_settings_unkilled_enemy_opacity').bind('change', function () {
            getState().setUnkilledEnemyOpacity(parseInt($(this).val()));
        });

        // Unkilled important enemy opacity
        $('#map_settings_unkilled_important_enemy_opacity').bind('change', function () {
            getState().setUnkilledImportantEnemyOpacity(parseInt($(this).val()));
        });
    }
}