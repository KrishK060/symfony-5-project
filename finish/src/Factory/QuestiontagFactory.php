<?php

namespace App\Factory;

use App\Entity\Questiontag;
use App\Repository\QuestiontagRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Questiontag>
 *
 * @method static Questiontag|Proxy createOne(array $attributes = [])
 * @method static Questiontag[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Questiontag[]|Proxy[] createSequence(array|callable $sequence)
 * @method static Questiontag|Proxy find(object|array|mixed $criteria)
 * @method static Questiontag|Proxy findOrCreate(array $attributes)
 * @method static Questiontag|Proxy first(string $sortedField = 'id')
 * @method static Questiontag|Proxy last(string $sortedField = 'id')
 * @method static Questiontag|Proxy random(array $attributes = [])
 * @method static Questiontag|Proxy randomOrCreate(array $attributes = [])
 * @method static Questiontag[]|Proxy[] all()
 * @method static Questiontag[]|Proxy[] findBy(array $attributes)
 * @method static Questiontag[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Questiontag[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static QuestiontagRepository|RepositoryProxy repository()
 * @method Questiontag|Proxy create(array|callable $attributes = [])
 */
final class QuestiontagFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [
            'question' => QuestionFactory::new(),
            'tag' => TagFactory::new(),
            'taggedAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Questiontag $questiontag): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Questiontag::class;
    }
}
