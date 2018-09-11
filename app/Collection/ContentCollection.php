<?php
/**
 * Created by PhpStorm.
 * User: sohrab
 * Date: 2018-08-21
 * Time: 15:53
 */

namespace App\Collection;


use App\Content;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as BaseCollection;


class ContentCollection extends Collection
{
    public function videos()
    {
        return $this->where("contenttype_id", Content::CONTENT_TYPE_VIDEO);
    }

    public function pamphlets()
    {
        return $this->where("contenttype_id", Content::CONTENT_TYPE_PAMPHLET);
    }

    public function articles()
    {
        return $this->where("contenttype_id", Content::CONTENT_TYPE_ARTICLE);
    }

    public function flashcards()
    {
        throw new \LogicException('define Content::CONTENT_TYPE_FLASHCARD');
    }

    public function normalMates(): BaseCollection
    {
        $items = $this;
        $result = collect();

        foreach ($items as $content) {

            $myContentType = optional($content->contenttype)->name;
            $result->push([
                "content" => $content,
                "type" => $myContentType,
                "thumbnail" => $content->thumbnail,
                "session" => $content->session
            ]);
        }
        return $result;
    }
}