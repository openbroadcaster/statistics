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
        $this->permission_enable('administration', 'data_statistics_module', 'data & statistics module');

        return true;
	}

	public function uninstall()
	{
        $this->permission_disable('data_statistics_module');

        return true;
	}

    public function purge()
    {
        $this->permission_delete('data_statistics_module');

        return true;
    }
}
