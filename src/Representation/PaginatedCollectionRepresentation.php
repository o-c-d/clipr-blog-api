<?php

namespace App\Representation;

use Pagerfanta\Pagerfanta;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PaginatedCollectionRepresentation
{
    private Pagerfanta $pager;
    private UrlGeneratorInterface $router;
    private string $routeName;
    private array $links;

    public function __construct(Pagerfanta $pager, UrlGeneratorInterface $router, string $routeName)
    {
        $this->pager = $pager;
        $this->router = $router;
        $this->routeName = $routeName;
        $this->addLinks();
    }

    private function setLink(string $ref, string $routeName, ?array $routeParams=[]): void 
    {
        $this->links[$ref] = $this->router->generate($routeName, $routeParams, UrlGeneratorInterface::ABSOLUTE_URL);
    }

    private function addLinks(): void 
    {
        $this->setLink('self', $this->routeName, [
            "page" => $this->pager->getCurrentPage(),
            "limit" => $this->pager->getMaxPerPage(),
        ]);
        if($this->pager->getNbPages()>1) {
            $this->setLink('first', $this->routeName, [
                "page" => 1,
                "limit" => $this->pager->getMaxPerPage(),
            ]);
            if($this->pager->hasPreviousPage()) {
                $this->setLink('previous', $this->routeName, [
                    "page" => $this->pager->getPreviousPage(),
                    "limit" => $this->pager->getMaxPerPage(),
                ]);
            }
            if($this->pager->hasNextPage()) {
                $this->setLink('next', $this->routeName, [
                    "page" => $this->pager->getNextPage(),
                    "limit" => $this->pager->getMaxPerPage(),
                ]);
            }
            $this->setLink('last', $this->routeName, [
                "page" => $this->pager->getNbPages(),
                "limit" => $this->pager->getMaxPerPage(),
            ]);
        }
    }

    public function represent(): array
    {
        return [
            "items" => $this->pager->getCurrentPageResults(),
            "count" => count($this->pager->getCurrentPageResults()),
            "total" => $this->pager->getNbResults(),
            "_links" => $this->links,
        ];
    }
}