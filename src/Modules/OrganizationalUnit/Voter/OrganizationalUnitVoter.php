<?php

namespace App\Modules\OrganizationalUnit\Voter;

use App\Modules\OrganizationalUnit\Model\OrganizationalUnitInterface;
use App\Modules\User\Model\UserInterface;
use App\Modules\User\Role\UserRole;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class OrganizationalUnitVoter extends Voter
{
    public const CREATE = 'create_organizational_unit';
    public const UPDATE = 'update_organizational_unit';
    public const DELETE = 'delete_organizational_unit';

    protected function supports(string $attribute, mixed $subject): bool
    {
        if ($attribute === self::CREATE) {
            return true;
        }

        if ($attribute !== self::DELETE && $attribute !== self::UPDATE) {
            return false;
        }

        if (!$subject instanceof OrganizationalUnitInterface) {
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

        return match ($attribute) {
            self::CREATE => $this->canCreate($subject, $user),
            self::UPDATE => $this->canUpdate($subject, $user),
            self::DELETE => $this->canDelete($subject, $user),
            default => false,
        };
    }

    private function canCreate(OrganizationalUnitInterface|null $subject, UserInterface $user): bool
    {
        return $user->getRole() === UserRole::ADMIN;
    }

    private function canUpdate(OrganizationalUnitInterface $subject, UserInterface $user): bool
    {
        return $user->getRole() === UserRole::ADMIN;
    }

    private function canDelete(OrganizationalUnitInterface $subject, UserInterface $user): bool
    {
        return $user->getRole() === UserRole::ADMIN;
    }
}
