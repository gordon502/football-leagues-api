<?php

namespace App\Common\Serialization;

use App\Modules\User\Model\UserInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class RoleBasedSerializer
{
    private Serializer $serializer;
    private Security $security;

    public function __construct(
        Security $security
    ) {
        $this->serializer = new Serializer(
            normalizers: [new ObjectNormalizer()],
            encoders: [new JsonEncoder()]
        );
        $this->security = $security;
    }

    public function normalize($object): array
    {
        /** @var UserInterface|null $user */
        $user = $this->security->getUser();

        // TODO: workaround until we get rid of InMemory users
        if ($user === null || !method_exists($user, 'getRole')) {
            return $this->serializer->normalize(
                $object,
                null,
                ['groups' => [RoleSerializationGroup::GUEST]]
            );
        }

        return $this->serializer->normalize(
            $object,
            null,
            ['groups' => [$user->getRole()]]
        );
    }
}
