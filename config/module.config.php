<?php
namespace CsnUser; // Important for Doctrine otherwise can not find the Entities

return array(
	'static_salt' => 'aFGQ475SDsdfsaf2342',
	'controllers' => array(
        'invokables' => array(
            'CsnUser\Controller\Index' => 'CsnUser\Controller\IndexController',
			'CsnUser\Controller\Registration' => 'CsnUser\Controller\RegistrationController',
        ),
    ),	
    'router' => array(
        'routes' => array(
			'login' => array(
				'type'    => 'Literal',
				'options' => array(
					'route'    => '/login',
					'defaults' => array(
						'__NAMESPACE__' => 'CsnUser\Controller',
						'controller'    => 'Index',
						'action'        => 'login',
					),
				),
			),
			'user' => array(
				'type'    => 'Literal',
				'options' => array(
					'route'    => '/user',
					'defaults' => array(
						'__NAMESPACE__' => 'CsnUser\Controller',
						'controller'    => 'Index',
						'action'        => 'index',
					),
				),
			),
			'logout' => array(
				'type'    => 'Literal',
				'options' => array(
					'route'    => '/logout',
					'defaults' => array(
						'__NAMESPACE__' => 'CsnUser\Controller',
						'controller'    => 'Index',
						'action'        => 'logout',
					),
				),
			),
			'registration' => array(
				'type'    => 'Literal',
				'options' => array(
					'route'    => '/registration',
					'defaults' => array(
						'__NAMESPACE__' => 'CsnUser\Controller',
						'controller'    => 'Registration',
						'action'        => 'index',
					),
				),
			),
			'forgotten-password' => array(
				'type'    => 'Literal',
				'options' => array(
					'route'    => '/forgotten-password',
					'defaults' => array(
						'__NAMESPACE__' => 'CsnUser\Controller',
						'controller'    => 'Registration',
						'action'        => 'forgottenPassword',
					),
				),
			),
			'password-change-success' => array(
				'type'    => 'Literal',
				'options' => array(
					'route'    => '/password-change-success',
					'defaults' => array(
						'__NAMESPACE__' => 'CsnUser\Controller',
						'controller'    => 'Registration',
						'action'        => 'passwordChangeSuccess',
					),
				),
			),
			'registration-success' => array(
				'type'    => 'Literal',
				'options' => array(
					'route'    => '/registration-success',
					'defaults' => array(
						'__NAMESPACE__' => 'CsnUser\Controller',
						'controller'    => 'Registration',
						'action'        => 'registrationSuccess',
					),
				),
			),
			'confirm-email' => array( // TODO: Fix the problem without token in url
				'type'    => 'Literal',
				'options' => array(
					'route'    => '/confirm-email',
						'defaults' => array(
						'__NAMESPACE__' => 'CsnUser\Controller',
						'controller'    => 'Registration',
						'action'        => 'confirmEmail',
					),
				),
				'may_terminate' => true,
				'child_routes' => array(
					'default' => array(
						'type'    => 'Segment',
						'options' => array(
							'route'    => '[/:id]',
							'defaults' => array(
								'__NAMESPACE__' => 'CsnUser\Controller',
								'controller'    => 'Registration',
								'action'        => 'confirmEmail',
							),
						),
					),
				),
			),
			'confirm-email-change-password' => array( // TODO: Fix the problem without token in url
				'type'    => 'Literal',
				'options' => array(
					'route'    => '/confirm-email-change-password',
						'defaults' => array(
						'__NAMESPACE__' => 'CsnUser\Controller',
						'controller'    => 'Registration',
						'action'        => 'confirmEmailChangePassword',
					),
				),
				'may_terminate' => true,
				'child_routes' => array(
					'default' => array(
						'type'    => 'Segment',
						'options' => array(
							'route'    => '[/:id]',
							'defaults' => array(
								'__NAMESPACE__' => 'CsnUser\Controller',
								'controller'    => 'Registration',
								'action'        => 'confirmEmailChangePassword',
							),
						),
					),
				),
			),
		),
	),
    'view_manager' => array(
        'template_path_stack' => array(
            'auth-doctrine' => __DIR__ . '/../view'
        ),
		
		'display_exceptions' => true,
    ),
    'doctrine' => array(
	
		// 1) for Authentication
        'authentication' => array( // this part is for the Auth adapter from DoctrineModule/Authentication
            'orm_default' => array(
                'object_manager' => 'Doctrine\ORM\EntityManager',
				// object_repository can be used instead of the object_manager key
                'identity_class' => 'CsnUser\Entity\User', //'Application\Entity\User',
                'identity_property' => 'username', // 'username', // 'email',
                'credential_property' => 'password', // 'password',
                'credential_callable' => function(Entity\User $user, $passwordGiven) {
					if ($user->getPassword() == md5('aFGQ475SDsdfsaf2342' . $passwordGiven . $user->getPasswordSalt()) &&
						$user->getState() == 1) {
						return true;
					}
					else {
						return false;
					}
                },
            ),
        ),

		// 2) standard configuration for the ORM from https://github.com/doctrine/DoctrineORMModule
		// http://www.jasongrimes.org/2012/01/using-doctrine-2-in-zend-framework-2/
		// ONLY THIS IS REQUIRED IF YOU USE Doctrine in the module
        'driver' => array(
            // defines an annotation driver with two paths, and names it `my_annotation_driver`
//            'my_annotation_driver' => array(
			__NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    // __DIR__ . '/../module/CsnUser/src/CsnUser/Entity' // 'path/to/my/entities',
					__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity',
                ),
            ),

            // default metadata driver, aggregates all other drivers into a single one.
            // Override `orm_default` only if you know what you're doing
            'orm_default' => array(
                'drivers' => array(
                    // register `my_annotation_driver` for any entity under namespace `My\Namespace`
                    // 'My\Namespace' => 'my_annotation_driver'
					// 'CsnUser' => 'my_annotation_driver'
					__NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver',
                )
            )
        )
    ),
);
