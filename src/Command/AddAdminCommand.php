<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AddAdminCommand extends Command
{
    protected static $defaultName = 'app:add-admin';
    protected $em;
    protected $passwordEncoder;

    public function __construct(EntityManagerInterface $entityManagerInterface, UserPasswordEncoderInterface $passwordEncoderInterface)
    {
        $this->passwordEncoder = $passwordEncoderInterface;
        $this->em = $entityManagerInterface;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Cette commande vous permet d\'ajouter un administrateur')
            ->addArgument('username', InputArgument::REQUIRED, 'Nom d\'utilisateur ou Identifiant')
            ->addArgument('password', InputArgument::REQUIRED, 'Mot de passe')
            /* ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description') */
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $username = $input->getArgument('username');
        $password = $input->getArgument('password');

        if (strlen($username) >= 4 && strlen($password) >= 4) {
            $io->note(sprintf('Nom d\'utilisateur: %s', $username));
            $io->note(sprintf('Mot de passe: %s', $password));

            if ($this->addAdmin($username, $password)) {
                $io->success('L\'administrateur a été creéé avec succès !');
            } else {
                $io->error('Une erreur est survenu lors la création de l\'admin! Veillez réesayer ...');
            }
        } else {
            $io->warning('Les données saisi sont incorrectes !');
        }
    }

    protected function addAdmin(string $username, string $password)
    {
        $user = new User();
        $user->setUsername($username);
        $encryptedPassword = $this->passwordEncoder->encodePassword($user, $password);
        $user->setPassword($encryptedPassword);
        
        if (!empty($user->getUsername()) && !empty($user->getPassword())) {
            $this->em->persist($user);
            $this->em->flush();

            return true;
        }

        return false;
    }
}
