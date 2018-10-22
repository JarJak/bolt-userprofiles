<?php

declare(strict_types=1);

namespace Bolt\Extension\JarJak\UserProfiles;

use Bolt\Extension\DatabaseSchemaTrait;
use Bolt\Extension\JarJak\UserProfiles\Controller\UserProfileController;
use Bolt\Extension\JarJak\UserProfiles\Storage\Schema\Table\UsersTable;
use Bolt\Extension\SimpleExtension;
use Bolt\Menu\MenuEntry;
use Silex\Application;

class UserProfilesExtension extends SimpleExtension
{
    use DatabaseSchemaTrait;

    public function registerBackendControllers()
    {
        return [
            'extensions/user-profiles' => new UserProfileController(),
        ];
    }

    protected function registerServices(Application $app): void
    {
        $this->registerUsersTableSchema($app);

        $app['userprofiles.manager'] = $app->share(function () use ($app) {
            return new UserProfilesManager($app['db']);
        });
    }

    private function registerUsersTableSchema(Application $app): void
    {
        $app['schema.base_tables'] = $app->extend(
            'schema.base_tables',
            function ($baseTables) use ($app) {
                $platform = $app['db']->getDatabasePlatform();
                $prefix = $app['schema.prefix'];
                $baseTables['users'] = $app->share(function () use ($platform, $prefix) {
                    return new UsersTable($platform, $prefix);
                });
                return $baseTables;
            }
        );
    }
    protected function registerMenuEntries()
    {
        $menu = new MenuEntry('user-profiles', 'user-profiles');
        $menu->setLabel('Profile autorów')
            ->setIcon('fa:rocket')
            ->setPermission('userprofile');

        return [
            $menu,
        ];
    }

    protected function registerTwigPaths()
    {
        return [
            'templates/bolt' => [
                'position' => 'prepend',
                'namespace' => 'bolt',
            ],
        ];
    }
}
