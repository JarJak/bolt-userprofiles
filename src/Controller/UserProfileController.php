<?php

declare(strict_types=1);

namespace Bolt\Extension\JarJak\UserProfiles\Controller;

use Bolt\Controller\Backend\BackendBase;
use Silex;

class UserProfileController extends BackendBase
{
    /**
     * @param Silex\ControllerCollection $ctl
     */
    protected function addRoutes(Silex\ControllerCollection $ctl): void
    {
        $ctl->method('GET|POST');

        $ctl->match('/{id}', [$this, 'index'])
            ->value('id', null)
            ->bind('user_profiles_index')
            ->method('GET');

        $ctl->match('/save', [$this, 'save'])
            ->bind('user_profiles_save')
            ->method('POST');
    }

    public function index(?int $id = null)
    {
        $users = $this->app['storage']->getRepository('Bolt\Storage\Entity\Users')->findAll();
        $user = null;
        if ($id) {
            $user = $this->app['storage']->getRepository('Bolt\Storage\Entity\Users')->find($id);
        }
        return $this->render('@bolt/userprofiles/_base.twig', [], [
            'users' => $users,
            'user' => $user,
        ]);
    }

    public function save()
    {
        $data = $this->app['request']->get('profile');

        if ($data['id']) {
            $this->app['userprofiles.manager']->updateProfile($data['avatar'], $data['description'], (int) $data['id']);
            $this->app['session']->getFlashBag()->add('tagsMessage', 'Zaktualizowano profil');
        }
        return $this->app->redirect($this->app['url_generator']->generate('user_profiles_index'));
    }
}
