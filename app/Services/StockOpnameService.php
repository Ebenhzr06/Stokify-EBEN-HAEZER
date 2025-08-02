<?php

namespace App\Services;

use App\Repositories\StockOpnameRepositoryInterface;

class StockOpnameService implements StockOpnameServiceInterface
{
    protected $stockOpnameRepository;

    public function __construct(StockOpnameRepositoryInterface $stockOpnameRepository)
    {
        $this->stockOpnameRepository = $stockOpnameRepository;
    }

    public function getAll()
    {
        return $this->stockOpnameRepository->getAll();
    }

    public function findById($id)
    {
        return $this->stockOpnameRepository->findById($id);
    }

    public function create( $data)
    {
        return $this->stockOpnameRepository->create($data);
    }

    public function update($id,  $data)
    {
        return $this->stockOpnameRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->stockOpnameRepository->delete($id);
    }
}
