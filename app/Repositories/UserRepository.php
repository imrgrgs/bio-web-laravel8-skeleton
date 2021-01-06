<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\BaseRepository;

/**
 * Class UserRepository
 * @package App\Repositories
 */

class UserRepository extends BaseRepository
{
    public function getAllowedFilters()
    {
        return [
            'name',
            'email',
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
            'email',
            'active',
            'avatar',
            'module',
        ];
    }

    public function getAllowedSorts()
    {
        return [
            'name',
            'email',
            'active',
            'module',
        ];
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return User::class;
    }
}
