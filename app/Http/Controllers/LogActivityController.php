<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use App\Models\LogActivity as ModelsLogActivity;
use Illuminate\Http\Request;

class LogActivityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // $logsModel = new ModelsLogActivity;
        $logs= filterData('\App\Models\LogActivity');
        // if (!auth()->user()->hasRole('super-admin')) {
        //     $logs = ModelsLogActivity::where('company_id',getCompanyId())->get();
        // }
        return view('admin.logActivity', ["logs"=>$logs]);
    }

    public function destroy(ModelsLogActivity $log)
    {
        ModelsLogActivity::destroy($log->id);
        return redirect('/admin/log')->with("status", "Log deleted successfully");
    }


}
