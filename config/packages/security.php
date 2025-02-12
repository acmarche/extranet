<?php

use AcMarche\Extranet\Entity\User;
use AcMarche\Extranet\Security\Authenticator\ExtranetAuthenticator;
use AcMarche\Extranet\Security\Authenticator\ExtranetLdapAuthenticator;
use Symfony\Component\Ldap\Ldap;
use Symfony\Component\Ldap\LdapInterface;
use Symfony\Config\SecurityConfig;

return static function (SecurityConfig $security): void {
    $security
        ->provider('extranet_user_provider')
        ->entity()
        ->class(User::class)
        ->managerName('default')
        ->property('username');

    $security
        ->firewall('dev')
        ->pattern('^/(_(profiler|wdt)|css|images|js)/')
        ->security(false);

    $mainFirewall = $security
        ->firewall('main')
        ->lazy(true);

    $mainFirewall->switchUser();

    $mainFirewall
        ->formLogin()
        ->loginPath('app_login')
        ->rememberMe(true)
        ->enableCsrf(true);

    $mainFirewall
        ->logout()
        ->path('app_logout');

    $authenticators = [ExtranetAuthenticator::class];

    if (interface_exists(LdapInterface::class)) {
        $authenticators[] = ExtranetLdapAuthenticator::class;
        $mainFirewall->formLoginLdap([
            'service' => Ldap::class,
            'check_path' => 'app_login',
        ]);
    }

    $mainFirewall
        ->customAuthenticators($authenticators)
        ->provider('extranet_user_provider')
        ->entryPoint(ExtranetAuthenticator::class)
        ->loginThrottling()
        ->maxAttempts(6)
        ->interval('15 minutes');

    $mainFirewall
        ->rememberMe([
            'secret' => '%kernel.secret%',
            'lifetime' => 604800,
            'path' => '/',
            'always_remember_me' => true,
        ]);
};
