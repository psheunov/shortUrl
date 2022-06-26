<?php


namespace App\Service;


use App\Models\Link;
use App\Result\Result;
use App\Validator\LinkValidator;

class ButchLinkCreator implements LinkCreator
{
    public function create(array $data): Result
    {
        $result = new Result();

        foreach ($data as $item) {
            try {
                $item['short_url'] = ShortUrlGeneric::generate();
                $validator = LinkValidator::createValidation($item);

                if ($validator->fails()) {
                    $result->setStatus(400);
                    $result->addMessage($validator->getMessageBag()->toArray());
                    break;
                } else {
                    $link = Link::create($item);
                    $result->addMessage($link->toArray());
                }
            } catch (\Exception $e) {
                $result->setStatus(500);
                $result->addMessage('Server error');
                break;
            }
        }
        return $result;
    }
}
