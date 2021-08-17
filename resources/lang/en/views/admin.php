<?php

return [
    'dungeon'    => [
        'edit' => [
            'active'                        => 'Active',
            'zone_id'                       => 'Zone ID',
            'mdt_id'                        => 'MDT ID',
            'dungeon_name'                  => 'Dungeon name',
            'key'                           => 'Key',
            'enemy_forces_required'         => 'Enemy forces required',
            'enemy_forces_required_teeming' => 'Enemy forces required (teeming)',
            'timer_max_seconds'             => 'Timer (seconds)',
            'submit'                        => 'Submit',

            'floor_management'     => 'Floor management',
            'add_floor'            => 'Add floor',
            'table_header_id'      => 'Id',
            'table_header_index'   => 'Index',
            'table_header_name'    => 'Name',
            'table_header_actions' => 'Actions',
            'floor_edit_edit'      => 'Edit',
            'floor_edit_mapping'   => 'Mapping',
        ],
        'list' => [
            'title'                             => 'Dungeon listing',
            'header'                            => 'View dungeons',
            'table_header_active'               => 'Active',
            'table_header_expansion'            => 'Exp.',
            'table_header_name'                 => 'Name',
            'table_header_enemy_forces'         => 'Enemy Forces',
            'table_header_enemy_forces_teeming' => 'Teeming EF',
            'table_header_timer'                => 'Timer',
            'table_header_actions'              => 'Actions',
            'edit'                              => 'Edit',
        ],
    ],
    'expansion'  => [
        'edit' => [
            'name'          => 'Name',
            'shortname'     => 'Shortname',
            'icon'          => 'Icon',
            'current_image' => 'Current image',
            'color'         => 'Color',
            'edit'          => 'Edit',
            'submit'        => 'Submit',
        ],
        'list' => [
            'title'                => 'Expansion listing',
            'header'               => 'View expansions',
            'create_expansion'     => 'Create expansion',
            'table_header_icon'    => 'Icon',
            'table_header_id'      => 'Id',
            'table_header_name'    => 'Name',
            'table_header_color'   => 'Color',
            'table_header_actions' => 'Actions',
            'edit'                 => 'Edit',
        ]
    ],
    'floor'      => [
        'edit'    => [
            'index'                  => 'Index',
            'floor_name'             => 'Floor name',
            'min_enemy_size'         => 'Minimum enemy size (empty for default (%s))',
            'max_enemy_size'         => 'Maximum enemy size (empty for default (%s))',
            'default'                => 'Default',
            'default_title'          => 'If marked as default, this floor is opened first when editing routes for this dungeon (only one should be marked as default)',
            'connected_floors'       => 'Connected floors',
            'connected_floors_title' => 'A connected floor is any other floor that we may reach from this floor',
            'connected'              => 'Connected',
            'direction'              => 'Direction',
            'floor_direction'        => [
                'none'  => 'None',
                'up'    => 'Up',
                'down'  => 'Down',
                'left'  => 'Left',
                'right' => 'Right',
            ],
            'submit'                 => 'Submit',
        ],
        'mapping' => [

        ]
    ],
    'npc'        => [
        'edit' => [
            'title'                          => 'Edit Npc',
            'name'                           => 'Name',
            'game_id'                        => 'Game ID',
            'classification'                 => 'Classification',
            'aggressiveness'                 => 'Aggressiveness',
            'class'                          => 'Class',
            'base_health'                    => 'Base health',
            'enemy_forces'                   => 'Enemy forces (-1 for unknown)',
            'enemy_forces_teeming'           => 'Enemy forces teeming (-1 for same as normal)',
            'dangerous'                      => 'Dangerous',
            'truesight'                      => 'Truesight',
            'bursting'                       => 'Bursting',
            'bolstering'                     => 'Bolstering',
            'sanguine'                       => 'Sanguine',
            'bolstering_npc_whitelist'       => 'Bolstering NPC Whitelist',
            'bolstering_npc_whitelist_count' => '{0} NPCs',
            'spells'                         => 'Spells',
            'spells_count'                   => '{0} Spells',
            'submit'                         => 'Submit',
            'save_as_new_npc'                => 'Save as new npc'
        ],
        'list' => [
            'title'                       => 'Npc listing',
            'header'                      => 'View NPCs',
            'create_npc'                  => 'Create NPC',
            'table_header_id'             => 'Id',
            'table_header_name'           => 'Name',
            'table_header_dungeon'        => 'Dungeon',
            'table_header_enemy_forces'   => 'Enemy forces',
            'table_header_classification' => 'Classification',
            'table_header_actions'        => 'Action',
        ]
    ],
    'release'    => [
        'edit' => [
            'header'      => 'New release',
            'version'     => 'Version',
            'title'       => 'Title',
            'silent'      => 'Silent',
            'spotlight'   => 'Spotlight',
            'changelog'   => 'Changelog',
            'description' => 'Description',
            'ticket_nr'   => 'Ticket nr.',
            'change'      => 'Change',
            'add_change'  => 'Add change',
            'edit'        => 'Edit',
            'submit'      => 'Submit',
        ],
        'list' => [
            'title'                => 'Release listing',
            'view_releases'        => 'View releases',
            'create_release'       => 'Create release',
            'table_header_id'      => 'Id',
            'table_header_version' => 'Version',
            'table_header_title'   => 'Title',
            'table_header_actions' => 'Actions',
            'edit'                 => 'Edit'
        ]
    ],
    'spell'      => [
        'edit' => [
            'title'             => 'Edit spell',
            'game_id'           => 'Game ID',
            'name'              => 'Name',
            'icon_name'         => 'Icon name',
            'dispel_type'       => 'Dispel type',
            'schools'           => 'Schools',
            'aura'              => 'Aura',
            'submit'            => 'Submit',
            'save_as_new_spell' => 'Save as new spell',
        ],
        'list' => [
            'title'                => 'Spell listing',
            'header'               => 'View spells',
            'create_spell'         => 'Create spell',
            'table_header_icon'    => 'Icon',
            'table_header_id'      => 'Id',
            'table_header_name'    => 'Name',
            'table_header_actions' => 'Actions',
            'edit'                 => 'Edit'
        ]
    ],
    'tools'      => [

    ],
    'user'       => [
        'list' => [
            'title'                   => 'User list',
            'header'                  => 'View users',
            'table_header_id'         => 'Id',
            'table_header_name'       => 'Name',
            'table_header_email'      => 'Email',
            'table_header_routes'     => 'Routes',
            'table_header_roles'      => 'Roles',
            'table_header_registered' => 'Registered',
            'table_header_actions'    => 'Actions',
            'table_header_patreons'   => 'Patreon',
        ]
    ],
    'userreport' => [
        'list' => [
            'title'                    => 'User reports',
            'header'                   => 'View user reports',
            'table_header_id'          => 'Id',
            'table_header_author_name' => 'Author name',
            'table_header_category'    => 'Category',
            'table_header_message'     => 'Message',
            'table_header_contact_at'  => 'Contact at',
            'table_header_create_at'   => 'Created at',
            'table_header_actions'     => 'Actions',
            'handled'                  => 'Handled'
        ]
    ]
];
