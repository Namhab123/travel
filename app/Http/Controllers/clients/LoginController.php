<?php

namespace App\Http\Controllers\clients;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\clients\Login;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


class LoginController extends Controller
{
    private $login;

    public function __construct()
    {
        $this->login = new Login();
    }
    public function index()
    {
        $title = 'Đăng nhập';
        return view('clients.login', compact('title'));
    }

    public function register(Request $request)
    {
        $username_regis = $request->username_regis;
        $email = $request->email_regis;
        $password_regis = $request->password_regis;

        $checkAccountExist = $this->login->checkUserExist($username_regis, $email);

        if ($checkAccountExist) {
            return response()->json([
                'success' => false,
                'message' => 'Tên người dùng hoặc email đã tồn tại!'
            ]);
        }
        $activation_token = Str::random(60);//tạo token ngẫu nhiên
        //Nếu không tồn tại thực hiện đăng ký
        $dataInsert = [
            'username' => $username_regis,
            'email' => $email,
            'password' => md5($password_regis),
            'activation_token' => $activation_token
        ];

        $this->login->registerAccount($dataInsert);

        //Gửi mail kich hoạt
        $this->sendActivationEmail($email, $activation_token);
        return response()->json([
            'success' => true,
            'message' => 'Đăng ký thành công! Vui lòng kiểm tra email để kích hoạt tài khoản.'
        ]);
    }

    public function sendActivationEmail($email, $token){

        $activation_link = route('activate.account',['token'=>$token]);

        Mail::send('clients.email.emails_activation', ['link'=>$activation_link], function($message) use($email){
            $message->to($email);
            $message->subject('Kích hoạt tài khoản của bạn.');
        });
    }

    public function activateAccount($token){

        $user = $this->login->getUserByToken($token);

        if($user)
        {
            $this->login->activateAccount($token);

            return redirect('/login')->with('message','Tài khoản của bạn đã được kích hoạt!');
        }else{
            return redirect('/login')->with('error','Mã kích hoạt không hơp lệ !');
        }
    }

    //XỬ lý người dùng đăng nhập
    public function login(Request $request){

        $username = $request->username;
        $password= $request->password;

        $data_login = [
            'username'=>$username,
            'password'=>md5($password)
        ];

        $user = $this->login-> login($data_login);
        // dd($user);
        if($user != null){
            $request->session()->put('username', $username);
            return response()->json([
                'success'=>true,
                'message'=>'Đăng nhập thành công!',
                'redirectUrl'=>route('home'),
            ]);
        }else{
            return response()->json([
                'success'=>false,
                'message'=>'Thông tin tài khoản không chính xác. Bạn cần nhập lại!',
            ]);
        }
    }

    //Xử lý người dùng đăng xuất
    public function logout(Request $request){
        //Xóa session lưu trữ thông tin người dùng đã đăng nhập
        $request->session()->forget('username');
        return redirect()->route('home');
    }
}
