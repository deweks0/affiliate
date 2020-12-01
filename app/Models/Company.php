<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

class Company extends Model
{
    use HasFactory;

    protected $guarded = [];
    // protected $guard = 'company';

    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function settings()
    {
        return $this->hasMany(Setting::class);
    }
    public function roles()
    {
        return $this->hasMany(Role::class);
    }

    // custom method
    public function addCompany( $data)
    {
        $company =Company::create([
            'name'=>$data['company']
        ]);
        $user = User::create([
            'name'=>$data['name'],
            'email'=>$data['email'],
            'phone'=>$data['phone'],
            'password'=>Hash::make($data['password']),
            'address'=>'indonesia',
            'role'=>'2',
            'company_id'=>$company->id
        ]);
        $adminCompany = Role::create(['name'=>'admin-'.$data['company'],'company_id'=>$company->id]);
        // $adminCompany = Role::create(['name'=>'admin-'.$data['company'],'company_id'=>$company->id]);
        $resellerCompany = Role::create(['name'=>'reseller-'.$data['company'],'company_id'=>$company->id]);
        $permissionForAdmin = Role::where('name','copy-admin')->get()->first();
        $adminCompany->syncPermissions($permissionForAdmin->getAllPermissions());
        $user->assignRole('admin',$adminCompany->name);
        
        return $user;
    }

    public function editCompany($company,$data)
    {
        $company->update([
            'name'=>$data->company
        ]);
        $user = User::where('company_id',$company->id)->get()->first();
        if($user->hasRole('admin')){
            $user->update([
                'name'=>$data->name,   
                'email'=>$data->email,   
                'phone'=>$data->phone,
            ]);
        }
    }

}
