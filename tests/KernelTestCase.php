<?php

declare(strict_types=1);

namespace App\Tests;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Clock\MockClock;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * @internal
 * @psalm-internal App\Tests
 */
abstract class KernelTestCase extends \Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
{
    use AssertionsTrait;

    private static bool $schemaUpdated = false;

    protected static function bootKernelAndInitDb(array $options = []): void
    {
        self::bootKernel($options);
        /** @noinspection PhpUnhandledExceptionInspection */
        self::initDb();
    }

    /**
     * @throws \Throwable
     */
    protected static function initDb(): void
    {
        // Supports only single default connection!
        $conn = self::getConnection();

        // Create database
        self::executeCliCommand(['doctrine:database:create', '--if-not-exists']);

        // Clear previous data
        $conn->transactional(static function (Connection $conn): void {
            $sm = $conn->createSchemaManager();

            foreach ($sm->listTableNames() as $tableName) {
                // Skip migrations and views metadata tables
                if (\in_array($tableName, ['doctrine_migration_versions', 'doctrine_views_sync_metadata'], true)) {
                    continue;
                }

                $conn->executeStatement("TRUNCATE TABLE {$tableName} CASCADE");
            }
        });

        if (false === self::$schemaUpdated) {
            self::executeCliCommand(['doctrine:views:drop']);
            self::executeCliCommand(['doctrine:migrations:migrate', '-n']);
            self::executeCliCommand(['doctrine:views:sync']);

            self::$schemaUpdated = true;
        }
    }

    protected static function getDoctrine(): ManagerRegistry
    {
        $doctrine = self::getContainer()->get('doctrine');
        \assert($doctrine instanceof ManagerRegistry);

        return $doctrine;
    }

    protected static function getConnection(): Connection
    {
        $conn = self::getDoctrine()->getConnection();
        \assert($conn instanceof Connection);

        return $conn;
    }

    protected static function getEntityManager(): EntityManagerInterface
    {
        $em = self::getDoctrine()->getManager();
        \assert($em instanceof EntityManagerInterface);

        return $em;
    }

    protected static function getClock(): MockClock
    {
        $clock = self::getContainer()->get('clock');
        \assert($clock instanceof MockClock);

        return $clock;
    }

    /**
     * @param list<string> $argv
     */
    protected static function executeCliCommand(array $argv): void
    {
        $kernel = self::getContainer()->get('kernel');
        \assert($kernel instanceof KernelInterface);

        $app = new Application($kernel);
        $app->setAutoExit(false);
        $app->setCatchExceptions(false);
        $app->setCatchErrors(false);
        $app->run(new ArgvInput(['', ...$argv]), new NullOutput());
    }
}
