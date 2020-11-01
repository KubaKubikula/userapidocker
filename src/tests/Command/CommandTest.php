<?php
namespace App\Tests\Command;

use App\Command\SendSlackNotificationCommand;
use PHPUnit\Framework\TestCase;
use Doctrine\ORM\EntityManagerInterface;

class SendSlackNotificationCommandTest extends TestCase
{
    public function dataProvider(): array
    {
        return [
            ['2020-01-01', true],
            ['2020-01-012', false],
            ['', false],
            ['2020-23-23 23:34:21', false],
        ];
    }

    /**
     * @dataProvider dataProvider
    */
    public function testInputDate($date, $expected)
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $sendSlackNotificationCommand = new SendSlackNotificationCommand($entityManager);
        $result = $sendSlackNotificationCommand->dateMatch($date);

        $this->assertSame($result, $expected);
    }
}