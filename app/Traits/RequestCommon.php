<?php namespace App\Traits;

trait RequestCommon
{
    /**
     * @param array $data
     * @param         $index
     *
     * @return array|bool|\Illuminate\Http\UploadedFile|mixed|null
     */
    public function getRequestFile(array $data, $index)
    {
        $hasFile = true;
        if (in_array($index , $data)) {
            $file = $data[$index];
            if (!is_file($file))
                $hasFile = false;
        } else {
            $hasFile = false;
        }

        if ($hasFile)
            return $file;
        else
            return $hasFile;
    }

    /**
     * @param \Illuminate\Foundation\Http\FormRequest $request
     *
     * @return bool
     */
    public function isRequestFromApp(\Illuminate\Foundation\Http\FormRequest $request): bool
    {
        $isApp = (strlen(strstr($request->header('User-Agent'), "Alaa")) > 0) ? true : false;
        return $isApp;
    }

    /**
     * Copy source request in to the new request
     *
     * @param \Illuminate\Foundation\Http\FormRequest $sourceRequest
     * @param \Illuminate\Foundation\Http\FormRequest $newRequest
     * @return void
     */
    public function copyRequest(\Illuminate\Foundation\Http\FormRequest $sourceRequest , \Illuminate\Foundation\Http\FormRequest &$newRequest):void
    {
        $newRequest->merge($sourceRequest->all());
        $user = $sourceRequest->user() ;
        if(isset($user))
            $newRequest->setUserResolver(function () use ($user) {
                return $user;
            });
    }

    /**
     * Converts a request to an Ajax request
     *
     * @param \Illuminate\Foundation\Http\FormRequest $request
     * @return void
     */
    static public function convertRequestToAjax(\Illuminate\Foundation\Http\FormRequest &$request):void
    {
        $request->headers->add(["X-Requested-With" => "XMLHttpRequest"]);
    }

    /**
     * Trims user form request by unsetting forbidden keys
     *
     * @param \Illuminate\Http\Request $request
     */
    public function trimUserFormRequest(\Illuminate\Http\Request &$request):void
    {
        $securedFields = \App\User::getSecureFillable();
        foreach ($request->all() as $key => $value)
        {
            if(in_array($key , $securedFields) && isset($user->$key))
                $request->offsetUnset($key);
        }

        $protected = \App\User::getBeProtected();
        foreach ($request->all() as $key => $value)
        {
            if(in_array($key , $protected))
                $request->offsetUnset($key);
        }
    }

    /**
     * Determines whether intended request involves logged in user or another user
     *
     * @param $user
     * @param \Illuminate\Foundation\Http\FormRequest $request
     * @return bool
     */
    public function hasRequestAuthUser($user , \Illuminate\Foundation\Http\FormRequest $request) :bool
    {
        return $user->id == $request->user()->id;
    }
}