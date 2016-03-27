<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use AppBundle\Entity\User;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\Common\Persistence\ObjectManager;

class AdminCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('admin:update')
            ->setDescription('Creating and updating Admin user')
            ->addArgument('username', InputArgument::REQUIRED, 'Username')
            ->addArgument('email', InputArgument::REQUIRED, 'Email')
            ->addArgument('password', InputArgument::REQUIRED, 'Password');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $userData = [
            'username' => $input->getArgument('username'),
            'password' => $input->getArgument('password'),
            'email' => $input->getArgument('email'),
        ];

        $userAdmin = $this->findAdmin();
        $this->updateAdmin($userData, $userAdmin);

        $output->writeln('Done!');
    }

    private function findAdmin()
    {
        $em = $this->getContainer()->get('doctrine')->getEntityManager('default');

        return $em->getRepository('AppBundle:User')
            ->findOneBy(['role' => 'ROLE_ADMIN']);
    }

    private function updateAdmin(array $userData, $userAdmin)
    {
        $isAdminCreated = true;

        $em = $this->getContainer()->get('doctrine')->getEntityManager('default');
        $factory = $this->getContainer()->get('security.encoder_factory');

        if (!$userAdmin) {
            $isAdminCreated = false;
            $userAdmin = new User();
        }

        $userAdmin->setUsername($userData['username']);
        $userAdmin->setEmail($userData['email']);
        $userAdmin->setRole('ROLE_ADMIN');

        $encoder = $factory->getEncoder($userAdmin);
        $password = $encoder->encodePassword($userData['password'], $userAdmin->getSalt());
        $userAdmin->setPassword($password);

        if ($isAdminCreated) {
            $em->merge($userAdmin);
        } else {
            $em->persist($userAdmin);
        }

        return $em->flush();
    }
}