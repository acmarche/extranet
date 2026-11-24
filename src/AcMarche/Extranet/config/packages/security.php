<?php

use AcMarche\Extranet\Entity\User;
use AcMarche\Extranet\Security\Authenticator\ExtranetAuthenticator;
use AcMarche\Extranet\Security\Authenticator\ExtranetLdapAuthenticator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Ldap\Ldap;
use Symfony\Component\Ldap\LdapInterface;

return static function (ContainerConfigurator $containerConfigurator): void {
    $authenticators = [ExtranetAuthenticator::class];

    $mainFirewall = [
        'lazy' => true,
        'switch_user' => true,
        'form_login' => [
            'login_path' => 'app_login',
            'remember_me' => true,
            'enable_csrf' => true,
        ],
        'logout' => [
            'path' => 'app_logout',
        ],
    ];

    if (interface_exists(LdapInterface::class)) {
        $authenticators[] = ExtranetLdapAuthenticator::class;
        $mainFirewall['form_login_ldap'] = [
            'service' => Ldap::class,
            'check_path' => 'app_login',
        ];
    }

    $mainFirewall += [
        'custom_authenticators' => $authenticators,
        'provider' => 'extranet_user_provider',
        'entry_point' => ExtranetAuthenticator::class,
        'login_throttling' => [
            'max_attempts' => 6,
            'interval' => '15 minutes',
        ],
        'remember_me' => [
            'secret' => '%kernel.secret%',
            'lifetime' => 604800,
            'path' => '/',
            'always_remember_me' => true,
        ],
    ];

    $containerConfigurator->extension('security', [
        'providers' => [
            'extranet_user_provider' => [
                'entity' => [
                    'class' => User::class,
                    'manager_name' => 'default',
                    'property' => 'username',
                ],
            ],
        ],
        'firewalls' => [
            'dev' => [
                'pattern' => '^/(_(profiler|wdt)|css|images|js)/',
                'security' => false,
            ],
            'main' => $mainFirewall,
        ],
    ]);
};
