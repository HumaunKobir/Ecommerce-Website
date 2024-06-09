<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Validator;
use Auth;
use Hash;
use Image;

class AdminController extends Controller
{
    public function dashboard(){
        return view('admin.dashboard');
    }
    public function login(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            $rules = [
                'email' => 'required|email|max:255',
                'password' => 'required|max:30',
            ];
            $customMessage = [
                'email.required' => 'Email is Required',
                'email.email' => 'Valid Email is Required',
                'password.required' => 'Password is Required',
            ];
            $this->validate($request,$rules,$customMessage);
            if(Auth::guard('admin')->attempt(['email'=>$data['email'],'password'=>$data['password']])){
                return redirect('admin/dashboard');
            }else{
                return redirect()->back()->with('error_message','Invalid Email or Password!');
            }
        }
        return view('admin.login');
    }
    public function logout(){
        Auth::guard('admin')->logout();
        return redirect('admin/login');
    }
    //Update Admin Password
    public function updateAdminPassword(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            //Check current Password
            if(Hash::check($data['current_password'],Auth::guard('admin')->user()->password)){
                //Check if new Password is matching with Comfirm password
                if($data['confirm_password']==$data['new_password']){
                    Admin::where('id',Auth::guard('admin')->user()->id)->update(['password'=>bcrypt($data['new_password'])]);
                    return redirect()->back()->with('success_message','Password Has Been Upadate Successfully!');
                }else{
                    return redirect()->back()->with('error_message','New Password is not match With Confirm Password!');
                }

            }else{
                return redirect()->back()->with('error_message','Current Password is not match!');
            }
        }
        $adminDetails=Admin::where('email',Auth::guard('admin')->user()->email)->first()->toArray();
        return view('admin.settings.update_password')->with(compact('adminDetails')); 
    }
    //Check Current Password
    public function checkCurrentPassword(Request $request){
        $data = $request->all();
        //Check current Password
        if(Hash::check($data['current_password'],Auth::guard('admin')->user()->password)){
            return "true";
        }else{
            return "false";
        }
    }
    //Update Admin Details
    public function updateAdminDetails(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            $rules = [
                'name' => 'required|max:255',
                'mobile' => 'required|numeric',
                'image' => 'required',
            ];
            $customMessage = [
                'name.required' => 'Name is Required',
                'mobile.required' => 'Mobile number is Required',
                'mobile.numeric' => 'Valid Mobile number is Required',
                'image.required' => 'Image is Required',
            ];
            $this->validate($request,$rules,$customMessage);
            //upload image
            if($request->hasFile('image')){
                $image_tmp = $request->file('image');
                if($image_tmp->isValid()){
                    $extension = $image_tmp->getClientOriginalExtension();
                    $image_name = time().'-'.$request->name .'.'.$extension;
                    $request->image->move(public_path('admin/images/photos'),$image_name); 
                }else if(!empty($data['current_image'])){
                    $image_name = $data['current_image'];
                }else{
                    $image_name = "";
                }
            }
            //Update Admin Details
            Admin::where('email',Auth::guard('admin')->user()->email)->update(['name'=>$data['name'],'mobile'=>$data['mobile'],'image'=>$image_name]);
            return redirect()->back()->with('success_message','Admin Details Has Been Upadated Successfully!');
        }
        return view('admin.settings.update_admin_details');
    }
}
