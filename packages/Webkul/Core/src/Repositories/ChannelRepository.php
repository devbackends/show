<?php

namespace Webkul\Core\Repositories;

use Webkul\Core\Eloquent\Repository;
use Illuminate\Support\Facades\Storage;
use Webkul\Core\Models\ChannelFooterIcons;
use Illuminate\Support\Facades\File;
class ChannelRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Core\Contracts\Channel';
    }

    /**
     * @param  array  $data
     * @return \Webkul\Core\Contracts\Channel
     */
    public function create(array $data)
    {
        $channel = $this->model->create($data);

        $channel->locales()->sync($data['locales']);

        $channel->currencies()->sync($data['currencies']);

        $this->uploadImages($data, $channel);

        $this->uploadImages($data, $channel, 'favicon');

        return $channel;
    }
     public function createFooter($channel_id){
         $facebookFooterIcons=new ChannelFooterIcons();
         $facebookFooterIcons->icon='images/custom-facebook-icon.svg';
         $facebookFooterIcons->url='#';
         $facebookFooterIcons->channel_id=$channel_id;
         $facebookFooterIcons->save();

         $instagramFooterIcons=new ChannelFooterIcons();
         $instagramFooterIcons->icon='images/custom-instagram-icon.svg';
         $instagramFooterIcons->url='#';
         $instagramFooterIcons->channel_id=$channel_id;
         $instagramFooterIcons->save();

         $twitterFooterIcons=new ChannelFooterIcons();
         $twitterFooterIcons->icon='images/custom-twitter-icon.svg';
         $twitterFooterIcons->url='#';
         $twitterFooterIcons->channel_id=$channel_id;
         $twitterFooterIcons->save();

         $youtubeFooterIcons=new ChannelFooterIcons();
         $youtubeFooterIcons->icon='images/custom-youtube-icon.svg';
         $youtubeFooterIcons->url='#';
         $youtubeFooterIcons->channel_id=$channel_id;
         $youtubeFooterIcons->save();

     }
    /**
     * @param  array  $data
     * @param  int  $id
     * @param  string  $attribute
     * @return \Webkul\Core\Contracts\Channel
     */
    public function update(array $data, $id, $attribute = "id")
    {
        $channel = $this->find($id);

        $channel->update($data);

        $channel->locales()->sync($data['locales']);

        $channel->currencies()->sync($data['currencies']);


        $this->uploadImages($data, $channel);

        $this->uploadImages($data, $channel, 'favicon');

        return $channel;
    }

    /**
     * @param  array  $data
     * @param  \Webkul\Core\Contratcs\Channel  $channel
     * @param  string  $type
     * @return void
     */
    public function uploadImages($data, $channel, $type = "logo")
    {
        if (isset($data[$type])) {
            foreach ($data[$type] as $imageId => $image) {
                $file = $type . '.' . $imageId;
                $dir = 'channel/' . $channel->id;

                if (request()->hasFile($file)) {
                    if ($channel->{$type}) {
                        Storage::delete($channel->{$type});
                    }

                    $channel->{$type} = request()->file($file)->store($dir);
                    $channel->save();
                }
            }
        } else {
            if ($channel->{$type}) {
                Storage::delete($channel->{$type});
            }

            $channel->{$type} = null;
            $channel->save();
        }
    }
    public function deleteIcon($id){
       $ChannelFooterIcons=ChannelFooterIcons::findOrFail($id);
        $ChannelFooterIcons ->delete();
        return json_encode(array("status"=>1,"message"=>"icon deleted succesfully"));
    }
    public function addIcon($request){
        $channel_id=$request->request->get('channel_id');
        if ($request->files->has("icon")) {
            $file= $request->file('icon');
            $destinationPath = storage_path().'/app/public/images/channel-'.$channel_id.'/';

           if($file->move($destinationPath,$file->getClientOriginalName())){
               $ChannelFooterIcons=new ChannelFooterIcons();
               $ChannelFooterIcons->icon='storage/images/channel-'.$channel_id.'/'.$file->getClientOriginalName();
               $ChannelFooterIcons->url=$request->request->get('url');
               $ChannelFooterIcons->channel_id=$request->request->get('channel_id');
               $ChannelFooterIcons->save();
               return json_encode(array('status'=>1,'message'=>'icon added succesfully'));
           }else{
               return json_encode(array('status'=>0,'message'=>'icon not added'));
           }
        }
    }
    public function updateIconUrl($request){
        $icon_id=$request->request->get('icon_id');
        $url=$request->request->get('url');
        $ChannelFooterIcons=ChannelFooterIcons::findOrFail($icon_id);
        $ChannelFooterIcons->url=$url;
        $ChannelFooterIcons->save();
        return json_encode(array('status'=>1,'message'=>'icon url updated succesfully'));
    }
    public function updateFooterIcon($request){
        $channel_id=$request->request->get('channel_id');
        $icon_id=$request->request->get('icon_id');

        if ($request->files->has("icon")) {
            $file= $request->file('icon');
            $destinationPath = storage_path().'/app/public/images/channel-'.$channel_id.'/';

            if($file->move($destinationPath,$file->getClientOriginalName())){
                $ChannelFooterIcons=ChannelFooterIcons::findOrFail($icon_id);
                $ChannelFooterIcons->icon='storage/images/channel-'.$channel_id.'/'.$file->getClientOriginalName();
                $ChannelFooterIcons->save();
                return json_encode(array('status'=>1,'message'=>'icon updated succesfully'));
            }else{
                return json_encode(array('status'=>0,'message'=>'icon updated added'));
            }
        }
    }
    public function getFooterIcons($channel_id){
         $ChannelFooterIcons=ChannelFooterIcons::where('channel_id', $channel_id)->get();
         return json_encode($ChannelFooterIcons);
    }
}
