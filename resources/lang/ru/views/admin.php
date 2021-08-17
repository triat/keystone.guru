<?php

return [
    'dungeon'    => [
        'edit' => [
            'active'                        => '@todo ru: .dungeon.edit.active',
            'zone_id'                       => '@todo ru: .dungeon.edit.zone_id',
            'mdt_id'                        => '@todo ru: .dungeon.edit.mdt_id',
            'dungeon_name'                  => '@todo ru: .dungeon.edit.dungeon_name',
            'key'                           => '@todo ru: .dungeon.edit.key',
            'enemy_forces_required'         => '@todo ru: .dungeon.edit.enemy_forces_required',
            'enemy_forces_required_teeming' => '@todo ru: .dungeon.edit.enemy_forces_required_teeming',
            'timer_max_seconds'             => '@todo ru: .dungeon.edit.timer_max_seconds',
            'submit'                        => '@todo ru: .dungeon.edit.submit',

            'floor_management'     => '@todo ru: .dungeon.edit.floor_management',
            'add_floor'            => '@todo ru: .dungeon.edit.add_floor',
            'table_header_id'      => '@todo ru: .dungeon.edit.table_header_id',
            'table_header_index'   => '@todo ru: .dungeon.edit.table_header_index',
            'table_header_name'    => '@todo ru: .dungeon.edit.table_header_name',
            'table_header_actions' => '@todo ru: .dungeon.edit.table_header_actions',
            'floor_edit_edit'      => '@todo ru: .dungeon.edit.floor_edit_edit',
            'floor_edit_mapping'   => '@todo ru: .dungeon.edit.floor_edit_mapping',
        ],
        'list' => [
            'title'                             => '@todo ru: .dungeon.list.title',
            'header'                            => '@todo ru: .dungeon.list.header',
            'table_header_active'               => '@todo ru: .dungeon.list.table_header_active',
            'table_header_expansion'            => '@todo ru: .dungeon.list.table_header_expansion',
            'table_header_name'                 => '@todo ru: .dungeon.list.table_header_name',
            'table_header_enemy_forces'         => '@todo ru: .dungeon.list.table_header_enemy_forces',
            'table_header_enemy_forces_teeming' => '@todo ru: .dungeon.list.table_header_enemy_forces_teeming',
            'table_header_timer'                => '@todo ru: .dungeon.list.table_header_timer',
            'table_header_actions'              => '@todo ru: .dungeon.list.table_header_actions',
            'edit'                              => '@todo ru: .dungeon.list.edit',
        ],
    ],
    'expansion'  => [
        'edit' => [
            'name'          => '@todo ru: .expansion.edit.name',
            'shortname'     => '@todo ru: .expansion.edit.shortname',
            'icon'          => '@todo ru: .expansion.edit.icon',
            'current_image' => '@todo ru: .expansion.edit.current_image',
            'color'         => '@todo ru: .expansion.edit.color',
            'edit'          => '@todo ru: .expansion.edit.edit',
            'submit'        => '@todo ru: .expansion.edit.submit',
        ],
        'list' => [
            'title'                => '@todo ru: .expansion.list.title',
            'header'               => '@todo ru: .expansion.list.header',
            'create_expansion'     => '@todo ru: .expansion.list.create_expansion',
            'table_header_icon'    => '@todo ru: .expansion.list.table_header_icon',
            'table_header_id'      => '@todo ru: .expansion.list.table_header_id',
            'table_header_name'    => '@todo ru: .expansion.list.table_header_name',
            'table_header_color'   => '@todo ru: .expansion.list.table_header_color',
            'table_header_actions' => '@todo ru: .expansion.list.table_header_actions',
            'edit'                 => '@todo ru: .expansion.list.edit',
        ]
    ],
    'floor'      => [
        'edit'    => [
            'index'                  => '@todo ru: .floor.edit.index',
            'floor_name'             => '@todo ru: .floor.edit.floor_name',
            'min_enemy_size'         => '@todo ru: .floor.edit.min_enemy_size',
            'max_enemy_size'         => '@todo ru: .floor.edit.max_enemy_size',
            'default'                => '@todo ru: .floor.edit.default',
            'default_title'          => '@todo ru: .floor.edit.default_title',
            'connected_floors'       => '@todo ru: .floor.edit.connected_floors',
            'connected_floors_title' => '@todo ru: .floor.edit.connected_floors_title',
            'connected'              => '@todo ru: .floor.edit.connected',
            'direction'              => '@todo ru: .floor.edit.direction',
            'floor_direction'        => [
                'none'  => '@todo ru: .floor.edit.floor_direction.none',
                'up'    => '@todo ru: .floor.edit.floor_direction.up',
                'down'  => '@todo ru: .floor.edit.floor_direction.down',
                'left'  => '@todo ru: .floor.edit.floor_direction.left',
                'right' => '@todo ru: .floor.edit.floor_direction.right',
            ],
            'submit'                 => '@todo ru: .floor.edit.submit',
        ],
        'mapping' => [

        ]
    ],
    'npc'        => [
        'edit' => [
            'title'                          => '@todo ru: .npc.edit.title',
            'name'                           => '@todo ru: .npc.edit.name',
            'game_id'                        => '@todo ru: .npc.edit.game_id',
            'classification'                 => '@todo ru: .npc.edit.classification',
            'aggressiveness'                 => '@todo ru: .npc.edit.aggressiveness',
            'class'                          => '@todo ru: .npc.edit.class',
            'base_health'                    => '@todo ru: .npc.edit.base_health',
            'enemy_forces'                   => '@todo ru: .npc.edit.enemy_forces',
            'enemy_forces_teeming'           => '@todo ru: .npc.edit.enemy_forces_teeming',
            'dangerous'                      => '@todo ru: .npc.edit.dangerous',
            'truesight'                      => '@todo ru: .npc.edit.truesight',
            'bursting'                       => '@todo ru: .npc.edit.bursting',
            'bolstering'                     => '@todo ru: .npc.edit.bolstering',
            'sanguine'                       => '@todo ru: .npc.edit.sanguine',
            'bolstering_npc_whitelist'       => '@todo ru: .npc.edit.bolstering_npc_whitelist',
            'bolstering_npc_whitelist_count' => '@todo ru: .npc.edit.bolstering_npc_whitelist_count',
            'spells'                         => '@todo ru: .npc.edit.spells',
            'spells_count'                   => '@todo ru: .npc.edit.spells_count',
            'submit'                         => '@todo ru: .npc.edit.submit',
            'save_as_new_npc'                => '@todo ru: .npc.edit.save_as_new_npc'
        ],
        'list' => [
            'title'                       => '@todo ru: .npc.list.title',
            'header'                      => '@todo ru: .npc.list.header',
            'create_npc'                  => '@todo ru: .npc.list.create_npc',
            'table_header_id'             => '@todo ru: .npc.list.table_header_id',
            'table_header_name'           => '@todo ru: .npc.list.table_header_name',
            'table_header_dungeon'        => '@todo ru: .npc.list.table_header_dungeon',
            'table_header_enemy_forces'   => '@todo ru: .npc.list.table_header_enemy_forces',
            'table_header_classification' => '@todo ru: .npc.list.table_header_classification',
            'table_header_actions'        => '@todo ru: .npc.list.table_header_actions',
        ]
    ],
    'release'    => [
        'edit' => [
            'header'      => '@todo ru: .release.edit.header',
            'version'     => '@todo ru: .release.edit.version',
            'title'       => '@todo ru: .release.edit.title',
            'silent'      => '@todo ru: .release.edit.silent',
            'spotlight'   => '@todo ru: .release.edit.spotlight',
            'changelog'   => '@todo ru: .release.edit.changelog',
            'description' => '@todo ru: .release.edit.description',
            'ticket_nr'   => '@todo ru: .release.edit.ticket_nr',
            'change'      => '@todo ru: .release.edit.change',
            'add_change'  => '@todo ru: .release.edit.add_change',
            'edit'        => '@todo ru: .release.edit.edit',
            'submit'      => '@todo ru: .release.edit.submit',
        ],
        'list' => [
            'title'                => '@todo ru: .release.list.title',
            'view_releases'        => '@todo ru: .release.list.view_releases',
            'create_release'       => '@todo ru: .release.list.create_release',
            'table_header_id'      => '@todo ru: .release.list.table_header_id',
            'table_header_version' => '@todo ru: .release.list.table_header_version',
            'table_header_title'   => '@todo ru: .release.list.table_header_title',
            'table_header_actions' => '@todo ru: .release.list.table_header_actions',
            'edit'                 => '@todo ru: .release.list.edit'
        ]
    ],
    'spell'      => [
        'edit' => [
            'title'             => '@todo ru: .spell.edit.title',
            'game_id'           => '@todo ru: .spell.edit.game_id',
            'name'              => '@todo ru: .spell.edit.name',
            'icon_name'         => '@todo ru: .spell.edit.icon_name',
            'dispel_type'       => '@todo ru: .spell.edit.dispel_type',
            'schools'           => '@todo ru: .spell.edit.schools',
            'aura'              => '@todo ru: .spell.edit.aura',
            'submit'            => '@todo ru: .spell.edit.submit',
            'save_as_new_spell' => '@todo ru: .spell.edit.save_as_new_spell',
        ],
        'list' => [
            'title'                => '@todo ru: .spell.list.title',
            'header'               => '@todo ru: .spell.list.header',
            'create_spell'         => '@todo ru: .spell.list.create_spell',
            'table_header_icon'    => '@todo ru: .spell.list.table_header_icon',
            'table_header_id'      => '@todo ru: .spell.list.table_header_id',
            'table_header_name'    => '@todo ru: .spell.list.table_header_name',
            'table_header_actions' => '@todo ru: .spell.list.table_header_actions',
            'edit'                 => '@todo ru: .spell.list.edit'
        ]
    ],
    'tools'      => [

    ],
    'user'       => [
        'list' => [
            'title'                   => '@todo ru: .user.list.title',
            'header'                  => '@todo ru: .user.list.header',
            'table_header_id'         => '@todo ru: .user.list.table_header_id',
            'table_header_name'       => '@todo ru: .user.list.table_header_name',
            'table_header_email'      => '@todo ru: .user.list.table_header_email',
            'table_header_routes'     => '@todo ru: .user.list.table_header_routes',
            'table_header_roles'      => '@todo ru: .user.list.table_header_roles',
            'table_header_registered' => '@todo ru: .user.list.table_header_registered',
            'table_header_actions'    => '@todo ru: .user.list.table_header_actions',
            'table_header_patreons'   => '@todo ru: .user.list.table_header_patreons',
        ]
    ],
    'userreport' => [
        'list' => [

        ]
    ]
];
