<?php

namespace App\Modules\Season\Voter;

use App\Modules\Season\Model\SeasonInterface;
use App\Modules\User\Model\UserInterface;
use App\Modules\User\Role\UserRole;
use ReflectionClass;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class SeasonVoter extends Voter
{
    public const CREATE = 'create_season';
    public const UPDATE = 'update_season';
    public const DELETE = 'delete_season';

    protected function supports(string $attribute, mixed $subject): bool
    {
        if ($attribute === self::CREATE) {
            return true;
        }

        if ($attribute !== self::DELETE && $attribute !== self::UPDATE) {
            return false;
        }

        $reflection = new ReflectionClass($subject);

        if (!$reflection->implementsInterface(SeasonInterface::class)) {
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

    private function canCreate(SeasonInterface|null $subject, UserInterface $user): bool
    {
        return in_array($user->getRole(), [UserRole::ADMIN, UserRole::MODERATOR, UserRole::EDITOR]);
    }

    private function canUpdate(SeasonInterface $subject, UserInterface $user): bool
    {
        return in_array($user->getRole(), [UserRole::ADMIN, UserRole::MODERATOR, UserRole::EDITOR]);
    }

    private function canDelete(SeasonInterface $subject, UserInterface $user): bool
    {
        return in_array($user->getRole(), [UserRole::ADMIN, UserRole::MODERATOR, UserRole::EDITOR]);
    }
}
