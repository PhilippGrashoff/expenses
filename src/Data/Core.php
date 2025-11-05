<?php declare(strict_types=1);

namespace Expenses\Data;

use Atk4\Core\HookTrait;
use Atk4\Data\Exception;
use Atk4\Data\Field\PasswordField;
use Atk4\Data\Model;
use Atk4\Data\Persistence;
use Atk4\Data\Persistence\Sql;
use Atk4\Data\Persistence\Sql\Mysql\Connection;
use InvalidCredentialsException;
use NoLoggedInUserException;


/**
 * Singleton Pattern implementation taken from
 * https://github.com/DesignPatternsPHP/DesignPatternsPHP/blob/main/Creational/Singleton/Singleton.php
 */
class Core
{
    use HookTrait;

    /** ---------------------------------------------- Singleton Code  ---------------------------------------------  */

    protected static ?Core $instance = null;

    /**
     * @return Core
     */
    public static function get(): Core
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @codeCoverageIgnore;
     */
    protected function __construct()
    {
    }

    /**
     * @codeCoverageIgnore;
     */
    protected function __clone()
    {
    }

    /**
     * @codeCoverageIgnore;
     */
    public function __wakeup()
    {
        throw new Exception("Cannot unserialize singleton");
    }




    /** ---------------------------------------------- Auth Code ---------------------------------------------------  */

    /** @var class-string|Model The user model class to check against */
    public static string $userModel = User::class;

    /** @var Model|null an instance of the logged-in user */
    protected ?Model $userEntity = null;

    /** @var int how often a user may enter the password wrong before being blocked */
    protected int $maxFailedLogins = 10;

    /** @var string The key for $_SESSION array to store the logged-in user in */
    protected static string $sessionKeyForUser = '__atk_user';


    /**
     * @param Model $userModel
     * @param string $username
     * @param string $password
     * @param string $fieldUsername
     * @param string $fieldPassword
     * @return void
     * @throws Exception
     * @throws InvalidCredentialsException
     * @throws Exception|\Atk4\Core\Exception
     */
    public function login(
        Model  $userModel,
        string $username,
        string $password,
        string $fieldUsername = 'username',
        string $fieldPassword = 'password'
    ): void
    {
        //login should only be possible if no logged-in user is set!
        if (!empty($_SESSION[self::$sessionKeyForUser])) {
            throw new Exception('A User is already logged in, logout prior to login!');
        }
        $userModel->assertIsModel();
        if (!$userModel instanceof self::$userModel) {
            throw new Exception('Instance of wrong class passed. ' . self::$userModel . ' expected.');
        }

        //use tryLoadBy and throw generic exception to avoid username guessing
        $userEntity = $userModel->tryLoadBy($fieldUsername, $username);
        if ($userEntity === null) {
            throw new InvalidCredentialsException();
        }

        //can e.g. be used to check max. failed logins before another attempt
        $this->beforeLogin($userEntity);

        // verify if the password matches
        $passwordField = PasswordField::assertInstanceOf($userEntity->getField($fieldPassword));
        if ($passwordField->verifyPassword($userEntity, $password)) {
            $this->onSuccessfulLogin($userEntity);
            $_SESSION[self::$sessionKeyForUser] = $userEntity->get();
            $this->userEntity = clone $userEntity;
        } else {
            $this->onFailedLogin($userEntity);
            throw new InvalidCredentialsException();
        }
    }

    /**
     * TODO: Which actions/function calls are really sensible here?
     *
     * @return void
     */
    public function logout(): void
    {
        $_SESSION[self::$sessionKeyForUser] = null;
        $this->userEntity = null;
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
        session_start();
        session_regenerate_id();
    }

    /**
     * retrieve the logged-in User
     *
     * @return Model
     * @throws Exception|NoLoggedInUserException
     */
    public function getUser(): Model
    {
        if ($this->userEntity) {
            return $this->userEntity;
        }
        $userEntity = (new self::$userModel($this->getPersistence()))->createEntity();
        //load user from session cache
        if (
            !isset($_SESSION[self::$sessionKeyForUser])
            || !isset($_SESSION[self::$sessionKeyForUser][$userEntity->idField])
        ) {
            throw new NoLoggedInUserException();
        }
        $userEntity->setMulti($_SESSION[self::$sessionKeyForUser]);
        $this->userEntity = $userEntity;
        return $this->userEntity;
    }

    /**
     * check if a user's role is within given roles
     *
     * @param array $roles
     * @return bool
     * @throws Exception
     * @throws NoLoggedInUserException
     */
    public function userHasRole(array $roles): bool
    {
        return in_array($this->getUser()->get('role'), $roles);
    }

    /**
     * This method should not be used unless for special occasions where a user needs to be set, e.g.
     * - a script run by a cronjob
     * - an API script where the API key points to a user
     *
     * @param Model $userEntity
     * @param bool $allowOverwrite
     * @return void
     * @throws Exception
     * @throws Exception
     */
    public function dangerouslySetLoggedInUser(Model $userEntity, bool $allowOverwrite = false): void
    {
        $userEntity->assertIsLoaded();
        if (
            isset($_SESSION[self::$sessionKeyForUser][$userEntity->idField])
            && !$allowOverwrite
        ) {
            throw new Exception('Cannot overwrite logged in user.');
        }
        if (!$userEntity instanceof self::$userModel) {
            throw new Exception('Instance of wrong class passed. ' . self::$userModel . ' expected.');
        }
        $_SESSION[self::$sessionKeyForUser] = $userEntity->get();
        $this->userEntity = $userEntity;
    }

    protected function beforeLogin(User $userEntity): void
    {
        if ($userEntity->get('failed_logins') >= $this->maxFailedLogins) {
            throw new Exception('Too many failed login attempts');
        }
    }

    protected function onSuccessfulLogin(User $userEntity): void
    {
        $userEntity->set('failed_logins', 0);
        $userEntity->set('last_login', new \DateTime());
        $userEntity->save();
    }

    protected function onFailedLogin(User $userEntity): void
    {
        $userEntity->set('failed_logins', $userEntity->get('failed_logins') + 1);
        $userEntity->save();
    }


    /** ---------------------------------- Persistence getter ------------------------------------------------------  */

    protected ?Persistence $persistence = null;
    public ?Connection $dbConnection = null;

    public function getPersistence(): Persistence
    {
        if ($this->persistence !== null) {
            return $this->persistence;
        }

        $this->dbConnection = Connection::connect(DB_STRING, DB_USER, DB_PASSWORD);
        $this->persistence = new Sql($this->dbConnection);

        return $this->persistence;
    }
}