<?php

namespace App\Services;

use App\Repositories\ServerRepository;
use App\Services\Contracts\CreateServerServiceInterface;

class CreateServerService
{
    var $repo;

    public function __construct(ServerRepository $repo)
    {
        $this->repo = $repo;
    }
    public function make(array $request)
    {

        return $this->repo->create($request);
    }
}
