<?php declare(strict_types=1);

namespace Expenses\Setup;

use Atk4\Data\Model;
use Atk4\Data\Persistence;
use Atk4\Data\Schema\Migrator;
use Expenses\Data\Category;
use Expenses\Data\Expense;
use Expenses\Data\User;

class DbCreator
{
    protected string $dbString;
    protected string $dbHost;
    protected string $dbName;
    protected string $dbUser;
    protected string $dbPassword;

    protected int $exitCode = 0;

    protected array $output = [];

    protected Persistence $persistence;
    protected $dbal;

    protected bool $verbose = false;

    public function __construct(
        string $dbString,
        string $dbUser,
        string $dbPassword,
        bool   $verbose = false,
    )
    {
        $this->verbose = $verbose;
        $this->dbString = $dbString;
        $this->dbHost = substr(
            DB_STRING,
            strpos(DB_STRING, '=') + 1,
            strpos(DB_STRING, ';') - strpos(DB_STRING, '=') - 1
        );
        $this->dbName = substr(DB_STRING, strrpos(DB_STRING, '=') + 1);

        $this->dbUser = $dbUser;
        $this->dbPassword = $dbPassword;
        $this->getPersistence();
        $this->dbal = $this->persistence->getConnection()->getConnection();
    }

    //TODO why not simply Core::get()->getPersistence()?
    protected function getPersistence(): void
    {
        $this->persistence = new Persistence\Sql(
            $this->dbString,
            $this->dbUser,
            $this->dbPassword
        );
    }

    public function recreateDb(): void
    {
        $this->dropDb();
        $this->createDb();
        $this->getPersistence(); //needed after DB drop
        $this->createExpensesTables();
    }

    public function createExpensesTables(): void
    {
        $this->createTables();
        $this->createForeignKeys();
        $this->createAdditionalIndexes();
    }

    protected function dropDb(): void
    {
        if ($this->verbose) {
            echo 'dropping database ' . $this->dbName . PHP_EOL;
        }
        $query = "DROP DATABASE IF EXISTS `" . $this->dbName . "`;";
        $this->dbal->executeQuery($query);
    }

    protected function createDb(): void
    {
        if ($this->verbose) {
            echo 'creating database ' . $this->dbName . PHP_EOL;
        }
        $query = "CREATE DATABASE IF NOT EXISTS `" . $this->dbName . "` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci;";
        $this->dbal->executeQuery($query);
    }

    protected function createTables(): void
    {
        $this->createTable(new Expense($this->persistence));
        $this->createTable(new Category($this->persistence));
        $this->createTable(new User($this->persistence));
    }

    protected function createTable(Model $model): void
    {
        if ($this->verbose) {
            echo 'creating table ' . $model->table . PHP_EOL;
        }
        (new Migrator(new $model($this->persistence)))->create();
    }

    protected function createForeignKeys(): void
    {
        if ($this->verbose) {
            echo 'creating foreign keys' . PHP_EOL;
        }
        (new Migrator($this->persistence))->createForeignKey((new Expense($this->persistence))->getReference('category_id'));
        (new Migrator($this->persistence))->createForeignKey((new Expense($this->persistence))->getReference('user_id'));
    }

    protected function createAdditionalIndexes(): void
    {
        if ($this->verbose) {
            echo 'creating additional indexes' . PHP_EOL;
        }
        (new Migrator($this->persistence))->createIndex(
            [
                (new Expense($this->persistence))->getField('date'),
            ],
            false
        );
    }
}