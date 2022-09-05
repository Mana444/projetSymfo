<?php
namespace App\EntityListener;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserListener
{
    private  UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }
    public function  prePersist(User $user)
    {
        $this->encodePassword($user);
    }

    public function preupDate (User $user)
    {
        $this->encodePassword($user);
    }
/**
 * Encode le Mdp à partir du plainPassword qui vient de l'Entity User
**/
    public function encodePassword(User $user)
    {
        if ($user->getPlainPassword()===null){
            return;
        }
        $user->setPassword(
            $this->hasher->hashPassword(
                $user,
                $user->getPlainPassword())
            );

    }

}