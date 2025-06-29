<?php

namespace App\Http\Controllers\clients;

use App\Http\Controllers\Controller;
use App\Models\clients\Login;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;


class LoginGoogleController extends Controller
{

    protected $user;

    public function __construct()
    {
        $this->user = new Login();
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

public function handleGoogleCallback()
{
    try {
        $user = Socialite::driver('google')->stateless()->user();
        $finduser = $this->user->checkUserExistGoogle($user->id);

        if ($finduser) {
            // Kiểm tra trạng thái kích hoạt (nếu đã áp dụng yêu cầu xác nhận email)
            if ($finduser->isActive !== 'y') {
                return redirect('/login')->with('error', 'Tài khoản chưa được kích hoạt. Vui lòng kiểm tra email!');
            }

            // Lưu session
            session()->put('username', $finduser->username);
            session()->put('avatar', $finduser->avatar ?? null);
            session()->put('userId', $finduser->userId);

            // Thêm thông báo thành công vào session
            toastr()->success('Đăng nhập thành công!', 'Thông báo');

            return redirect()->intended('/');
        } else {
            // Tạo tài khoản mới (nếu không áp dụng xác nhận email)
            $activation_token = \Illuminate\Support\Str::random(60);
            $data_google = [
                'google_id' => $user->id,
                'fullName' => $user->name,
                'username' => 'user-google' . time(),
                'password' => bcrypt('12345678'),
                'email' => $user->email,
                'activation_token' => $activation_token,
                'isActive' => 'y' // Tạm thời kích hoạt ngay nếu không dùng xác nhận email
            ];

            $newUser = $this->user->registerAccount($data_google);

            if ($newUser && isset($newUser->username)) {
                session()->put('username', $newUser->username);
                toastr()->success('Đăng nhập thành công! Tài khoản mới đã được tạo.', 'Thông báo');
                return redirect()->intended('/');
            } else {
                return redirect('/login')->with('error', 'Có lỗi xảy ra trong quá trình đăng ký!');
            }
        }
    } catch (\Exception $e) {
        Log::error('Google login error: ' . $e->getMessage());
        return redirect('/login')->with('error', 'Có lỗi xảy ra khi đăng nhập bằng Google!');
    }
}
}
