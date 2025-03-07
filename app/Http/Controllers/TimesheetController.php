<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Timesheet;

class TimesheetController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $timesheets = $user->timesheets()->with(['user', 'project'])->get();
        return response()->json($timesheets);
    }

    public function show($id)
    {
        try{

        
        $timesheet = Timesheet::with(['user', 'project'])->findOrFail($id);
        return response()->json($timesheet);}
        catch(\Exception $e){
            return response()->json($e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try{
            $user = $request->user();
        $validated = $request->validate([
           // 'user_id'    => 'required|exists:users,id',
            'project_id' => 'required|exists:projects,id',
            'task_name'  => 'required|string',
            'date'       => 'required|date',
            'hours'      => 'required|numeric'
        ]);

        $timesheet = $user->timesheets()->create($validated);
        return response()->json($timesheet, 201);}
        catch(\Exception $e){
            return response()->json($e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $timesheet = Timesheet::findOrFail($id);
            $validated = $request->validate([
           // 'user_id'    => 'sometimes|required|exists:users,id',
            'project_id' => 'sometimes|required|exists:projects,id',
            'task_name'  => 'sometimes|required|string',
            'date'       => 'sometimes|required|date',
            'hours'      => 'sometimes|required|numeric'
            ]);

            $timesheet->update($validated);
            return response()->json($timesheet);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function destroy($id)
    {
        try{

        
        $timesheet = Timesheet::findOrFail($id);
        $timesheet->delete();
        return response()->json(['message' => 'Timesheet deleted successfully']);}
        catch(\Exception $e){
            return response()->json($e->getMessage());
        }
    }
}
