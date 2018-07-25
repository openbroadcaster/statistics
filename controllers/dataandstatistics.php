<?php

class DataAndStatistics extends OBFController
{

  public function __construct()
  {
    parent::__construct();
    $this->user->require_permission('data_statistics_module');
    $this->model = $this->load->model('DataAndStatistics');
  }
  
  public function get_all()
  {
    $return = [];
    $return['media_categories'] = $this->model('media_categories');
    $return['media_genres'] = $this->model('media_genres');
    $return['media_countries'] = $this->model('media_countries');
    $return['media_languages'] = $this->model('media_languages');
    $return['media_audio_formats'] = $this->model('media_audio_formats');
    $return['media_video_formats'] = $this->model('media_video_formats');
    $return['media_image_formats'] = $this->model('media_image_formats');
    $return['media_types'] = $this->model('media_types');
    $return['media_status'] = $this->model('media_status');
    $return['media_approved'] = $this->model('media_approved');
    $return['media_owner'] = $this->model('media_owner');
    $return['playlist_type'] = $this->model('playlist_type');
    $return['playlist_status'] = $this->model('playlist_status');
    
    return array(true,'Data and Statistics',$return);
  }

}
