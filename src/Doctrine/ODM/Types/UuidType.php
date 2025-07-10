<?php declare(strict_types=1);

namespace App\Doctrine\ODM\Types;

use Doctrine\ODM\MongoDB\Types\ClosureToPHP;
use Doctrine\ODM\MongoDB\Types\Type;
use Doctrine\ODM\MongoDB\Types\Versionable;
use InvalidArgumentException;
use MongoDB\BSON\Binary;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class UuidType extends Type implements Versionable
{
    use ClosureToPHP;

    public const UUID = 'uuid';

    public function convertToPHPValue(mixed $value): ?UuidInterface
    {
        if (null === $value || [] === $value) {
            return null;
        }

        if ($value instanceof UuidInterface) {
            return $value;
        }

        if ($value instanceof Binary) {
            return Uuid::fromBytes($value->getData());
        }

        if (is_string($value) && Uuid::isValid($value)) {
            return Uuid::fromString($value);
        }

        throw new InvalidArgumentException(
            sprintf(
                'Could not convert database value "%s" from "%s" to %s',
                $value,
                get_debug_type($value),
                UuidInterface::class
            )
        );
    }

    public function convertToDatabaseValue(mixed $value): ?Binary
    {
        return self::convertPHPToDatabaseValue($value);
    }

    public static function convertPHPToDatabaseValue(mixed $value): ?Binary
    {
        if (null === $value || [] === $value) {
            return null;
        }

        if ($value instanceof Binary) {
            return $value;
        }

        if ($value instanceof UuidInterface) {
            return new Binary($value->getBytes(), Binary::TYPE_UUID);
        }

        if (is_string($value) && Uuid::isValid($value)) {
            return new Binary(Uuid::fromString($value)->getBytes(), Binary::TYPE_UUID);
        }

        throw new InvalidArgumentException(
            sprintf(
                'Could not convert database value "%s" from "%s" to %s',
                $value,
                get_debug_type($value),
                Binary::class
            )
        );
    }

    public function getNextVersion($current): UuidInterface
    {
        return Uuid::uuid4();
    }
}
