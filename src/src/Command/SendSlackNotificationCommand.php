<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use App\Entity\User;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\AdminRecipient;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Notifier\Recipient\Recipient;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpClient\HttplugClient;

class SendSlackNotificationCommand extends Command
{
    protected static $defaultName = 'app:slack-notifiocation';
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        parent::__construct();
    }

    protected function configure()
    {
        $this->addArgument('date', InputArgument::REQUIRED, 'Notification date format YYYY-MM-DD');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $notificationDate = $input->getArgument('date');

        if ($this->dateMatch($notificationDate)) {
            $users = $this->getUsersForDate($notificationDate);
            if ($users !== []) {
                $this->slackApi($users);
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
        $requestText = 'Happy birthday ';
        $userNames = [];
        foreach ($users as $user) {
            $userNames[] = '@' . $user->getName();
        }

        $requestText .= implode(', ', $userNames);

        $httpClient = new HttplugClient();
        $request = $httpClient->createRequest(
            'POST',
            // tohle si nahraďtě webhookem z vaší aplikace https://api.slack.com/apps?new_app=1
            'https://hooks.slack.com/services/XXXXXXXXXX/XXXXXXX/XXXXXXXXX',
            [
                'Content-type' => 'application/json',
            ],
            '{"text":"' . $requestText . '"}'
        );

        $httpClient->sendAsyncRequest($request);
        
        return true;
    }

    private function getUsersForDate(string $date)
    {
        $usersRepository = $this->container->get('doctrine')->getRepository(User::class);

        return $usersRepository->findByDateField($date);
    }
}
