<?php

/**
 * Count
 * @return integer
 */
function countStructureOrganization()
{
	$user = \Auth::user();
	if($user->project_id != NULL)
    {
    	return \App\Models\StructureOrganizationCustom::where('project_id', $user->project_id)->count();
    }else{
		return \App\Models\StructureOrganizationCustom::count();
    }
	
}

/**
 * get sub menu
 * @return object / objects
 */
function get_sub_structure($id)
{
	if(empty($id) || $id == 0) return 0;

	return \App\Models\StructureOrganizationCustom::where('parent_id',$id)->get();
}

/**
 * Select Navigation Form
 * @return string
 */
function structure_custom()
{
	$object = [];
	$structure = \App\Models\StructureOrganizationCustom::all();

	foreach($structure as $key => $item)
	{
		if($item->parent_id != 0) continue;

		$sub_menu = get_sub_structure($item->id);

		$object[$key]['title'] =  'Directure';
		$object[$key]['name'] =  $item->name;

	  	if(count($sub_menu) > 0)
	  	{
	  		$object[$key]['children']  = structure_ul_form_sub_menu($sub_menu, []);	
	  	}
	}

	return $object;
}

/**
 * Navigation Sub Menu
 * @return html
 */
function structure_ul_form_sub_menu($object, $data)
{	

	foreach($object as $key => $item)
	{
	  	$sub_menu = get_sub_structure($item->id);
	  	
	  	$data[$key]['title'] 	= $item->name .' title';
	  	$data[$key]['name'] 	= $item->name;

	  	// if(count($sub_menu) > 0)
	  	// {
	  	// 	$data['children'][$key] = structure_ul_form_sub_menu($sub_menu, []);	
	  	// }
	}

	return $data;
}

function get_user_structure_detail($user_id){
    $user = \App\User::find($user_id);
    if($user && $user->structure_organization_custom_id) {
        $structure = \App\Models\StructureOrganizationCustom::select(['organisasi_position.name as position', 'organisasi_division.name as division'])
            ->leftJoin('organisasi_position', 'organisasi_position.id', '=', 'structure_organization_custom.organisasi_position_id')
            ->leftJoin('organisasi_division', 'organisasi_division.id', '=', 'structure_organization_custom.organisasi_division_id')
            ->where('structure_organization_custom.id', $user->structure_organization_custom_id)
            ->first();
        return $structure;
    }
    return null;
}

function get_all_position_name(){
    $user = \Illuminate\Support\Facades\Auth::user();
    $structure = \App\Models\StructureOrganizationCustom::select(['structure_organization_custom.id','organisasi_position.name as position', 'organisasi_division.name as division'])
        ->leftJoin('organisasi_position', 'organisasi_position.id', '=', 'structure_organization_custom.organisasi_position_id')
        ->leftJoin('organisasi_division', 'organisasi_division.id', '=', 'structure_organization_custom.organisasi_division_id')
        ->leftJoin('organisasi_title', 'organisasi_title.id', '=', 'structure_organization_custom.organisasi_title_id')
        ->where('structure_organization_custom.project_id', $user->project_id)
        ->orderBy(\DB::raw("CONCAT(COALESCE(organisasi_position.name,''),IF(organisasi_division.name IS NOT NULL, CONCAT(' - ',COALESCE(organisasi_division.name,'')), ''),IF(organisasi_title.name IS NOT NULL, CONCAT(' - ',COALESCE(organisasi_title.name,'')), ''))"),'ASC')
        ->get();
    $pos_array = [];
    if($structure){
        foreach ($structure as $pos) {
            $position = $pos->position;
            if ($pos->division)
                $position .= " - " . $pos->division;
            array_push($pos_array, ['id' => $pos->id, 'position' => $position]);
        }
    }
    return $pos_array;
}


function get_position_name($structure_organization_custom_id){
    $structure = \App\Models\StructureOrganizationCustom::select(['organisasi_position.name as position', 'organisasi_division.name as division'])
        ->leftJoin('organisasi_position', 'organisasi_position.id', '=', 'structure_organization_custom.organisasi_position_id')
        ->leftJoin('organisasi_division', 'organisasi_division.id', '=', 'structure_organization_custom.organisasi_division_id')
        ->where('structure_organization_custom.id', $structure_organization_custom_id)
        ->first();
    if($structure){
        $position = $structure->position;
        if($structure->division)
            $position .= " - ".$structure->division;
        return $position;
    }

    return null;
}

function get_position_name2($structure_organization_custom_id){
    $structure = \App\Models\StructureOrganizationCustom::select(['organisasi_position.name as position'])
        ->leftJoin('organisasi_position', 'organisasi_position.id', '=', 'structure_organization_custom.organisasi_position_id')
        ->where('structure_organization_custom.id', $structure_organization_custom_id)
        ->first();
    if($structure){
        return $structure->position;
    }
    return "";
}

function get_bank_name($bank_id){
    $bank = \App\Models\Bank::find($bank_id);
    if($bank){
        return $bank->name;
    }
    else{
        return "";
    }
}

function get_division_name($structure_organization_custom_id){
    $structure = \App\Models\StructureOrganizationCustom::select(['organisasi_division.name as division'])
        ->leftJoin('organisasi_division', 'organisasi_division.id', '=', 'structure_organization_custom.organisasi_division_id')
        ->where('structure_organization_custom.id', $structure_organization_custom_id)
        ->first();
    if($structure){
        return $structure->division;
    }
    return "";
}
