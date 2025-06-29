<?php

namespace App\Http\Controllers\clients;

use App\Models\clients\Login;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class LoginFacebookController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this-> user = new Login();
    }
    public function redirectToFacebook()

    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        try {

            $user = Socialite::driver('facebook')->stateless()->user();
            
             $finduser = $this->user->checkUserExistFacebook($user->id); //Kieemr tra đã có id người dùng với email chưa
/*              dd($finduser);
 */            if ($finduser) {
                    session()->put('username', $finduser->username);
                return redirect()->intended('/');
            } else {

                $data_facebook = [
                    'facebook_id' => $user->id,
                    'fullName'=>$user->name,
                    'username'=>'user-google',
                    'password'=>md5('12345678'),
                    'email'=>$user->email,
                    'isActive'=>'y'
                ];
                $newUser = $this->user->registerAccount($data_facebook);
                //Kiểm tra xem newUser có hợp lệ không
                if($newUser && isset ($newUser->username)){
                    //Luu thông tin người dùng mới vào session
                    session()->put('username',$newUser->username);
                    return redirect()->intended('/');
                }else{
                    //Nếu có lỗi khi đăng ký người dùng mới, xử lý lỗi
                    return redirect()->back()->with('error', 'Có lỗi xẩy ra trong quá trình đăng ký người dùng mới');
                }
            }
        } catch (Exception $e) {

            dd($e->getMessage());
        }
    }
}
