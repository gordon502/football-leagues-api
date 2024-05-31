<?php

namespace App\Modules\User\Voter;

use App\Modules\User\Model\UserInterface;
use App\Modules\User\Role\UserRole;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserVoter extends Voter
{
    public const DELETE = 'delete_user';

    protected function supports(string $attribute, mixed $subject): bool
    {
        if ($attribute !== self::DELETE) {
            return false;
        }

        if (!$subject instanceof UserInterface) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        return $this->canDelete($subject, $user);
    }

    private function canDelete(UserInterface $subject, UserInterface $user): bool
    {
        return
            $subject->getId() === $user->getId()
            || in_array($user->getRole(), [UserRole::ADMIN, UserRole::MODERATOR]);
    }
}
