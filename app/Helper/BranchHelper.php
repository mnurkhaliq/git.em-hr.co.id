<?php

/**
 * Get List Backup
 * @return [type] [description]
 */
function get_branches()
{
	$auth = \Auth::user();
	if($auth)
	{
        return \App\Models\Cabang::orderBy('id', 'ASC')->get();
	}
}