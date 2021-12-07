<?php

namespace App\Manager;

use Doctrine\ORM\Query;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;

abstract class AbstractManager
{
    protected function getPager(Query $query, $limit=10, $page=1): Pagerfanta
    {
        $adapter = new QueryAdapter($query);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage($limit);
        $pagerfanta->setCurrentPage($page);

        return $pagerfanta;        
    }
}
