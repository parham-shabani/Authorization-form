<?php

namespace App\Http\Controllers;

use Dflydev\DotAccessData\Data;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class RestoController extends Controller
{

    function registerUser(Request $req){
        $validateData = $req->validate([
            'name' => 'required|regex:/^[a-z A-Z]+$/u',
            'email' => 'required|email',
            'password' => 'required|min:6|max:12',
            'confirm_password' => 'required|same:password',
            'mobile' => 'numeric|required|digits:11'
        ]);

        $result = DB::table('users')
            ->where('email',$req->input('email'))
            ->get();

        $res = json_decode($result,true);
        print_r($res);

        if(sizeof($res)==0){
            $data = $req->input();
            $user = new User();
            $user->name = $data['name'];
            $user->email = $data['email'];
            $encrypted_password = crypt::encrypt($data['password']);
            $user->password = $encrypted_password;
            $user->mobile = $data['mobile'];
            $user->save();
            $req->session()->flash('register_status','User has been registered successfully');
            return redirect('/register');
        }
        else{
            $req->session()->flash('register_status','This Email already exists.');
            return redirect('/register');
        }
    }

    function login(Request $req){
        $validatedData = $req->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $result = DB::table('users')
            ->where('email',$req->input('email'))
            ->get();

        $res = json_decode($result,true);
        print_r($res);

        if(sizeof($res)==0){
            $req->session()->flash('error','Email Id does not exist. Please register yourself first');
            echo "Email Id Does not Exist.";
            return redirect('login');
        }
        else{
            $encrypted_password = $result[0]->password;
            $decrypted_password = crypt::decrypt($encrypted_password);
            if($decrypted_password==$req->input('password')){
                echo "You are logged in Successfully";
                $req->session()->put('user',$result[0]->name);
                return redirect('/');
            }
            else{
                $req->session()->flash('error','Password Incorrect!!!');
                //echo "Email Id Does not Exist.";
                return redirect('login');
            }
        }
    }
}
