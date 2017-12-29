<?php
return array (
		'demo' => array (
				'type' => CAuthItem::TYPE_ROLE,
				'description' => 'Demo account gives full read only access to the system',
				'bizRule' => '',
				'data' => '',
				'children' => array (
						'canlist',
						'canview'
				)
		),
        
		'admin' => array (
				'type' => CAuthItem::TYPE_ROLE,
				'description' => 'Can do everything',
				'bizRule' => '',
				'data' => '',
				'children' => array (
				        'ADD_DEPUTY_COMMISSIONER',
				        'ADD_CHIEF_MINISTER'
				)
		),        
        'ADD_CHIEF_MINISTER' => [
                'type' => CAuthItem::TYPE_OPERATION,
                'description' => 'To allow saving data',
                'data' => '',
                'bizRule' => '',
                'children' => [
                ]
        ],
        'ADD_DEPUTY_COMMISSIONER' => [
                'type' => CAuthItem::TYPE_OPERATION,
                'description' => 'To allow saving data',
                'data' => '',
                'bizRule' => '',
                'children' => [
                ]
        ],
);
