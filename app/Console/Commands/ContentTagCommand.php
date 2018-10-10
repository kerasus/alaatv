<?php

namespace App\Console\Commands;

use App\Classes\Search\TaggingInterface;
use App\Content;
use App\Traits\TaggableTrait;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ContentTagCommand extends Command
{
    private $tagging;
    use TaggableTrait;

    /**
     * ContentTagCommand constructor.
     * @param TaggingInterface $tagging
     */
    public function __construct(TaggingInterface $tagging)
    {
        parent::__construct();
        $this->tagging = $tagging;
    }


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alaaTv:seed:content:tag {content : The ID of the content}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'add Tags for a Content';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $contentId = (int)$this->argument('content');
        if($contentId > 0){
            try {
                $content = Content::findOrFail($contentId);
            } catch (ModelNotFoundException $exception){
                $this->error($exception->getMessage());
                return;
            }
            if ($this->confirm('You have chosen\n\r '.$content->display_name.'. \n\rDo you wish to continue?',true)) {
                $this->performTaggingTaskForAContent($content);
            }
        }else{
            $this->performTaggingTaskForAllContents();
        }
    }
    private function  performTaggingTaskForAContent(Content $content){
        $this->sendTagsOfTaggableToApi($content, $this->tagging);
    }
    private function performTaggingTaskForAllContents(): void
    {
        $contents = Content::all();
        $bar = $this->output->createProgressBar($contents->count());
        foreach ($contents as $content) {
            $this->performTaggingTaskForAContent($content);
            $bar->advance();
        }
        $bar->finish();
        $this->info("");
    }
}
