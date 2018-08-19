<?php

namespace App;

use App\Traits\APIRequestCommon;
use App\Traits\Helper;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Auth;
use Illuminate\Support\Facades\Storage;

/**
 * App\Educationalcontent
 *
 * @property int $id
 * @property int|null $author_id آی دی مشخص کننده به وجود آورنده اثر
 * @property int|null $contenttype_id آی دی مشخص کننده نوع محتوا
 * @property int|null $template_id آی دی مشخص کننده قالب این گرافیکی این محتوا
 * @property string|null $name نام محتوا
 * @property string|null $description توضیح درباره محتوا
 * @property string|null $metaTitle متا تایتل محتوا
 * @property string|null $metaDescription متا دیسکریپشن محتوا
 * @property string|null $metaKeywords متای کلمات کلیدی محتوا
 * @property string|null $tags تگ ها
 * @property string|null $context محتوا
 * @property int $order ترتیب
 * @property int $enable فعال یا غیر فعال بودن محتوا
 * @property string|null $validSince تاریخ شروع استفاده از محتوا
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Contentset[] $contentsets
 * @property-read \App\Contenttype|null $contenttype
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Contenttype[] $contenttypes
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read mixed $file
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Grade[] $grades
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Major[] $majors
 * @property-read \App\Template|null $template
 * @property-read \App\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Educationalcontent active()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Educationalcontent enable($enable = 1)
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Educationalcontent onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Educationalcontent soon()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Educationalcontent valid()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Educationalcontent whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Educationalcontent whereContenttypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Educationalcontent whereContext($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Educationalcontent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Educationalcontent whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Educationalcontent whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Educationalcontent whereEnable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Educationalcontent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Educationalcontent whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Educationalcontent whereMetaKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Educationalcontent whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Educationalcontent whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Educationalcontent whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Educationalcontent whereTags($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Educationalcontent whereTemplateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Educationalcontent whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Educationalcontent whereValidSince($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Educationalcontent withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Educationalcontent withoutTrashed()
 * @mixin \Eloquent
 */
class Educationalcontent extends Model
{
    use APIRequestCommon;
    use SoftDeletes;
    use Helper;

    /**      * The attributes that should be mutated to dates.        */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $fillable = [
        'name',
        'description',
        'context',
        'order',
        'validSince',
        'template_id',
        'metaTitle',
        'metaDescription',
        'metaKeywords',
        'tags',
        'author_id',
        'contenttype_id'
    ];

    public function grades()
    {
        return $this->belongsToMany('App\Grade');
    }

    public function majors()
    {
        return $this->belongsToMany('App\Major');
    }

    public function files()
    {
        return $this->belongsToMany(
            'App\File',
            'educationalcontent_file',
            'content_id',
            'file_id')->withPivot("caption", "label");
    }
    public function thumbnails(){
        return $this->files()->where('label','=','thumbnail');
    }
    public function sources(){
        return $this->files()->where('label','<>','thumbnail');
    }

    public function contentsets()
    {
        return $this->belongsToMany("\App\Contentset", "contentset_educationalcontent", "edc_id", "contentset_id")->withPivot("order", "isDefault");
    }

    public function template()
    {
        return $this->belongsTo("\App\Template");
    }

    public function user()
    {
        return $this->belongsTo("\App\User" , "author_id" ,"id");
    }

    /**
     * @return string
     * Converting Created_at field to jalali
     */
    public function CreatedAt_Jalali()
    {
        $explodedDateTime = explode(" ", $this->created_at);
//        $explodedTime = $explodedDateTime[1] ;
        return $this->convertDate($this->created_at, "toJalali");
    }

    /**
     * @return string
     * Converting Updated_at field to jalali
     */
    public function UpdatedAt_Jalali()
    {
        $explodedDateTime = explode(" ", $this->updated_at);
//        $explodedTime = $explodedDateTime[1] ;
        return $this->convertDate($this->updated_at, "toJalali");
    }

    /**
     * @return string
     * Converting Created_at field to jalali
     */
    public function validSince_Jalali()
    {
        $explodedDateTime = explode(" ", $this->validSince);
        $explodedTime = $explodedDateTime[1];
        return $this->convertDate($this->validSince, "toJalali") . " " . $explodedTime;
    }

    public function fileMultiplexer($contentTypes = array())
    {
        if (!empty($contentTypes)) {
            if (in_array(Contenttype::where("name", "exam")->get()->first()->id, $contentTypes)) {
                $disk = Config::get('constants.DISK18_CLOUD');
            } elseif (in_array(Contenttype::where("name", "pamphlet")->get()->first()->id, $contentTypes)) {
                $disk = Config::get('constants.DISK19_CLOUD');
            } elseif (in_array(Contenttype::where("name", "book")->get()->first()->id, $contentTypes)) {
                $disk = Config::get('constants.DISK20_CLOUD');
            }
            if (isset($disk))
                return $disk;
            else
                return false;
        } else {
            if ($this->contenttype_id == Contenttype::where("name", "exam")->get()->first()->id ) {
                $disk = Config::get('constants.DISK18_CLOUD');
            } elseif ($this->contenttype_id == Contenttype::where("name", "pamphlet")->get()->first()->id ) {
                $disk = Config::get('constants.DISK19_CLOUD');
            } elseif ($this->contenttype_id == Contenttype::where("name", "book")->get()->first()->id ) {
                $disk = Config::get('constants.DISK20_CLOUD');
            }

            if (isset($disk)) {

                $disk = Disk::where("name", $disk)->get()->first();
                return $disk;
            } else {
                return false;
            }
        }

        return false;
    }

