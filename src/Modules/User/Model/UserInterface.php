<?php

namespace App\Modules\User\Model;

use App\Common\Model\SoftDeletableModelInterface;
use App\Common\Model\TimestampableModelInterface;
use Symfony\Component\Security\Core\User\UserInterface as SecurityUserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

interface UserInterface extends
    UserGetInterface,
    UserGetPasswordInterface,
    UserSetInterface,
    TimestampableModelInterface,
    SoftDeletableModelInterface,
    SecurityUserInterface,
    PasswordAuthenticatedUserInterface
{
}
