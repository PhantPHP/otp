<?php

declare(strict_types=1);

namespace Fixture\Port\Repository;

use Psr\SimpleCache\CacheInterface;
use Phant\Error\NotFound;
use Phant\Otp\Entity\Request as EntityRequest;
use Phant\Otp\Entity\Request\Id;
use Fixture\DataStructure\Request as FixtureRequest;

final class Request implements \Phant\Otp\Port\Repository\Request
{
    public function __construct(
        protected CacheInterface $cache
    ) {
    }

    public function set(
        EntityRequest $request
    ): void {
        $this->cache->set((string)$request->id, $request);
    }

    public function get(
        Id $id
    ): EntityRequest {
        $entity = $this->cache->get((string)$id);
        if ($entity) {
            return $entity;
        }

        $entity = FixtureRequest::get();

        if ((string)$entity->id == (string)$id) {
            throw $entity;
        }

        throw new NotFound('Request not found from Id : ' . $id);
    }
}
