<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseApiController;
use App\Http\Middleware\ConnectionMiddleware;
use App\Http\Resources\AbsensiItemResources;
use App\Models\AbsensiItem;
use App\Models\Cuti;
use App\Models\CutiBersama;
use App\Models\CutiKaryawan;
use App\Models\CutiKaryawanDate;
use App\Models\Shift;
use App\Models\ShiftScheduleChange;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CalendarController extends BaseApiController
{
    public function __construct()
    {
        $this->middleware(ConnectionMiddleware::class);
        $this->middleware('jwt.auth');
    }

    public function getCalendar(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'year' => "required",
            'month' => "required",
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()], 401);
        }

        $start = Carbon::createFromDate($request->year, $request->month)->startOfMonth();
        $end = Carbon::createFromDate($request->year, $request->month)->endOfMonth();

        $publicHoliday = hari_libur($start, $end);

        $start->endOfDay();
        $shiftScheduleChange = ShiftScheduleChange::where('change_date', '<=', $end)->where('change_date', '>=', $start)->whereHas('shiftScheduleChangeEmployees', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->orderBy('change_date', 'DESC')->with('shift.details')->get();
        $currentShift = ShiftScheduleChange::where('change_date', '<', $start)->whereHas('shiftScheduleChangeEmployees', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->orderBy('change_date', 'DESC')->with('shift.details')->first();
        $currentShift = $currentShift ? $currentShift->shift : Shift::where('id', $user->shift_id)->with('details')->first();

        $leaveTaken = CutiKaryawanDate::whereBetween('tanggal_cuti', [$start, $end])->where('type', 1)->whereHas('cutiKaryawan', function ($query) use ($user) {
            $query->where('user_id', $user->id)->whereIn('status', [2, 6, 8]);
        })->with('cutiKaryawan.cuti')->get();

        $disabledDates = [];
        $start->startOfDay()->subDay();
        while ($start->diff($end)->days) {
            $loopDate = $start->addDay();
            $loopDateName = $loopDate->format('l');
            $loopDate = $loopDate->format('Y-m-d');

            $loopPublicHoliday = $publicHoliday->filter(function ($value) use ($loopDate) {
                return $value->tanggal == $loopDate;
            })->first();

            $loopShiftScheduleChange = $shiftScheduleChange->filter(function ($value) use ($loopDate) {
                return $value->change_date <= $loopDate;
            })->first();
            $loopShiftScheduleChange = ($loopShiftScheduleChange ? $loopShiftScheduleChange->shift : $currentShift);
            $loopShiftScheduleChangeDay = $loopShiftScheduleChange ? $loopShiftScheduleChange->details->filter(function ($value) use ($loopDateName) {
                return $value->day == $loopDateName;
            })->first() : $loopShiftScheduleChange;

            $loopLeaveTaken = $leaveTaken->filter(function ($value) use ($loopDate) {
                return $value->tanggal_cuti == $loopDate;
            })->first();

            $cutiBersama = CutiBersama::where('dari_tanggal', $start)->first();

            if ($loopLeaveTaken) {
                $disabledDates[] = [
                    'date' => $loopDate,
                    'type' => 4,
                    'is_disabled' => true,
                    'description' => $loopLeaveTaken->cutiKaryawan->keperluan,
                ];
            } else if ($cutiBersama && (!$loopShiftScheduleChange || ($loopShiftScheduleChange && (!$cutiBersama->impacttoleave || !$loopShiftScheduleChange->is_collective)))) {
                $disabledDates[] = [
                    'date' => $loopDate,
                    'type' => $cutiBersama->impacttoleave ? 4 : 3,
                    'is_disabled' => !$loopShiftScheduleChange || ($loopShiftScheduleChange && !$loopShiftScheduleChange->is_collective) ? true : false,
                    'description' => $cutiBersama->description,
                ];
            } else if ($loopPublicHoliday) {
                $disabledDates[] = [
                    'date' => $loopDate,
                    'type' => 3,
                    'is_disabled' => !$loopShiftScheduleChange || ($loopShiftScheduleChange && (!$loopShiftScheduleChange->is_holiday || !$loopShiftScheduleChangeDay)) ? true : false,
                    'description' => $loopPublicHoliday->keterangan,
                ];
            } else if ($loopShiftScheduleChange && !$loopShiftScheduleChangeDay) {
                $disabledDates[] = [
                    'date' => $loopDate,
                    'type' => 2,
                    'is_disabled' => true,
                    'description' => "Shift off day",
                ];
            }
        }

        $shifts = [];
        $start->startOfMonth();
        $shifts[] = [
            'start_date' => $start->format('Y-m-d'),
            'end_date' => $end->format('Y-m-d'),
            'shift' => $currentShift,
        ];
        foreach ($shiftScheduleChange->sortBy('change_date') as $value) {
            $shifts[count($shifts) - 1]['end_date'] = Carbon::parse($value->change_date)->subDay()->format('Y-m-d');
            $shifts[] = [
                'start_date' => Carbon::parse($value->change_date)->format('Y-m-d'),
                'end_date' => $end->format('Y-m-d'),
                'shift' => $value->shift,
            ];
        }

        $data = [
            'event_dates' => $disabledDates,
            'shifts' => $shifts,
        ];

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully',
            'data' => $data,
        ], 200);
    }

    public function getDate(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'date' => "required|date",
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()], 401);
        }

        $date = Carbon::parse($request->date)->startOfDay();

        $publicHoliday = hari_libur($date, $date)->first();

        $currentShift = ShiftScheduleChange::where('change_date', '<', $date->endOfDay())->whereHas('shiftScheduleChangeEmployees', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->orderBy('change_date', 'DESC')->with('shift.details')->first();
        $currentShift = $currentShift ? $currentShift->shift : Shift::where('id', $user->shift_id)->with('details')->first();
        if ($currentShift) {
            $currentShift->detail = $currentShift->details->filter(function ($value) use ($date) {
                return $value->day == $date->format('l');
            })->first();
            unset($currentShift->details);
        }

        $leaveTaken = CutiKaryawanDate::where('tanggal_cuti', $date->startOfDay())->where('type', 1)->whereHas('cutiKaryawan', function ($query) use ($user) {
            $query->where('user_id', $user->id)->whereIn('status', [2, 6, 8]);
        })->with('cutiKaryawan.cuti')->first();
        
        $cutiBersama = CutiBersama::where('dari_tanggal', $date)->first();

        $event = null;
        if ($leaveTaken) {
            $event = [
                'date' => $date->format('Y-m-d'),
                'type' => 4,
                'is_disabled' => true,
                'description' => $leaveTaken->cutiKaryawan->keperluan,
            ];
        } else if ($cutiBersama && $currentShift && (!$cutiBersama->impacttoleave || !$currentShift->is_collective)) {
            $event = [
                'date' => $date->format('Y-m-d'),
                'type' => $cutiBersama->impacttoleave ? 4 : 3,
                'is_disabled' => $currentShift && !$currentShift->is_collective ? true : false,
                'description' => $cutiBersama->description,
            ];
        } else if ($publicHoliday) {
            $event = [
                'date' => $date->format('Y-m-d'),
                'type' => 3,
                'is_disabled' => $currentShift && (!$currentShift->is_holiday || !$currentShift->detail) ? true : false,
                'description' => $publicHoliday->keterangan,
            ];
        } else if ($currentShift && !$currentShift->detail) {
            $event = [
                'date' => $date->format('Y-m-d'),
                'type' => 2,
                'is_disabled' => true,
                'description' => "Shift off day",
            ];
        }

        if ($currentShift && $event && $event['is_disabled']) {
            $currentShift->detail = null;
        }

        $attendance = AbsensiItem::where('user_id', $user->id)
            ->where('date', $date)
            ->orderByRaw("CONCAT(`date`, ' ', `clock_in`,':00') desc");

        $data = [
            'event' => $event,
            'shift' => $currentShift,
            'attendance' => AbsensiItemResources::collection($attendance->get()),
        ];

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully',
            'data' => $data,
        ], 200);
    }

    public function getDates(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()], 401);
        }

        $start = Carbon::parse($request->start_date)->startOfDay();
        $end = Carbon::parse($request->end_date)->endOfDay();

        $publicHoliday = hari_libur($start, $end);

        $start->endOfDay();
        $shiftScheduleChange = ShiftScheduleChange::where('change_date', '<=', $end)->where('change_date', '>=', $start)->whereHas('shiftScheduleChangeEmployees', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->orderBy('change_date', 'DESC')->with('shift.details')->get();
        $currentShift = ShiftScheduleChange::where('change_date', '<', $start)->whereHas('shiftScheduleChangeEmployees', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->orderBy('change_date', 'DESC')->with('shift.details')->first();
        $currentShift = $currentShift ? $currentShift->shift : Shift::where('id', $user->shift_id)->with('details')->first();

        $leaveTaken = CutiKaryawanDate::whereBetween('tanggal_cuti', [$start, $end])->where('type', 1)->whereHas('cutiKaryawan', function ($query) use ($user) {
            $query->where('user_id', $user->id)->whereIn('status', [2, 6, 8]);
        })->with('cutiKaryawan.cuti')->get();

        $start->startOfDay()->subDay();
        while ($start->diff($end)->days) {
            $loopDate = $start->addDay();
            $loopDateName = $loopDate->format('l');
            $loopDate = $loopDate->format('Y-m-d');

            $loopPublicHoliday = $publicHoliday->filter(function ($value) use ($loopDate) {
                return $value->tanggal == $loopDate;
            })->first();

            $loopShiftScheduleChange = $shiftScheduleChange->filter(function ($value) use ($loopDate) {
                return $value->change_date <= $loopDate;
            })->first();
            $loopShiftScheduleChange = ($loopShiftScheduleChange ? $loopShiftScheduleChange->shift : $currentShift);
            if ($loopShiftScheduleChange) {
                $loopShiftScheduleChange->detail = $loopShiftScheduleChange->details->filter(function ($value) use ($loopDateName) {
                    return $value->day == $loopDateName;
                })->first();
                unset($loopShiftScheduleChange->details);
            }

            $loopLeaveTaken = $leaveTaken->filter(function ($value) use ($loopDate) {
                return $value->tanggal_cuti == $loopDate;
            })->first();

            $cutiBersama = CutiBersama::where('dari_tanggal', $start)->first();

            $loopEvent = null;
            if ($loopLeaveTaken) {
                $loopEvent = [
                    'date' => $loopDate,
                    'type' => 4,
                    'is_disabled' => true,
                    'description' => $loopLeaveTaken->cutiKaryawan->keperluan,
                ];
            } else if ($cutiBersama && (!$loopShiftScheduleChange || ($loopShiftScheduleChange && (!$cutiBersama->impacttoleave || !$loopShiftScheduleChange->is_collective)))) {
                $loopEvent = [
                    'date' => $loopDate,
                    'type' => $cutiBersama->impacttoleave ? 4 : 3,
                    'is_disabled' => !$loopShiftScheduleChange || ($loopShiftScheduleChange && !$loopShiftScheduleChange->is_collective) ? true : false,
                    'description' => $cutiBersama->description,
                ];
            } else if ($loopPublicHoliday) {
                $loopEvent = [
                    'date' => $loopDate,
                    'type' => 3,
                    'is_disabled' => !$loopShiftScheduleChange || ($loopShiftScheduleChange && (!$loopShiftScheduleChange->is_holiday || !$loopShiftScheduleChange->detail)) ? true : false,
                    'description' => $loopPublicHoliday->keterangan,
                ];
            } else if ($loopShiftScheduleChange && !$loopShiftScheduleChange->detail) {
                $loopEvent = [
                    'date' => $loopDate,
                    'type' => 2,
                    'is_disabled' => true,
                    'description' => "Shift off day",
                ];
            }

            if ($loopShiftScheduleChange && $loopEvent && $loopEvent['is_disabled']) {
                $loopShiftScheduleChange->detail = null;
            }

            $data[$loopDate] = [
                'event' => $loopEvent,
                'shift' => $loopShiftScheduleChange ? $loopShiftScheduleChange->toArray() : $loopShiftScheduleChange,
                // 'attendance' => AbsensiItemResources::collection(AbsensiItem::where('date', $loopDate)->where('user_id', $user->id)->orderByRaw("CONCAT(`date`, ' ', `clock_in`,':00') desc")->get()),
            ];
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully',
            'data' => $data,
        ], 200);
    }
}
