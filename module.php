<?php

class DataAndStatisticsModule extends OBFModule
{

	public $name = 'Data & Statistics v1.0';
	public $description = 'Provide stats for media, playlists, and other OpenBroadcaster Server data.';

	public function callbacks()
	{

	}

	public function install()
	{
      // add permissions data for this module
      $this->db->insert('users_permissions', [
        'category'=>'administration',
        'description'=>'data & statistics module',
        'name'=>'data_statistics_module'
      ]);
      
      return true;
	}

	public function uninstall()
	{
      // remove permissions data for this module
      $this->db->where('name','data_statistics_module');
      $permission = $this->db->get_one('users_permissions');
      
      $this->db->where('permission_id',$permission['id']);
      $this->db->delete('users_permissions_to_groups');
      
      $this->db->where('id',$permission['id']);
      $this->db->delete('users_permissions');
      
      return true;
	}
}
