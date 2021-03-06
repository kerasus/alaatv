<?php


namespace App\Traits\Set;


use Illuminate\Http\Response;

trait TaggableSetTrait
{
    public function retrievingTags()
    {
        /**
         *      Retrieving Tags
         */
        $response = $this->sendRequest(config("constants.TAG_API_URL") . "id/contentset/" . $this->id, "GET");

        if ($response["statusCode"] == Response::HTTP_OK) {
            $result = json_decode($response["result"]);
            $tags   = $result->data->tags;
        } else {
            $tags = [];
        }

        return $tags;
    }

    public function getTaggableTags()
    {
        return $this->tags->tags;
    }

    public function getTaggableId(): int
    {
        return $this->id;
    }

    public function getTaggableScore()
    {
        return $this->created_at !== null ? $this->created_at->timestamp : null;
    }

    public function isTaggableActive(): bool
    {
        if ($this->isActive() && isset($this->tags) && !empty($this->tags->tags)) {
            return true;
        }

        return false;
    }
}
