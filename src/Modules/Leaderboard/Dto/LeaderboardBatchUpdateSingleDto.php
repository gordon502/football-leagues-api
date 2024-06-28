<?php

namespace App\Modules\Leaderboard\Dto;

use App\Common\Dto\NotIncludedInBody;
use App\Common\Dto\NotIncludedInBodyTrait;
use App\Common\OAAttributes\OARoleBasedProperty;
use App\Common\Serialization\RoleSerializationGroup;
use App\Modules\Leaderboard\Model\LeaderboardUpdatableInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

readonly class LeaderboardBatchUpdateSingleDto extends LeaderboardUpdateDto implements LeaderboardUpdatableInterface
{
    use NotIncludedInBodyTrait;

    public function __construct(
        protected string|null $id = null,
        int|null|NotIncludedInBody $place = new NotIncludedInBody(),
        int|null|NotIncludedInBody $matchesPlayed = new NotIncludedInBody(),
        int|null|NotIncludedInBody $points = new NotIncludedInBody(),
        int|null|NotIncludedInBody $wins = new NotIncludedInBody(),
        int|null|NotIncludedInBody $draws = new NotIncludedInBody(),
        int|null|NotIncludedInBody $losses = new NotIncludedInBody(),
        int|null|NotIncludedInBody $goalsScored = new NotIncludedInBody(),
        int|null|NotIncludedInBody $goalsConceded = new NotIncludedInBody(),
        int|null|NotIncludedInBody $homeGoalsScored = new NotIncludedInBody(),
        int|null|NotIncludedInBody $homeGoalsConceded = new NotIncludedInBody(),
        int|null|NotIncludedInBody $awayGoalsScored = new NotIncludedInBody(),
        int|null|NotIncludedInBody $awayGoalsConceded = new NotIncludedInBody(),
        bool|null|NotIncludedInBody $promotedToHigherDivision = new NotIncludedInBody(),
        bool|null|NotIncludedInBody $eligibleForPromotionBargaining = new NotIncludedInBody(),
        bool|null|NotIncludedInBody $eligibleForRetentionBargaining = new NotIncludedInBody(),
        bool|null|NotIncludedInBody $relegatedToLowerDivision = new NotIncludedInBody(),
        int|null|NotIncludedInBody $directMatchesPlayed = new NotIncludedInBody(),
        int|null|NotIncludedInBody $directMatchesPoints = new NotIncludedInBody(),
        int|null|NotIncludedInBody $directMatchesWins = new NotIncludedInBody(),
        int|null|NotIncludedInBody $directMatchesDraws = new NotIncludedInBody(),
        int|null|NotIncludedInBody $directMatchesLosses = new NotIncludedInBody(),
        int|null|NotIncludedInBody $directMatchesGoalsScored = new NotIncludedInBody(),
        int|null|NotIncludedInBody $directMatchesGoalsConceded = new NotIncludedInBody(),
        string|null|NotIncludedInBody $annotation = new NotIncludedInBody(),
        string|null|NotIncludedInBody $seasonId = new NotIncludedInBody(),
        string|null|NotIncludedInBody $seasonTeamId = new NotIncludedInBody()
    ) {
        parent::__construct(
            $place,
            $matchesPlayed,
            $points,
            $wins,
            $draws,
            $losses,
            $goalsScored,
            $goalsConceded,
            $homeGoalsScored,
            $homeGoalsConceded,
            $awayGoalsScored,
            $awayGoalsConceded,
            $promotedToHigherDivision,
            $eligibleForPromotionBargaining,
            $eligibleForRetentionBargaining,
            $relegatedToLowerDivision,
            $directMatchesPlayed,
            $directMatchesPoints,
            $directMatchesWins,
            $directMatchesDraws,
            $directMatchesLosses,
            $directMatchesGoalsScored,
            $directMatchesGoalsConceded,
            $annotation,
            $seasonId,
            $seasonTeamId,
        );
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('ID.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[Assert\Uuid]
    public function getId(): string|null
    {
        return $this->id;
    }
}
