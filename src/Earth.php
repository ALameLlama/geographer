<?php

declare(strict_types=1);

namespace ALameLlama\Geographer;

use ALameLlama\Geographer\Collections\MemberCollection;
use ALameLlama\Geographer\Services\DefaultManager;

/**
 * Class Earth
 *
 * @method mixed findOneByCode($code)
 * @method string getName()
 * @method mixed findOneByName($name)
 */
class Earth extends Divisible
{
    /**
     * @var string
     */
    protected $memberClass = Country::class;

    protected static $parentClass;

    /**
     * @var array
     */
    protected $exposed = [
        'code',
        'name',
    ];

    /**
     * {@inheritdoc}
     */
    public static function build($id, $config = null): static
    {
        $config = $config ?: new DefaultManager;

        return new self([], null, $config);
    }

    /**
     * @return MemberCollection
     */
    public function getCountries()
    {
        return $this->getMembers();
    }

    public function getShortName(): string
    {
        return 'Earth';
    }

    public function getLongName(): string
    {
        return 'The Blue Marble';
    }

    public function getCode(): string
    {
        return 'SOL-III';
    }

    public function getParentCode()
    {
        return null;
    }

    /**
     * @return MemberCollection
     */
    public function getAfrica()
    {
        return $this->find([
            'continent' => 'AF',
        ]);
    }

    /**
     * @return MemberCollection
     */
    public function getNorthAmerica()
    {
        return $this->find([
            'continent' => 'NA',
        ]);
    }

    /**
     * @return MemberCollection
     */
    public function getSouthAmerica()
    {
        return $this->find([
            'continent' => 'SA',
        ]);
    }

    /**
     * @return MemberCollection
     */
    public function getAsia()
    {
        return $this->find([
            'continent' => 'AS',
        ]);
    }

    /**
     * @return MemberCollection
     */
    public function getEurope()
    {
        return $this->find([
            'continent' => 'EU',
        ]);
    }

    /**
     * @return MemberCollection
     */
    public function getOceania()
    {
        return $this->find([
            'continent' => 'OC',
        ]);
    }

    /**
     * @return static
     */
    public function withoutMicro(): MemberCollection
    {
        return $this->getMembers()->filter(fn ($item): bool => $item->getPopulation() > 100000);
    }
}
