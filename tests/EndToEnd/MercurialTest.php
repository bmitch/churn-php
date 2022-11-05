<?php

declare(strict_types=1);

namespace Churn\Tests\EndToEnd;

use Churn\Command\RunCommand;
use Churn\Tests\BaseTestCase;
use InvalidArgumentException;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class MercurialTest extends BaseTestCase
{
    /** @var CommandTester */
    private $commandTester;

    protected function setUp()
    {
        $application = new Application('churn-php', 'test');
        $application->add(RunCommand::newInstance());
        $command = $application->find('run');
        $this->commandTester = new CommandTester($command);
    }

    protected function tearDown()
    {
        $this->commandTester = null;
    }

    /** @test */
    public function it_works_with_mercurial()
    {
        $exitCode = $this->commandTester->execute([
            'paths' => [],
            '--configuration' => '/tmp/test',
        ]);
        $display = $this->commandTester->getDisplay();

        $expected = "
    ___  _   _  __  __  ____  _  _     ____  _   _  ____
   / __)( )_( )(  )(  )(  _ \( \( )___(  _ \( )_( )(  _ \
  ( (__  ) _ (  )(__)(  )   / )  ((___))___/ ) _ (  )___/
   \___)(_) (_)(______)(_)\_)(_)\_)   (__)  (_) (_)(__)

+---------+---------------+------------+-------+
| File    | Times Changed | Complexity | Score |
+---------+---------------+------------+-------+
| Foo.php | 2             | 1          | 1     |
| Bar.php | 1             | 1          | 0.5   |
+---------+---------------+------------+-------+
";

        $this->assertSame(0, $exitCode);
        $this->assertSame($expected, $display);
    }
}
