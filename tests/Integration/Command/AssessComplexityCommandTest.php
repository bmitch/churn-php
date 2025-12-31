<?php

declare(strict_types=1);

namespace Churn\Tests\Integration\Command;

use Churn\Command\AssessComplexityCommand;
use Churn\Tests\BaseTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

final class AssessComplexityCommandTest extends BaseTestCase
{
    /**
     * @var CommandTester
     */
    private $commandTester;

    /** @return void */
    #[\Override]
    protected function setUp()
    {
        parent::setUp();

        $application = new Application('churn-php', 'test');
        $method = \method_exists($application, 'addCommand')
            ? 'addCommand'
            : 'add';
        $application->{$method}(AssessComplexityCommand::newInstance());
        $command = $application->find('assess-complexity');
        $this->commandTester = new CommandTester($command);
    }

    /** @return void */
    #[\Override]
    protected function tearDown()
    {
        parent::tearDown();

        unset($this->commandTester);
    }

    /** @test */
    public function it_returns_the_cyclomatic_complexity_greater_than_zero(): void
    {
        $exitCode = $this->commandTester->execute(['file' => __FILE__]);
        $result = \rtrim($this->commandTester->getDisplay());

        self::assertSame(0, $exitCode);
        self::assertTrue(\ctype_digit($result), 'The result of the command must be an integer');
        self::assertGreaterThan(0, (int) $result);
    }

    /** @test */
    public function it_returns_zero_for_non_existing_file(): void
    {
        $exitCode = $this->commandTester->execute(['file' => 'nonexisting-file.php']);
        $result = \rtrim($this->commandTester->getDisplay());

        self::assertSame(0, $exitCode);
        self::assertTrue(\ctype_digit($result), 'The result of the command must be an integer');
        self::assertSame(0, (int) $result);
    }
}
