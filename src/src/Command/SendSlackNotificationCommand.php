<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use App\Entity\Users;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\AdminRecipient;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Notifier\Recipient\Recipient;

class SendSlackNotificationCommand extends Command
{
    protected static $defaultName = 'app:slack-notifiocation';
    private $container;
    private $notifier;

    public function __construct(ContainerInterface $container, NotifierInterface $notifier)
    {
        $this->container = $container;
        $this->notifier = $notifier;
        parent::__construct();
    }

    protected function configure()
    {
        $this->addArgument('date', InputArgument::REQUIRED, 'Notification date format YYYY-MM-DD');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $notificationDate = $input->getArgument('date');
        var_dump($notificationDate);
        die;

        if ($this->dateMatch($notificationDate)) {
            
            $users = $this->getUsersForDate($notificationDate);
            if ($this->slackApi($users)) {
                return 1;
            }
        }

        return 0;
    }

    public function dateMatch(string $notificationDate): bool
    {
        return (bool) preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}\z/', $notificationDate);
    }

    private function slackApi($users): bool
    {
        foreach ($users as $user) {
            dump($user);
            die;
        }

        return true;
    }

    private function getUsersForDate(string $date)
    { 
        $usersRepository = $this->container->get('doctrine')->getRepository(Users::class);

        return $usersRepository->findByDateField($date);
    }
}