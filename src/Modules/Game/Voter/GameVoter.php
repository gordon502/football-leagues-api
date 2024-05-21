<?php

namespace App\Modules\Game\Voter;

use App\Modules\Game\Model\GameInterface;
use App\Modules\User\Model\UserInterface;
use App\Modules\User\Role\UserRole;
use ReflectionClass;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class GameVoter extends Voter
{
    public const CREATE = 'create_game';
    public const UPDATE = 'update_game';
    public const DELETE = 'delete_game';

    protected function supports(string $attribute, mixed $subject): bool
    {
        if ($attribute === self::CREATE) {
            return true;
        }

        if ($attribute !== self::DELETE && $attribute !== self::UPDATE) {
            return false;
        }

        $reflection = new ReflectionClass($subject);

        if (!$reflection->implementsInterface(GameInterface::class)) {
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

    private function canCreate(GameInterface|null $subject, UserInterface $user): bool
    {
        return in_array($user->getRole(), [UserRole::ADMIN, UserRole::MODERATOR, UserRole::EDITOR]);
    }

    private function canUpdate(GameInterface $subject, UserInterface $user): bool
    {
        return in_array($user->getRole(), [UserRole::ADMIN, UserRole::MODERATOR, UserRole::EDITOR]);
    }

    private function canDelete(GameInterface $subject, UserInterface $user): bool
    {
        return in_array($user->getRole(), [UserRole::ADMIN, UserRole::MODERATOR, UserRole::EDITOR]);
    }
}
