<?php 


function dataVisit($start, $end, $branch, $id){
    $user = \Auth::user();

    
        if($user->project_id != NULL){
            $dataabsen      = App\Models\AbsensiItem::select('absensi_item.date')
                                            ->join('users', 'users.id', 'absensi_item.user_id')
                                            ->where('users.project_id', $user->project_id)
                                            ->groupBy('absensi_item.date')
                                            ->orderBy('absensi_item.date', 'DESC');

            if(!empty($start) && !empty($end)){
                $dataabsen = $dataabsen->whereBetween('absensi_item.date', [$start, $end]);
            }
        /*    else{
                $dataabsen = $dataabsen->whereBetween('absensi_item.date', [date('Y-m-d'), date('Y-m-d')]);
            }   */
        }else{
            $dataabsen      = App\Models\AbsensiItem::groupBy('date')
                                                    ->orderBy('date', 'DESC');

            if(!empty($start) && !empty($end)){
                $dataabsen = $dataabsen->whereBetween('date', [$start, $end]);
            }
        }

        $dataabsen = $dataabsen->paginate(100);

        $tanggal = $tgl = $data = $dd = $name = $user_id = [];
        $x = $j = $y = $z = $w = $v = $a = 0;
        for($i = 0; $i < count($dataabsen); $i++){
            $tanggal[$j] = $dataabsen[$i]->date;
            $arrayhari = array("Minggu"=>"Sun", "Senin"=>"Mon", "Selasa"=>"Tue", "Rabu"=>"Wed", "Kamis"=>"Thu", "Jumat"=>"Fri", "Sabtu"=>"Sat");
            $day[$v] = array_search(date_format(date_create($tanggal[$j]), "D"), $arrayhari);

            if($user->project_id != NULL){
                $karyawan   = \App\User::where('project_id', \Auth::user()->project_id)
                                        ->whereIn('access_id', ['1', '2'])
                                        ->where(function($query) use ($tanggal, $j){
                                            $query->whereNull('non_active_date')->orWhere('non_active_date', '>', $tanggal[$j]);
                                        })->where(function($query) use ($tanggal, $j){
                                            $query->whereNull('join_date')->orWhere('join_date', '<=', $tanggal[$j]);
                                        });

                if(!empty($branch)){
                    $karyawan = $karyawan->where('cabang_id', $branch);
                }
                if(!empty($id)){
                    $karyawan = $karyawan->where('id', $id);
                }
            }else{
                $karyawan   = \App\User::whereIn('access_id', ['1', '2'])
                                        ->where(function($query) use ($tanggal, $j){
                                            $query->whereNull('non_active_date')->orWhere('non_active_date', '>', $tanggal[$j]);
                                        })->where(function($query) use ($tanggal, $j){
                                            $query->whereNull('join_date')->orWhere('join_date', '<=', $tanggal[$j]);
                                        });
                                        
                if(!empty($branch)){
                    $karyawan = $karyawan->where('cabang_id', $branch);
                }
                if(!empty($id)){
                    $karyawan = $karyawan->where('id', $id);
                }
            }
            
            $karyawan = $karyawan->orderBy('name', 'ASC')->paginate(100);
            

            for($no = 0; $no < count($karyawan); $no++){
                $nik[$w] = $karyawan[$no]->nik;
                $name[$z] = $karyawan[$no]->name;
                $user_id[$a] = $karyawan[$no]->id;
                if($user->project_id != NULL){
                    $data[$x]     = App\Models\AbsensiItem::join('users','users.id','=','absensi_item.user_id')
                                                            ->where('users.project_id', $user->project_id)
                                                            ->where('absensi_item.date', $tanggal[$j])
                                                            ->where('users.id', $user_id[$a])
                                                            ->select('absensi_item.*', 'users.nik')
                                                            ->orderBy('absensi_item.date', 'DESC')
                                                            ->paginate(100);
                }else{
                    $data[$x]     = App\Models\AbsensiItem::where('date', $tanggal[$j])
                                                            ->where('user_id', $user_id[$a])
                                                            ->orderBy('date', 'DESC')
                                                            ->paginate(100);
                }

                if(count($data[$x]) < 1){
                    $array = array(
                                    'nik'  => $nik[$w],
                                    'name' => $name[$z], 
                                    'date' => $tanggal[$j],
                                    'timetable' => $day[$v],
                                    'late' => "",
                                    'early' => "",
                                    'work_time' => "00:00",
                                    'pic' => "",
                                    'lat' => "00:00",
                                    'long' => "00:00",
                                    'clock_in' => "00:00",
                                    'clock_out' => "00:00"
                                );
                    if(date('Y-m-d') > $tanggal[$j]){
                        $array2 = [];
                        array_push($array2, $array);
                        $data[$x] = $array2;
                    }
                }

                $dd[$x] = $data[$x][0];
                $name[$z++];
                $user_id[$a++];
                $nik[$w++];
                $data[$x++];
                
            }
            $tanggal[$j++];
            $day[$v++];
        }
    //    dd(json_encode($name), json_encode($tanggal));
        
        $dataabsensi = $dd;
    

    return $dataabsensi;
}


function cabangvisit(){
    if(\Auth::user()->project_id != Null){
        $cabang = App\Models\Cabang::join('users', 'users.id', '=', 'cabang.user_created')
                                ->where('users.project_id', \Auth::user()->project_id)
                                ->whereNotNull('latitude')
                                ->whereNotNull('longitude')
                                ->whereNotNull('radius')
                                ->select('cabang.*')
                                ->get();
    }else{
        $cabang = App\Models\Cabang::whereNotNull('latitude')
        ->whereNotNull('longitude')
        ->whereNotNull('radius')
        ->get();
    }
    
    return $cabang;
}

function getNamaCabangvisit($id){
    $cabang = App\Models\Cabang::where('id', $id)->first();
    return $cabang;
}