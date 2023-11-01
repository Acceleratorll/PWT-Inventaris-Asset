<?php

namespace App\Services;

use App\Repositories\ProfileRepository;

class ProfileService
{
    protected $repository;

    public function __construct(ProfileRepository $repository)
    {
        $this->repository = $repository;
    }

    // Add your service methods here
    // For example:
    // public function getById($id)
    // {
    //     return $this->repository->find($id);
    // }

    // Define additional methods based on your project's needs
}
