<?php declare(strict_types=1);

namespace Bolt\Extension\UserProfile\Controller;

use Bolt\Controller\Backend\BackendBase;
use Silex;
use Symfony\Component\HttpFoundation\Request;

class UserProfileController extends BackendBase
{
    /**
     * @param Silex\ControllerCollection $ctl
     */
    protected function addRoutes(Silex\ControllerCollection $ctl)
    {
        $ctl->method('GET|POST');

        $ctl->match('/{id}', array($this, 'index'))
            ->value('id', null)
            ->bind('user_profiles_index')
            ->method('GET');

        $ctl->match('/save', array($this, 'save'))
            ->bind('user_profiles_save')
            ->method('POST');
    }

    public function index(Request $request, int $id = null)
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
            $this->app['userprofiles.manager']->updateProfile($data['avatar'], $data['description'], (int)$data['id']);
            $this->app['session']->getFlashBag()->add('tagsMessage', 'Zaktualizowano profil');
        }
        return $this->app->redirect($this->app["url_generator"]->generate("user_profiles_index"));
    }
}
