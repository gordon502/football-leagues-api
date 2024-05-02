<?php

namespace App\Common\Serialization;

use App\Common\Dto\NotIncludedInBody;
use App\Common\Response\UnprocessableEntityException;
use App\Modules\User\Model\UserInterface;
use ReflectionClass;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AttributeLoader;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class RoleBasedSerializer implements RoleBasedSerializerInterface
{
    private Serializer $serializer;
    private Security $security;
    private string|null $routeId;

    public function __construct(
        Security $security,
        RequestStack $requestStack
    ) {
        $this->serializer = new Serializer(
            normalizers: [
                new ObjectNormalizer(new ClassMetadataFactory(new AttributeLoader())),
                new ArrayDenormalizer()
            ],
            encoders: [new JsonEncoder()]
        );
        $this->security = $security;
        $this->routeId = $requestStack->getCurrentRequest()?->attributes->get('id');
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

    public function denormalize(array $data, string $classString): object
    {
        /** @var UserInterface|null $user */
        $user = $this->security->getUser();

        if (!$user) {
            $roles = [RoleSerializationGroup::GUEST];
        } else {
            $roles = [$user->getRole()];

            // TODO: later here we need to somehow determine who for example created article to determine OWNER role
            if ($this->routeId && $this->routeId === $user->getId()) {
                $roles[] = RoleSerializationGroup::OWNER;
            }
        }

        $object = $this->serializer->denormalize(
            $data,
            $classString,
            'json',
            ['groups' => $roles]
        );

        $bodyWasValid = $this->checkIfRequestBodyHadAnyValidProperties($object);

        if (!$bodyWasValid) {
            throw new UnprocessableEntityException(
                'Request body must contain at least one valid, accessible for your role property.'
            );
        }

        return $object;
    }

    private function checkIfRequestBodyHadAnyValidProperties(object $denormalizedObject): bool
    {
        $reflection = new ReflectionClass($denormalizedObject);

        foreach ($reflection->getProperties() as $property) {
            $value = $property->getValue($denormalizedObject);

            if ($value instanceof NotIncludedInBody) {
                continue;
            }

            return true;
        }

        return false;
    }
}
