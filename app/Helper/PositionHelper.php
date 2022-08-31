<?php

/**
 * Get List Backup
 * @return [type] [description]
 */
function get_positions()
{
	$auth = \Auth::user();
	if($auth)
	{
        return \App\Models\OrganisasiPosition::orderBy('id', 'ASC')->get();
	}
}