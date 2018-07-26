<?php

class DataAndStatisticsModel extends OBFModel
{
  public function media_types()
  {
    $this->db->query('SELECT type as name,count(*) AS count,sum(duration) as duration FROM media GROUP BY type ORDER BY count DESC, duration DESC');
    $rows = $this->db->assoc_list();
    return $rows;
  }
  
  public function media_categories()
  {
    $this->db->query('SELECT media_categories.name as name,count(*) as count,sum(duration) as duration
        FROM media 
        LEFT JOIN media_categories ON media.category_id=media_categories.id 
        GROUP BY media.category_id ORDER BY count DESC, duration DESC');
        
    $rows = $this->db->assoc_list();
    
    $missing = 0;
    $missing_duration = 0;
    foreach($rows as $index=>$row)
    {
      if($row['name']===null)
      { 
        $missing += $row['count'];
        $missing_duration += $row['duration'];
        unset($rows[$index]);
      }
    }
    
    if($missing>0) $rows[] = ['name'=>'(missing category)','count'=>$missing,'duration'=>$missing_duration];
    
    return $rows;
  }
  
  public function media_genres()
  {
    $this->db->query('SELECT media_categories.name as category_name,media_genres.name as name,count(*) as count,sum(duration) as duration 
        FROM media 
        LEFT JOIN media_genres ON media.genre_id=media_genres.id 
        LEFT JOIN media_categories ON media_genres.media_category_id=media_categories.id
        GROUP BY media.genre_id ORDER BY count DESC, duration DESC');
        
    $rows = $this->db->assoc_list();
    
    $missing = 0;
    $missing_duration = 0;
    foreach($rows as $index=>$row)
    {
      if($row['name']===null)
      { 
        $missing += $row['count'];
        $missing_duration += $row['duration'];
        unset($rows[$index]);
      }
      else
      {
        $rows[$index]['name'] = $row['category_name'].': '.$row['name'];
        unset($rows[$index]['category_name']);
      }
    }
    
    if($missing>0) $rows[] = ['name'=>'(missing genre)','count'=>$missing, 'duration'=>$missing_duration];
    
    return $rows;
  }
  
  public function media_countries()
  {
    $this->db->query('SELECT media_countries.name as name,count(*) as count,sum(duration) as duration 
        FROM media 
        LEFT JOIN media_countries ON media.country_id=media_countries.id 
        GROUP BY media.country_id ORDER BY count DESC, duration DESC');
        
    $rows = $this->db->assoc_list();
    
    $missing = 0;
    $missing_duration = 0;
    foreach($rows as $index=>$row)
    {
      if($row['name']===null)
      { 
        $missing += $row['count'];
        $missing_duration += $row['duration'];
        unset($rows[$index]);
      }
    }
    
    if($missing>0) $rows[] = ['name'=>'(missing country)','count'=>$missing,'duration'=>$missing_duration];
    
    return $rows;
  }
  
  public function media_languages()
  {
    $this->db->query('SELECT media_languages.name as name,count(*) as count,sum(duration) as duration
        FROM media 
        LEFT JOIN media_languages ON media.language_id=media_languages.id 
        GROUP BY media.language_id ORDER BY count DESC, duration DESC');
        
    $rows = $this->db->assoc_list();
    
    $missing = 0;
    $missing_duration = 0;
    foreach($rows as $index=>$row)
    {
      if($row['name']===null)
      { 
        $missing += $row['count'];
        $missing_duration += $row['duration'];
        unset($rows[$index]);
      }
    }
    
    if($missing>0) $rows[] = ['name'=>'(missing language)','count'=>$missing,'duration'=>$missing_duration];
    
    return $rows;
  }
  
  public function media_audio_formats()
  {
    $this->db->query('SELECT format as name,count(*) AS count,sum(duration) as duration FROM media WHERE type="audio" and format!="" GROUP BY format ORDER BY count DESC, duration DESC');
    $rows = $this->db->assoc_list();
    return $rows;
  }
  
  public function media_video_formats()
  {
    $this->db->query('SELECT format as name,count(*) AS count,sum(duration) as duration FROM media WHERE type="video" and format!="" GROUP BY format ORDER BY count DESC, duration DESC');
    $rows = $this->db->assoc_list();
    return $rows;
  }
  
  public function media_image_formats()
  {
    $this->db->query('SELECT format as name,count(*) AS count FROM media WHERE type="image" and format!="" GROUP BY format ORDER BY count DESC');
    $rows = $this->db->assoc_list();
    return $rows;
  }
  
  public function media_status()
  {
    $this->db->query('SELECT status as name,count(*) AS count,sum(duration) as duration FROM media GROUP BY status ORDER BY count DESC, duration DESC');
    $rows = $this->db->assoc_list();
    return $rows;
  }
  
  public function media_approved()
  {
    $this->db->query('SELECT (CASE WHEN is_approved <> 0 THEN "approved" ELSE "not approved" END) as name,count(*) AS count,sum(duration) as duration FROM media GROUP BY is_approved ORDER BY count DESC, duration DESC');
    $rows = $this->db->assoc_list();
    return $rows;
  }
  
  public function media_owner()
  {
    $this->db->query('SELECT (CASE WHEN is_copyright_owner <> 0 THEN "copyright owner" ELSE "not copyright owner" END) as name,count(*) AS count,sum(duration) as duration FROM media GROUP BY is_copyright_owner ORDER BY count DESC, duration DESC');
    $rows = $this->db->assoc_list();
    return $rows;
  }
  
  public function playlist_type()
  {
    $this->db->query('SELECT type as name,count(*) AS count FROM playlists GROUP BY type ORDER BY count DESC');
    $rows = $this->db->assoc_list();    
    return $rows;
  }
  
  public function playlist_status()
  {
    $this->db->query('SELECT status as name,count(*) AS count FROM playlists GROUP BY status ORDER BY count DESC');
    $rows = $this->db->assoc_list();
    return $rows;
  }
}