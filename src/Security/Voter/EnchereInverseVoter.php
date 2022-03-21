<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class EnchereInverseVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['EDIT', 'REMOVE'])
            && $subject instanceof \App\Entity\EnchereInverse;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'EDIT':
                // logic to determine if the user can EDIT
                // return true or false
                if ($subject->getUser() === $user) {
                    return true;
                }
                elseif ($user->getRoles()== "ROLE_ADMIN") {
                    return true;
                }
                break;
            case 'REMOVE':
                if ($subject->getUser() === $user) {
                    return true;
                }
                elseif ($user->getRoles()== "ROLE_ADMIN") {
                    return true;
                }
                break;
        }

        return false;
    }
}
