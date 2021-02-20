<?php

namespace Modules\Idocs\Events\Handlers;

use Illuminate\Contracts\Mail\Mailer;
use Modules\Idocs\Entities\DocumentUser;
use Modules\Idocs\Events\DocumentWasCreated;
use Modules\Idocs\Emails\Sendmail;
use Modules\Idocs\Events\DocumentWasDownloaded;
use Modules\Setting\Contracts\Setting;

class TrackingDocument
{
  

    public function __construct()
    {
    
    }

    public function handle(DocumentWasDownloaded $event)
    {
        try {
        
          $document = $event->document;
          $key = $event->key;
          $user = \Auth::user();
          
          $query = DocumentUser::where('document_id',$document->id);
          if(isset($user->id)){
            $query->where("user_id",$user->id);
          }else{
            if($key){
              $query->where("key",$key);
            }
          }
          $documentUser = $query->first();

          $documentUser->downloaded = $documentUser->downloaded+1;
          $documentUser->save();
          
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return $e;
        }
    }
}