<?php

namespace App\Services;

use App\Models\MovementService;
use App\Repositories\MovementRepository;

class MovementService
{
    protected $repository;

    public function __construct(MovementRepository $repository)
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
