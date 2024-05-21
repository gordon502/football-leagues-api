<?php

namespace App\Modules\Game\Dto;

use App\Common\OAAttributes\OARoleBasedProperty;
use App\Common\Serialization\RoleSerializationGroup;
use App\Modules\Game\Enum\GameResultEnum;
use App\Modules\Game\Model\GameCreatableInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class GameCreateDto implements GameCreatableInterface
{
    private string|null $date;
    private string|null $stadium;
    private int|null $team1ScoreHalf;
    private int|null $team2ScoreHalf;
    private int|null $team1Score;
    private int|null $team2Score;
    private GameResultEnum|null $result;
    private string|null $viewers;
    private string|null $annotation;
    private string|null $roundId;
    private string|null $seasonTeam1Id;
    private string|null $seasonTeam2Id;

    public function __construct(
        string|null $date,
        string|null $stadium,
        int|null $team1ScoreHalf,
        int|null $team2ScoreHalf,
        int|null $team1Score,
        int|null $team2Score,
        GameResultEnum|null $result,
        string|null $viewers,
        string|null $annotation,
        string|null $roundId,
        string|null $seasonTeam1Id,
        string|null $seasonTeam2Id
    ) {
        $this->date = $date;
        $this->stadium = $stadium;
        $this->team1ScoreHalf = $team1ScoreHalf;
        $this->team2ScoreHalf = $team2ScoreHalf;
        $this->team1Score = $team1Score;
        $this->team2Score = $team2Score;
        $this->result = $result;
        $this->viewers = $viewers;
        $this->annotation = $annotation;
        $this->roundId = $roundId;
        $this->seasonTeam1Id = $seasonTeam1Id;
        $this->seasonTeam2Id = $seasonTeam2Id;
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Game date.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\NotBlank]
    #[Assert\DateTime]
    public function getDate(): string|null
    {
        return $this->date;
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Stadium.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\Length(max: 255)]
    public function getStadium(): string|null
    {
        return $this->stadium;
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Team 1 score half.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\Type('integer')]
    #[Assert\PositiveOrZero]
    public function getTeam1ScoreHalf(): int|null
    {
        return $this->team1ScoreHalf;
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Team 2 score half.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\Type('integer')]
    #[Assert\PositiveOrZero]
    public function getTeam2ScoreHalf(): int|null
    {
        return $this->team2ScoreHalf;
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Team 1 score.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\Type('integer')]
    #[Assert\PositiveOrZero]
    public function getTeam1Score(): int|null
    {
        return $this->team1Score;
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Team 2 score.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\Type(['int', 'null'])]
    #[Assert\PositiveOrZero]
    public function getTeam2Score(): int|null
    {
        return $this->team2Score;
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Game result.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\Choice(choices: [
        GameResultEnum::TEAM_1,
        GameResultEnum::TEAM_2,
        GameResultEnum::DRAW,
        GameResultEnum::NOT_PLAYED,
        GameResultEnum::CANCELLED,
        GameResultEnum::POSTPONED,
        GameResultEnum::TEAM_1_WALKOVER,
        GameResultEnum::TEAM_2_WALKOVER
    ])]
    public function getResult(): GameResultEnum|null
    {
        return $this->result;
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Viewers.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\Length(max: 255)]
    public function getViewers(): string|null
    {
        return $this->viewers;
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Annotation.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\Length(max: 255)]
    public function getAnnotation(): string|null
    {
        return $this->annotation;
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Round ID.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\NotBlank]
    #[Assert\Uuid]
    public function getRoundId(): string|null
    {
        return $this->roundId;
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Season team 1 ID.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\Uuid]
    public function getSeasonTeam1Id(): string|null
    {
        return $this->seasonTeam1Id;
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Season team 2 ID.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\Uuid]
    public function getSeasonTeam2Id(): string|null
    {
        return $this->seasonTeam2Id;
    }
}
