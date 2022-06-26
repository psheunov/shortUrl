<?php


namespace App\Service;


use App\Models\Link;
use App\Result\Result;

class LinkCreatorService
{
    private LinkCreator $linkCreator;

    public function __construct(private array $data)
    {
        $this->initLinkCreator($this->data);
    }

    /**
     * @throws \Exception
     */
    public function create(): Result
    {
        return $this->linkCreator->create($this->data);
    }

    private function initLinkCreator($data)
    {
        if (array_intersect(array_keys($data), (new Link())->fillable)) {
            $this->linkCreator = new SingleLinkCreator();
        } else {
            $this->linkCreator = new ButchLinkCreator();
        }
    }

}
