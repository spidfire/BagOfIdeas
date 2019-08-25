<?php


namespace BagOfIdeas\Helpers;


use BagOfIdeas\Models\User\User;
use BagOfIdeas\Models\User\UserQuery;
use Propel\Runtime\Exception\PropelException;
use RuntimeException;

class Authentication
{

    /**
     * @return User|null
     * @throws PropelException
     */
    public static function getUserOrNull():?User{
        return UserQuery::create()
            ->filterByName('Djurre')
            ->findOneOrCreate();
    }


    public static function getUserOrException():User{
        /** @noinspection PhpUnhandledExceptionInspection */
        $user = static::getUserOrNull();

        if($user === null){
            throw new RuntimeException('Couldn\'t not find logged in user');
        }
        return $user;
    }

}