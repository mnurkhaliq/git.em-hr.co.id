<?php

/**
 * Get List Backup
 * @return [type] [description]
 */
function get_divisions()
{
	$auth = \Auth::user();
	if($auth)
	{
        return \App\Models\OrganisasiDivision::orderBy('id', 'ASC')->get();
	}
}