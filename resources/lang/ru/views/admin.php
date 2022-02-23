<?php

return [
    'dungeon'    => [
        'edit' => [
            'title_new'                     => 'Новое подземелье',
            'title_edit'                    => 'Редактировать подземелье',
            'header_new'                    => 'Новое подземелье',
            'header_edit'                   => 'Редактировать подземелье',
            'active'                        => 'Действующий',
            'zone_id'                       => 'ID зоны',
            'mdt_id'                        => 'MDT ID',
            'dungeon_name'                  => 'Название подземелья',
            'key'                           => 'Ключ',
            'slug'                          => 'Жетон',
            'enemy_forces_required'         => 'Требуется больше сил врага',
            'enemy_forces_required_teeming' => 'Требуется больше сил врага (Кишащий)',
            'timer_max_seconds'             => 'Таймер (секунды)',
            'submit'                        => 'Подтвердить',

            'floor_management'     => 'Управление уровнями',
            'add_floor'            => 'Добавить уровень',
            'table_header_id'      => 'ID',
            'table_header_index'   => 'Индекс',
            'table_header_name'    => 'Имя',
            'table_header_actions' => 'Действия',
            'floor_edit_edit'      => 'Редактировать',
            'floor_edit_mapping'   => 'Разместить',
        ],
        'list' => [
            'title'                             => 'Список подземелий',
            'header'                            => 'Показать подземелья',
            'table_header_active'               => 'Действующий',
            'table_header_expansion'            => 'Опыт',
            'table_header_name'                 => 'Имя',
            'table_header_enemy_forces'         => 'Силы врага',
            'table_header_enemy_forces_teeming' => 'Кишащий СВ',
            'table_header_timer'                => 'Таймер',
            'table_header_actions'              => 'Действия',
            'edit'                              => 'Редактировать',
        ],
    ],
    'expansion'  => [
        'edit' => [
            'title_new'     => 'Новое дополнение',
            'header_new'    => 'Новое дополнение',
            'title_edit'    => 'Редактировать дополнение',
            'header_edit'   => 'Редактировать дополнение',
            'active'        => 'Действующий',
            'name'          => 'Название',
            'shortname'     => 'Короткое название',
            'icon'          => 'Иконка',
            'current_image' => 'Текущее изображение',
            'color'         => 'Цвет',
            'edit'          => 'Редактировать',
            'submit'        => 'Подтвердить',
        ],
        'list' => [
            'title'                => 'Список дополнений',
            'header'               => 'Показать дополнение',
            'create_expansion'     => 'Создать дополнение',
            'table_header_active'  => 'Активировать',
            'table_header_icon'    => 'Иконка',
            'table_header_id'      => 'ID',
            'table_header_name'    => 'Название',
            'table_header_color'   => 'Цвет',
            'table_header_actions' => 'Действия',
            'edit'                 => 'Редактировать',
        ],
    ],
    'floor'      => [
        'flash'   => [
            'invalid_floor_id' => 'Этаж %s не является частью подземелья %s',
            'floor_updated'    => 'Этаж обновлен',
            'floor_created'    => 'Этаж создан',
        ],
        'edit'    => [
            'title_new'              => 'Новый этаж - %s',
            'header_new'             => 'Новый этаж - %s',
            'title_edit'             => 'Редактировать этаж - %s',
            'header_edit'            => 'Редактировать этаж - %s',
            'index'                  => 'Индекс',
            'floor_name'             => 'Название этажа',
            'min_enemy_size'         => 'Минимальное количество врагов (пусто по умолчанию (%s))',
            'max_enemy_size'         => 'Максимальное количество врагов (пусто по умолчанию (%s))',
            'default'                => 'По умолчанию',
            'default_title'          => 'Если отмечено по умолчанию, этот этаж открывается первым при редактировании маршрутов для этого подземелья (по умолчанию должен быть отмечен только один).',
            'connected_floors'       => 'Присоединить этаж',
            'connected_floors_title' => 'Присоединить этаж - это любой другой этаж, на который мы можем подняться с этого этажа.',
            'connected'              => 'Присоединить',
            'direction'              => 'Отсоединить',
            'floor_direction'        => [
                'none'  => 'Нет',
                'up'    => 'Верх',
                'down'  => 'Низ',
                'left'  => 'Левый',
                'right' => 'Правый',
            ],
            'submit'                 => 'Подтвердить',
        ],
        'mapping' => [
            'title'  => 'Редактировать отображение - %s',
            'header' => 'Редактировать отображение - %s',
        ],
    ],
    'npc'        => [
        'flash' => [
            'npc_updated' => 'NPC обновлены',
            'npc_created' => 'NPC %s создан',
        ],
        'edit'  => [
            'title_new'                      => 'Новый NPC',
            'header_new'                     => 'Новый NPC',
            'title_edit'                     => 'Редактировать NPC',
            'header_edit'                    => 'Редактировать NPC',
            'name'                           => 'Имя',
            'game_id'                        => 'Игровое ID',
            'classification'                 => 'Классификация',
            'aggressiveness'                 => 'Агрессивность',
            'class'                          => 'Класс',
            'base_health'                    => 'Базовое здоровье',
            'enemy_forces'                   => 'Отряд врага (-1 если неизвестно)',
            'enemy_forces_teeming'           => 'Кишащий отряд врага (-1 если без изменений)',
            'dangerous'                      => 'Подземелье',
            'truesight'                      => 'Истинное зрение',
            'bursting'                       => 'Взрывной',
            'bolstering'                     => 'Усиливающий',
            'sanguine'                       => 'Кровавый',
            'bolstering_npc_whitelist'       => 'Белый список Усиливающий NPC',
            'bolstering_npc_whitelist_count' => '{0} NPCs',
            'spells'                         => 'Способность',
            'spells_count'                   => '{0} Способность',
            'submit'                         => 'Подтвердить',
            'save_as_new_npc'                => 'Сохранить нового NPC',
            'all_npcs'                       => 'Все NPC',
            'all_dungeons'                   => 'Все подземелья',
        ],
        'list'  => [
            'all_dungeons'                => 'Все',
            'title'                       => 'Список NPC',
            'header'                      => 'Показать NPC',
            'create_npc'                  => 'Создать NPC',
            'table_header_id'             => 'ID',
            'table_header_name'           => 'Имя',
            'table_header_dungeon'        => 'Подземелье',
            'table_header_enemy_forces'   => 'Отряд врага',
            'table_header_enemy_count'    => 'Счетчик врагов',
            'table_header_classification' => 'Классификация',
            'table_header_actions'        => 'Действия',
        ],
    ],
    'release'    => [
        'edit' => [
            'title_new'   => 'Новый релиз',
            'header_new'  => 'Новый релиз',
            'title_edit'  => 'Редактировать релиз',
            'header_edit' => 'Редактировать релиз',
            'version'     => 'Версия',
            'title'       => 'Название',
            'silent'      => 'Немой',
            'spotlight'   => 'Подсветка',
            'changelog'   => 'Список изменений',
            'description' => 'Описание',
            'ticket_nr'   => 'Обращение №',
            'change'      => 'Изменения',
            'add_change'  => 'Добавить изменение',
            'edit'        => 'Редактировать',
            'submit'      => 'Подтвердить',
        ],
        'list' => [
            'title'                => 'Список релизов',
            'view_releases'        => 'Показать релизы',
            'create_release'       => 'Создать релиз',
            'table_header_id'      => 'ID',
            'table_header_version' => 'Версия',
            'table_header_title'   => 'Название',
            'table_header_actions' => 'Действия',
            'edit'                 => 'Редактировать',
        ],
    ],
    'spell'      => [
        'edit' => [
            'title_new'         => 'Новая способность',
            'header_new'        => 'Новая способность',
            'title_edit'        => 'Редактировать способность',
            'header_edit'       => 'Редактировать способность',
            'game_id'           => 'Игровое ID',
            'name'              => 'Название',
            'icon_name'         => 'Название иконки',
            'dispel_type'       => 'Тип развеивания',
            'schools'           => 'Школа',
            'aura'              => 'Аура',
            'submit'            => 'Подтвердить',
            'save_as_new_spell' => 'Сохранить как новую способность',
        ],
        'list' => [
            'title'                => 'Список способностей',
            'header'               => 'Показать способность',
            'create_spell'         => 'Создать способность',
            'table_header_icon'    => 'Иконка',
            'table_header_id'      => 'ID',
            'table_header_name'    => 'Название',
            'table_header_actions' => 'Действия',
            'edit'                 => 'Редактировать',
        ],
    ],
    'tools'      => [
        'datadump'     => [
            'viewexporteddungeondata' => [
                'title'   => 'Экспортировано!',
                'header'  => 'Данные подземелья сброшены',
                'content' => 'Экспортировано!',
            ],
            'viewexportedrelease'     => [
                'title'   => 'Экспортировано!',
                'header'  => 'Данные подземелья сброшены',
                'content' => 'Экспортировано!',
            ],
        ],
        'dungeonroute' => [
            'view'         => [
                'title'      => 'Показать подземелье',
                'header'     => 'Показать подземелье',
                'public_key' => 'Публичный ключ маршрута подземелья',
                'submit'     => 'Подтвердить',
            ],
            'viewcontents' => [
                'title'  => 'Просмотреть содержимое для :dungeonRouteTitle',
                'header' => 'Просмотреть содержимое для %s',
            ],
        ],
        'enemyforces'       => [
            'title'                        => '@todo ru: .tools.enemyforces.title',
            'header'                       => '@todo ru: .tools.enemyforces.header',
            'paste_mennos_export_json'     => '@todo ru: .tools.enemyforces.paste_mennos_export_json',
            'submit'                       => '@todo ru: .tools.enemyforces.submit',
        ],
        'exception'    => [
            'select' => [
                'title'                     => 'Сброс исключений',
                'header'                    => 'Сброс исключений',
                'select_exception_to_throw' => 'Выберите исключение, которое нужно сбросить',
                'submit'                    => 'Подтвердить',
            ],
        ],
        'mdt'          => [
            'diff'         => [
                'title'                 => 'MDT Различия',
                'header'                => 'MDT Различия',
                'headers'               => [
                    'mismatched_health'               => 'Здоровье не соответствует',
                    'mismatched_enemy_count'          => 'Количества врагов не соответствует',
                    'mismatched_enemy_type'           => 'Тип врага не соответствует ',
                    'missing_npc'                     => 'Отсутствует NPC',
                    'mismatched_enemy_forces'         => 'Отсутствует отряд врага',
                    'mismatched_enemy_forces_teeming' => 'Отсутствует отряд врага (Кишащий)',
                ],
                'table_header_dungeon'  => 'Подземелье',
                'table_header_npc'      => 'NPC',
                'table_header_message'  => 'Сообщение',
                'table_header_actions'  => 'Действия',
                'no_dungeon_name_found' => 'Название подземелья не найдено',
                'no_npc_name_found'     => 'Название NPC не найдено',
                'npc_message'           => ':npcName (:npcId, :count использованы)',
                'apply_mdt_kg'          => 'Применить (MDT -> KG)',
            ],
            'dungeonroute' => [
                'title'      => 'Просмотреть маршрут подземелья как строку для MDT',
                'header'     => 'Просмотреть маршрут подземелья как строку для MDT',
                'public_key' => 'Публичный ключ',
                'submit'     => 'Подтвердить',
            ],
            'string'       => [
                'title'                        => 'Просмотр содержимое строки MDT',
                'header'                       => 'Просмотр содержимое строки MDT',
                'paste_your_mdt_export_string' => 'Вставьте строку экспорта Mythic Dungeon Tools',
                'submit'                       => 'Подтвердить',
            ],
        ],
        'npcimport'    => [
            'title'                   => 'Массовый импорт NPC',
            'header'                  => 'Массовый импорт NPC',
            'paste_npc_import_string' => 'Вставьте строку импорта NPC',
            'submit'                  => 'Подтвердить',
        ],
        'list'         => [
            'title'            => 'Инструменты администратора',
            'header'           => 'Инструменты администратора',
            'header_tools'     => 'Инструменты',
            'subheader_import' => 'Импорт',
            'mass_import_npcs' => 'Массовый импорт NPC',

            'subheader_dungeonroute'    => 'Маршрут подземелья',
            'view_dungeonroute_details' => 'Показать детали маршрута подземелья',

            'subheader_mdt'                   => 'MDT',
            'view_mdt_string'                 => '@todo ru: .tools.list.view_mdt_string',
            'view_mdt_string_as_dungeonroute' => 'Просмотреть строку MDT как маршрут подземелья',
            'view_dungeonroute_as_mdt_string' => 'Просмотреть маршрут подземелья как строку MDT',
            'view_mdt_diff'                   => 'Просмотр различия с MDT',

            'subheader_enemy_forces' => '@todo ru: .tools.list.subheader_enemy_forces',
            'enemy_forces_import'    => '@todo ru: .tools.list.enemy_forces_import',

            'subheader_misc'     => 'Разное',
            'drop_caches'        => 'Сбросить кеш',
            'throw_an_exception' => 'Сбросить исключения',

            'subheader_actions'   => 'Действия',
            'export_dungeon_data' => 'Экспорт данных о подземельях',
            'export_releases'     => 'Экспорт релизов',
        ],
    ],
    'user'       => [
        'list' => [
            'title'                   => 'Список пользователей',
            'header'                  => 'Показать пользователя',
            'table_header_id'         => 'ID',
            'table_header_name'       => 'Имя',
            'table_header_email'      => 'Email',
            'table_header_routes'     => 'Маршруты',
            'table_header_roles'      => 'Роли',
            'table_header_registered' => 'Зарегистрирован',
            'table_header_actions'    => 'Действия',
            'table_header_patreons'   => 'Patreon',
        ],
    ],
    'userreport' => [
        'list' => [
            'title'                    => 'Отчеты пользователей',
            'header'                   => 'Просмотр отчетов пользователей',
            'table_header_id'          => 'ID',
            'table_header_author_name' => 'Имя автора',
            'table_header_category'    => 'Категория',
            'table_header_message'     => 'Сообщение',
            'table_header_contact_at'  => 'Адрес для связи',
            'table_header_create_at'   => 'Создано',
            'table_header_actions'     => 'Действия',
            'handled'                  => 'Обработано',
        ],
    ],
];
