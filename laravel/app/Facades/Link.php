<?php


namespace App\Facades;


use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\Link as LinkModel;

class Link
{
    private static function validate($data)
    {
        $result = [
            'status' => 'success',
        ];
        $rules = [
            'long_url'  => 'required|url',
            //'short_url' => 'required|unique:links',
            'title'     => 'string|max:200',
            'tags'      => 'array',
        ];

        $validator = Validator::make($data, $rules);
        if (!empty($validator->errors()->messages())) {
            $result = [
                'status'   => 'error',
                'messages' => $validator->errors()->messages()
            ];
        }
        return $result;
    }

    /**
     * @param array $links
     */
    public static function create(array $links): array
    {
        $result = [];
        foreach ($links as $link) {
            $link['short_url'] = self::makeShortUrl();
            $validator = self::validate($link);

            if ($validator['status'] != 'error') {
                $link['title'] = $link['title'] ?? null;
                $link['tags'] = isset($link['tags']) ? json_encode($link['tags']) : null;
                //сформировать ответ
                LinkModel::create($link);
            }
            $result[] = $validator;
        }

        return $result;
    }

    public static function update(LinkModel $link, array $data): array
    {
        $validator = self::validate($data);
        if ($validator['status'] != 'error') {
            $link->title = $data['title'] ??  $link->title;
            $link->tags = isset($data['tags'] ) ? json_encode($data['tags']) : $link->tags;
            $link->long_url = $data['long_url'] ?? $link->long_url;
            $link->save();
        }

        return $validator;
    }

    private static function makeShortUrl(): string
    {
        $url = Str::random(random_int(3, 8));

        while (LinkModel::where('short_url', '=', $url)->first()) {
            $url = Str::random(random_int(3, 6));
        }
        return Str::lower($url);
    }
}
