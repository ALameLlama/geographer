<?php

declare(strict_types=1);

namespace ALameLlama\Geographer\Traits;

/**
 * Class HasCollection
 */
trait HasCollection
{
    /**
     * @return mixed
     */
    public function find(array $params = [])
    {
        return $this->getMembers()->find($params);
    }

    /**
     * @return mixed
     */
    public function findOne(array $params = [])
    {
        return $this->getMembers()->findOne($params);
    }
}
