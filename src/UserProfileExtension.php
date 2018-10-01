<?php declare(strict_types=1);

namespace Bolt\Extension\UserProfile;

use Bolt\Extension\DatabaseSchemaTrait;
use Bolt\Extension\SimpleExtension;
use Bolt\Extension\UserProfile\Controller\UserProfileController;
use Bolt\Extension\UserProfile\Storage\Schema\Table\UsersTable;
use Bolt\Menu\MenuEntry;
use Silex\Application;

class UserProfileExtension extends SimpleExtension
{
    use DatabaseSchemaTrait;

    public function registerBackendControllers()
    {
        return [
            'extensions/user-profiles' => new UserProfileController(),
        ];
    }

    protected function registerServices(Application $app)
    {
        $this->registerUsersTableSchema($app);

        $app['userprofiles.manager'] = $app->share(function () use ($app) {
            return new UserProfilesManager($app['db']);
        });
    }

    private function registerUsersTableSchema(Application $app)
    {
        $config = $this->getConfig();
        $app['schema.base_tables'] = $app->extend(
            'schema.base_tables',
            function ($baseTables) use ($app, $config) {
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
            ->setPermission('userprofile')
        ;

        return [
            $menu,
        ];
    }

    protected function registerTwigPaths()
    {
        return [
            'templates/bolt' => [
                'position'  => 'prepend',
                'namespace' => 'bolt'
            ]
        ];
    }
}
