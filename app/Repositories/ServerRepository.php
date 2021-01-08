<?php

namespace App\Repositories;

use App\Models\Server;
use App\Repositories\BaseRepository;

/**
 * Class ServerRepository
 * @package App\Repositories
 * @version January 8, 2021, 6:18 pm UTC
 */

class ServerRepository extends BaseRepository
{
    public function getAllowedFilters()
    {
        return [
            'name',
            'ip_code',
        ];
    }

    public function getAllowedIncludes()
    {
        return [];
    }

    public function getAllowedFields()
    {
        return [
            'name',
            'ip_code',
            'locale',
        ];
    }

    public function getAllowedSorts()
    {
        return [
            'name',
            'ip_code',
            'locale',
        ];
    }

    public function model()
    {
        return Server::class;
    }
}