    public function isValid(): bool
    {
        if ($this->validSince < Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now())->timezone('Asia/Tehran'))
            return true;
        return false;
    }

    public function isEnable(): bool
    {
        if ($this->enable)
            return true;
        return false;
    }

    /**
     * Scope a query to only include enable(or disable) EducationalContents.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $enable
     * @return \Illuminate\Database\Eloquent\Builder
     */

    public function scopeEnable($query, $enable = 1)
    {
        return $query->where('enable', $enable);
    }

    /**
     * Scope a query to only include Valid EducationalContents.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeValid($query)
    {
        return $query->where('validSince', '<', Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now())->timezone('Asia/Tehran'));
    }

    public function scopeActive($query){
        return $query->where('enable', 1)
                     ->where('validSince', '<', Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now())
                         ->timezone('Asia/Tehran')
                     );
    }

    /**
     * Scope a query to only include EducationalContents that will come soon.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSoon($query)
    {
        return $query->where('validSince', '>', Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now())->timezone('Asia/Tehran'));
    }

    public function contentsWithSameType($enable = 1, $valid = 1)
    {
        $contentsWithSameType = Educationalcontent::where("id", "<>", $this->id);
        if ($enable) $contentsWithSameType = $contentsWithSameType->enable();
        if ($valid) $contentsWithSameType = $contentsWithSameType->valid();
        $contentTypes = $this->contenttypes->pluck("id")->toArray();
        foreach ($contentTypes as $id) {
            $contentsWithSameType = $contentsWithSameType->whereHas("contenttypes", function ($q) use ($id) {
                $q->where("id", $id);
            });
        }
        return $contentsWithSameType;
    }

    /**
     * @return mixed
     * @throws Exception
     */

    public  function getOrder(){
        $key = "content:Order"
            .$this->cacheKey();
        $c = $this;
        return Cache::remember($key,Config::get("constants.CACHE_60"),function () use($c) {
            $sessionNumber = -1;
            $contenSets = $c->contentsets->where("pivot.isDefault" , 1)->first();
            if(isset($contenSets))
            {
                $order = $contenSets->pivot->order;
                if($order >= 0)
                    $sessionNumber = $contenSets->pivot->order;
            }
            return $sessionNumber;
        });
    }
    public function getDisplayName()
    {
        try {
            $key = "content:getDisplayName"
                .$this->cacheKey();
            $c = $this;
            return Cache::remember($key,Config::get("constants.CACHE_60"),function () use($c) {
                $displayName = "";
                $sessionNumber = $c->getOrder();
                if (isset($c->contenttype)) {
                    $displayName .=$c->contenttype->displayName." ";
                }
                $displayName .= ( isset($sessionNumber) && $sessionNumber > -1 ? "جلسه ".$sessionNumber." - ":"" )." ".(isset($c->name) ? $c->name : $c->user->name);
                return $displayName;
            });

        } catch (Exception $e) {
            throw $e;
        }
    }

    public function contenttypes()
    {
        return $this->belongsToMany('App\Contenttype', 'educationalcontent_contenttype', 'content_id', 'contenttype_id');
    }
    public function contenttype()
    {
        return $this->belongsTo('App\Contenttype');
    }

    public function displayMajors()
    {
        $displayMajors = "";
        foreach ($this->majors as $major) {
            if (count($this->majors) > 1 && $major->id != $this->majors->last()->id)
                $displayMajors .= $major->name . " / ";
            else
                $displayMajors .= $major->name . " ";
        }
        return $displayMajors;
    }

    public function getFileAttribute()
    {
        if (!is_null($this->files))
            return $this->files->first();
        return null;
    }

    public function getFilesUrl()
    {
        $files = $this->files;
        $links = collect();
        foreach ($files as $file) {
            $url = $file->getUrl();
            if (isset($url[0]))
                $links->push($url);
        }
        return $links;
    }

    public function getTagsAttribute($value)
    {
        return json_decode($value);
    }

    public function cacheKey()
    {
        $key = $this->getKey();
        $time= isset($this->update) ? $this->updated_at->timestamp : $this->created_at->timestamp;
        return sprintf(
            "%s-%s",
            //$this->getTable(),
            $key,
            $time
        );
    }

    public function getSetMates()
    {
        $contentSets = $this->contentsets->where("pivot.isDefault" , 1);
        $contentsWithSameSet = collect();
        $contentSetName = "" ;
        if($contentSets->isNotEmpty())
        {
            $contentSet = $contentSets->first();
            $contentSetName = $contentSet->name;
            $sameContents =  $contentSet->educationalcontents->where("enable" , 1)->sortBy("pivot.order") ;
            $sameContents->load('files');
            $sameContents->load('contenttype');

            foreach ($sameContents as $content)
            {
                if(!$content->isValid())
                    continue;

                $file = $content->files->where("pivot.label" , "thumbnail")->first();
                if(isset($file))
                    $thumbnailFile = $file->name;
                else
                    $thumbnailFile = "" ;

                if (isset($content->contenttype)) {
                    $myContentType = $content->contenttype->name;
                }else{
                    $myContentType ="";
                }
                $session = $content->pivot->order;
                $contentsWithSameSet->push([
                    "type"=> $myContentType ,
                    "content"=>$content ,
                    "thumbnail"=>$thumbnailFile ,
                    "session"=>$session
                ]);
            }
        }
        return [
            $contentsWithSameSet ,
            $contentSetName ,
            ];
    }

    public function retrievingTags()
    {
        /**
         *      Retrieving Tags
         */
        $response = $this->sendRequest(
            config("constants.TAG_API_URL")."id/content/".$this->id,
            "GET"
        );

        if($response["statusCode"] == 200)
        {
            $result = json_decode($response["result"]);
            $tags = $result->data->tags;
        } else
        {
            $tags =[];
        }

        return $tags ;
    }

//    public function setTagsAttribute($value)
//    {
//        return json_encode($value);
//    }


}
