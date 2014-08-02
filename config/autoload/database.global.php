<?php
return array (
	'doctrine' => array (
		'connection' => array (
			'orm_default'=> array (
				'driverClass' => 'Doctrine\DBAL\Driver\PDOPgSql\Driver',
					'params' => array(
						'host'     => 'hostname',
						'port'     => '5432',
						'user'     => 'username',
						'password' => 'passxxxx',
						'dbname'   => 'schema'
				)
			),
		),
		'driver' => array (
			'zfcuser_entity' => array(
				'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
				//'cache' => 'array',
                'paths' => array(
                    __DIR__ . '/../../module/MakiUser/src/MakiUser/Entity'
                ),
            ),

			'orm_default' => array (
				'drivers' => array (
					'MakiUser\Entity' => 'zfcuser_entity',				
				) 
			) 
		) 
	),


	'zfcuser' => array(
        // telling ZfcUser to use our own class
        'user_entity_class'       => 'MakiUser\Entity\User',
        // telling ZfcUserDoctrineORM to skip the entities it defines
        'enable_default_entities' => false,
    ),


);
