<?php declare(strict_types=1);

namespace App\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[MongoDB\Document(collection: 'foo')]
class Foo
{
    #[MongoDB\Id(name: '_id', type: Uuid::class, strategy: 'NONE')]
    private UuidInterface $id;

    #[MongoDB\Field(type: 'string')]
    private string $message;

    #[MongoDB\Version]
    #[MongoDB\Field(type: Uuid::class)]
    private UuidInterface $version;

    public function __construct(string $message)
    {
        $this->id = Uuid::uuid4();

        $this->message = $message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
