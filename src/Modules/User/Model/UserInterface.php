<?php

namespace App\Modules\User\Model;

use App\Common\Model\SoftDeletableModelInterface;
use App\Common\Model\TimestampableModelInterface;

interface UserInterface extends UserGetInterface, UserGetPasswordInterface, UserSetInterface, TimestampableModelInterface, SoftDeletableModelInterface
{
}
