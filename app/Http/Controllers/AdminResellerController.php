<?php

namespace App\Http\Controllers;
use App\Mail\KonfirmasiEmail;
use App\Mail\EmailApproval;
use App\Mail\EmailConfirmation;
use App\Models\City;
use App\Models\Company;
use App\Models\Product;
use App\Models\Province;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
// use LogHelper;

class AdminResellerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware(['permission:reseller.view'])->only('index');
        $this->middleware('permission:reseller.create')->only('store');
        $this->middleware('permission:reseller.edit')->only('update');
        $this->middleware('permission:reseller.delete')->only('destroy');
    }

    public function index()
    {
$products = filterData('\App\Models\Product');
$users = filterData('\App\Models\User');
        if (!auth()->user()->hasRole('super-admin')) {
            $products = Product::where('company_id',getCompanyId())->get();
            $users= User::where('company_id',getCompanyId())->get();
        }
        
        $reseller =[];
        foreach ($users as $user ) {
            if ($user->hasRole('reseller')) {
                array_push($reseller,$user);
            }
        }
        return view('admin.resellerAdmin', ['users' => $reseller, 'products' => $products]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'min:9', 'max:14'],
            'product_id' => ['required'],
            'address' => ['required']
        ]);


        $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $random = substr(str_shuffle($permitted_chars), 0, 6);
        $model_user = new User;
        $product = new Product;
        $regex = $product->getRegex($request['product_id']);
        $pass = Str::random(10);
        do {
            $ref_code = $regex->regex . '-' . $random;
            $check = $model_user->getRefCode($ref_code);
        } while ($check != null);

        if ($check == null) {
            $request->request->add(['password'=>$pass,'ref_code'=>$ref_code]);
            $user = $model_user->createReseller($request->all());
            
        }

        Mail::to($user['email'])->send(new EmailConfirmation($user->id, $pass));
        addToLog("Menambahkan Reseller" . $request->email);
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'min:9', 'max:14'],
        ]);

        User::where('id', $request->id)
            ->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);
        $role = $request->role == '1' ? ' Admin' : 'Reseller';
        addToLog("Mengubah data " . $role . " " . $request->email);
        return redirect()->back()->with('status', 'Sucess Update data ' . $request->name);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    
    public function destroy(Request $request)
    {
        User::destroy($request->id);
        addToLog("Menghapus akun" . $request->email);
        return redirect("/admin/reseller")->with('status', 'Data berhasil dihapus');
    }

    // custom
    public function searchByCompany($company)
    {
        $companies = Company::where('name',$company)->get()->first();
        $products = Product::all();

        $users =[];
        if ($companies->count()>0) {
            foreach ($companies->users as $user) {
                if($user->hasRole('reseller')){
                    array_push($users,$user);
                }
            }
        }
        return view('admin.resellerAdmin', ['users' => $users, 'products' => $products]);
    }

    public function getApproval(Request $request)
    {
        $user = new User;
        $approval = empty($request->approve_note) ? $user->getApproval($request->id) : $user->getEjectApproval($request->id, $request->approve_note);
        if($approval){
            $data = $user->getUser($request->id);
            Mail::to($data->email)->send(new emailApproval($data->id));
            $note = empty($request->approve_note) ? " is approved" : " is ejected";
        }else{
            $note = "Something wrong";
        }
        return ['success' => $data->name . $note];
    }

    public function getStatus(Request $request)
    {
        $user = new User;
        $data = $user->getStatus($request->id);
        $data = $user->getUser($request->id);
        $note = $data->status == 1 ? " is enabled" : " is disabled";
        return ['success' => $data->name . $note];
    }

    public function getCity(Request $request)
    {
        $term = empty($request->term['term']) ? '' : ($request->term['term']);
        $cities = new City();
        $cities = $cities->getCity($request->state, $term);

        $result = array();
        foreach ($cities as $key => $value) {
            array_push($result, ['id' => $value->id, 'text' => $value->city_name_full]);
        }

        return ['results' => $result];
    }
    
    public function getCityEdit(Request $request)
    {
        $term = empty($request->term['term']) ? '' : ($request->term['term']);
        $cities = new City();
        $cities = $cities->getCity($request->stateEdit, $term);

        $result = array();
        foreach ($cities as $key => $value) {
            array_push($result, ['id' => $value->id, 'text' => $value->city_name_full]);
        }

        return ['results' => $result];
    }
}
