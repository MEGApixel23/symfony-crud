<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserData implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $data = [
            'username' => 'admin',
            'password' => 'admin',
            'email' => 'admin@gmail.com',
        ];

        $userAdmin = new User();
        $userAdmin->setUsername($data['username']);
        $userAdmin->setEmail($data['email']);
        $userAdmin->setRole('ROLE_ADMIN');

        $factory = $this->container->get('security.encoder_factory');
        $encoder = $factory->getEncoder($userAdmin);
        $password = $encoder->encodePassword($data['password'], $userAdmin->getSalt());
        $userAdmin->setPassword($password);

        $manager->persist($userAdmin);
        $manager->flush();
    }
}