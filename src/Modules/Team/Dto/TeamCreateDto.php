<?php

namespace App\Modules\Team\Dto;

use App\Common\OAAttributes\OARoleBasedProperty;
use App\Common\Serialization\RoleSerializationGroup;
use App\Modules\Team\Model\TeamCreatableInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class TeamCreateDto implements TeamCreatableInterface
{
    private string|null $name;
    private int|null $yearEstablished;
    private string|null $colors;
    private string|null $country;
    private string|null $address;
    private string|null $city;
    private string|null $postalCode;
    private string|null $site;
    private string|null $stadium;
    private string|null $organizationalUnitId;

    public function __construct(
        string|null $name,
        int|null $yearEstablished,
        string|null $colors,
        string|null $country,
        string|null $address,
        string|null $city,
        string|null $postalCode,
        string|null $site,
        string|null $stadium,
        string|null $organizationalUnitId
    ) {
        $this->name = $name;
        $this->yearEstablished = $yearEstablished;
        $this->colors = $colors;
        $this->country = $country;
        $this->address = $address;
        $this->city = $city;
        $this->postalCode = $postalCode;
        $this->site = $site;
        $this->stadium = $stadium;
        $this->organizationalUnitId = $organizationalUnitId;
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Team name.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    public function getName(): string|null
    {
        return $this->name;
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Team year established.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\Type(['int', 'null'])]
    #[Assert\PositiveOrZero]
    public function getYearEstablished(): int|null
    {
        return $this->yearEstablished;
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Team colors.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\Type(['string', 'null'])]
    #[Assert\Length(max: 255)]
    public function getColors(): string|null
    {
        return $this->colors;
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Team country.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\Type(['string', 'null'])]
    #[Assert\Length(max: 255)]
    public function getCountry(): string|null
    {
        return $this->country;
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Team address.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\Type(['string', 'null'])]
    #[Assert\Length(max: 255)]
    public function getAddress(): string|null
    {
        return $this->address;
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Team city.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\Type(['string', 'null'])]
    #[Assert\Length(max: 255)]
    public function getCity(): string|null
    {
        return $this->city;
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Team postal code.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\Type(['string', 'null'])]
    #[Assert\Length(max: 255)]
    public function getPostalCode(): string|null
    {
        return $this->postalCode;
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Team site.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\Type(['string', 'null'])]
    #[Assert\Length(max: 255)]
    public function getSite(): string|null
    {
        return $this->site;
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Team stadium.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\Type(['string', 'null'])]
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
    #[OARoleBasedProperty('Organizational unit ID.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Uuid]
    public function getOrganizationalUnitId(): string|null
    {
        return $this->organizationalUnitId;
    }
}
