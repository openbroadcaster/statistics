<?php

class DataAndStatisticsModule extends OBFModule
{

	public $name = 'Data & Statistics v1.0';
	public $description = 'Provide graphs and statistics for media, playlists and other OBServer data.';

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
        $this->db->delete('users_permissions');

        return true;
	}

    public function purge()
    {
        return true;
    }
}
