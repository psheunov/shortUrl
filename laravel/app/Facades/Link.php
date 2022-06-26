<?php
declare(strict_types=1);

namespace App\Facades;


use App\Result\Result;
use App\Service\LinkCreatorService;
use App\Validator\LinkValidator;
use App\Models\Link as LinkModel;

class Link
{
    /**
     * @param array $data
     * @return \App\Result\Result
     * @throws \Exception
     */
    public static function create(array $data): Result
    {
        return (new LinkCreatorService($data))->create();
    }

    public static function update(LinkModel $link, array $data): Result
    {
        $result = new Result();

        try {
            $validator = LinkValidator::updateValidation($data);

            if ($validator->fails()) {
                $result->setStatus(400);
                $result->addMessage($validator->getMessageBag()->toArray());
            } else {
                $link->title = $data['title'] ?? $link->title;
                $link->tags = $data['tags'] ?? $link->tags;
                $link->save();
                $result->addMessage('Link updated successfully');
            }
        } catch (\Exception $e) {
            $result->setStatus(500);
            $result->addMessage('Server error');
        }

        return $result;
    }
}
