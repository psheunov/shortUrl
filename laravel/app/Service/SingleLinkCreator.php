<?php


namespace App\Service;


use App\Models\Link;
use App\Result\Result;
use App\Validator\LinkValidator;

class SingleLinkCreator implements LinkCreator
{
    public function __construct()
    {
    }

    /**
     * @throws \Exception
     */
    public function create($data): Result
    {
        $result = new Result();
        try {
            // TODO: Get rid of code duplication
            $data['short_url'] = ShortUrlGeneric::generate();
            $validator = LinkValidator::createValidation($data);
            if ($validator->fails()) {
                $result->setStatus(400);
                $result->addMessage($validator->getMessageBag());
            } else {
                $link = Link::create($data);
                $result->addMessage($link->toArray()->toArray());
            }
        } catch (\Exception $e) {
            $result->setStatus(500);
            $result->addMessage('Server error');
        }

        return $result;
    }
}
