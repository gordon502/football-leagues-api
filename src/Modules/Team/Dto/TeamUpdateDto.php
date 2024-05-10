<?php

namespace App\Modules\Team\Dto;

use App\Common\Dto\DtoPropertyRelatedToEntity;
use App\Common\Dto\NotIncludedInBody;
use App\Common\Dto\NotIncludedInBodyTrait;
use App\Common\OAAttributes\OARoleBasedProperty;
use App\Common\Serialization\RoleSerializationGroup;
use App\Modules\OrganizationalUnit\Model\OrganizationalUnitInterface;
use App\Modules\Team\Model\TeamUpdatableInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

readonly class TeamUpdateDto implements TeamUpdatableInterface
{
    use NotIncludedInBodyTrait;

    public function __construct(
        private string|null|NotIncludedInBody $name = new NotIncludedInBody(),
        private int|null|NotIncludedInBody $yearEstablished = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $colors = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $country = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $address = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $city = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $postalCode = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $site = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $stadium = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $organizationalUnitId = new NotIncludedInBody()
    ) {
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
        return $this->toValueOrNull($this->name);
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Year established.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\Type(['int', 'null'])]
    #[Assert\PositiveOrZero]
    public function getYearEstablished(): int|null
    {
        return $this->toValueOrNull($this->yearEstablished);
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Colors.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\Type(['string', 'null'])]
    #[Assert\Length(max: 255)]
    public function getColors(): string|null
    {
        return $this->toValueOrNull($this->colors);
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Country.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\Type(['string', 'null'])]
    #[Assert\Length(max: 255)]
    public function getCountry(): string|null
    {
        return $this->toValueOrNull($this->country);
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Address.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\Type(['string', 'null'])]
    #[Assert\Length(max: 255)]
    public function getAddress(): string|null
    {
        return $this->toValueOrNull($this->address);
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('City.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\Type(['string', 'null'])]
    #[Assert\Length(max: 255)]
    public function getCity(): string|null
    {
        return $this->toValueOrNull($this->city);
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Postal code.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\Type(['string', 'null'])]
    #[Assert\Length(max: 255)]
    public function getPostalCode(): string|null
    {
        return $this->toValueOrNull($this->postalCode);
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Site.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\Type(['string', 'null'])]
    #[Assert\Length(max: 255)]
    public function getSite(): string|null
    {
        return $this->toValueOrNull($this->site);
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
    #[Assert\Type(['string', 'null'])]
    #[Assert\Length(max: 255)]
    public function getStadium(): string|null
    {
        return $this->toValueOrNull($this->stadium);
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Organizational unit identifier.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[DtoPropertyRelatedToEntity(OrganizationalUnitInterface::class)]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Uuid]
    public function getOrganizationalUnitId(): string|null
    {
        return $this->toValueOrNull($this->organizationalUnitId);
    }
}
