<?php declare(strict_types=1);

namespace App\Tests\Integration\Document;

use App\Document\Foo;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class FooTest extends KernelTestCase
{
        public function testFoo(): void{
            $container = self::getContainer();

            $foo = new Foo('original message');

            /** @var DocumentManager $documentManager */
            $documentManager = $container->get(DocumentManager::class);
            $documentManager->persist($foo);
            $documentManager->flush();

            $foo->setMessage('new message');
            $documentManager->persist($foo);
            $documentManager->flush();

            $this->markTestIncomplete();
        }
}
