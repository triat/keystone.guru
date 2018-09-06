$(function () {
    $("#faction_id").bind('change', _factionChanged);
    $(".raceselect").bind('change', _raceChanged);

    // Add icons to the faction dropdown
    $.each($("#faction_id option"), function (index, value) {
        let faction = _factions[index];
        let html = $('#template_faction_dropdown_icon_' + faction.name.toLowerCase()).html();
        $(value).data('content', html);
    });
});

function _factionChanged() {
    console.log(">> _factionChanged");

    let newFaction = parseInt($("#faction_id").val());
    let $raceSelect = $("select.raceselect");
    let $classSelect = $("select.classselect");

    // Remove all existing options
    $raceSelect.find('option').remove();
    $classSelect.find('option').remove();
    $classSelect.append(jQuery('<option>', {
        value: '0', // Laravel can then accept values that haven't been set
        text: 'Class...'
    }));

    // Re-fill the races
    $raceSelect.append(jQuery('<option>', {
        value: '0', // Laravel can then accept values that haven't been set
        text: 'Race...'
    }));

    for (let i = 0; i < _racesClasses.length; i++) {
        let raceClass = _racesClasses[i];
        console.log(raceClass.faction_id, newFaction, raceClass.faction_id === newFaction);
        if (raceClass.faction_id === newFaction) {
            $raceSelect.append(jQuery('<option>', {
                value: raceClass.id,
                text: raceClass.name
            }));
        }
    }

    let $selectPicker = $('.selectpicker');
    $selectPicker.selectpicker('refresh');
    $selectPicker.selectpicker('render');

    console.log('OK _factionChanged');
}

function _raceChanged() {
    console.log('>> _raceChanged');

    let $raceSelect = $(this);
    let raceId = parseInt($raceSelect.val());
    let $classSelect = $('.classselect').find("[data-id='" + $raceSelect.data('id') + "']");

    $classSelect.find('option').remove();

    // Find the raceclass for the class we've selected
    let raceClass = null;
    for (let i = 0; i < _racesClasses.length; i++) {
        if (_racesClasses[i].id === raceId) {
            raceClass = _racesClasses[i];
            break;
        }
    }

    // May be null if unspecified was set
    if (raceClass !== null) {
        // Match the raceClass to the classDetails
        for (let i = 0; i < raceClass.classes.length; i++) {
            let rClass = raceClass.classes[i];
            // Find the details
            for (let j = 0; j < _classDetails.length; j++) {
                let classDetail = _classDetails[j];
                // If found
                if (classDetail.id === rClass.id) {
                    // Display it
                    $classSelect.append(jQuery('<option>', {
                        value: classDetail.id, //zzz
                        text: classDetail.name,
                        'data-content': $('#template_class_dropdown_icon_' + classDetail.key).html()
                    }));
                    break;
                }
            }
        }
    }

    // Refresh always; we removed options
    let $selectPicker = $('.selectpicker');
    $selectPicker.selectpicker('refresh');
    $selectPicker.selectpicker('render');

    console.log('OK _raceChanged');
}