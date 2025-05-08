<?php

namespace App\Http\Controllers\clients;

use App\Http\Controllers\Controller;
use App\Models\clients\User;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    private $user;

    public function __construct()
    {
        $this->user = new User();
    }

    protected function getUserId()
    {
        if (!session()->has('userId')) {
            $username = session()->get('username');
            if ($username) {
                $userId = $this->user->getUserId($username);
                session()->put('userId', $userId); //lưu userId vào session để dùng lại

            }
        }
        return session()->get('userId');
    }
    public function index()
    {
        $title = 'Thông tin cá nhân';
        $userId = $this->getUserId();
        $user = $this->user->getUser($userId);
        //dd($userId);

        return view('clients.user-profile', compact('title', 'user'));
    }


    public function update(Request $req)
    {
        $fullName = $req->fullName;
        $address = $req->address;
        $email = $req->email;
        $phoneNumber = $req->phoneNumber;

        $dataUpdate = [
            'fullName' => $fullName,
            'address' => $address,
            'email' => $email,
            'phoneNumber' => $phoneNumber,
        ];

        $userId = $this->getUserId();

        $update = $this->user->updateUser($userId, $dataUpdate);
        if (!$update) {
            return response()->json([
                'fail' => false
            ]);
        }
        return response()->json([
            'success' => true,
        ]);
    }

    public function ChangePassword(Request $req)
    {
        $oldPass     = md5($req->oldPass);
        $newPass     = md5($req->newPass);
        $confirmPass = md5($req->confirmPass);

        $userId = $this->getUserId();
        $user   = $this->user->getUser($userId);

        // Kiểm tra mật khẩu cũ có đúng không
        if ($oldPass != $user->password) {
            return response()->json(['success' => false, 'message' => 'Mật khẩu cũ không đúng!']);
        }

        // Kiểm tra mật khẩu mới có trùng mật khẩu cũ không
        if ($newPass == $oldPass) {
            return response()->json(['success' => false, 'message' => 'Mật khẩu mới không được trùng với mật khẩu cũ!']);
        }

        // Kiểm tra mật khẩu mới với confirm
        if ($newPass != $confirmPass) {
            return response()->json(['success' => false, 'message' => 'Mật khẩu mới và xác nhận không khớp!']);
        }

        // Nếu pass mới ok thì update
        $dataPass = [
            'password' => $newPass
        ];

        $update = $this->user->updateUser($userId, $dataPass);

        if ($update === false) {
            return response()->json(['success' => false, 'message' => 'Cập nhật thất bại!']);
        }

        return response()->json(['success' => true, 'message' => 'Đổi mật khẩu thành công!']);
    }

    public function changeAvatar(Request $req){

        $req->validate([
            'avatar' => 'required|image|mimes:jpeg,jpg,png,gif|max:5120'
        ]);
        //Lấy tệp ảnh
        $avatar =$req->file('avatar');

        //Tạo tên tệp mới cho ảnh
        $filename = time(). '.' . $avatar->getClientOriginalExtension();//Tên tệp mới theo thời gian
        $userId = $this->getUserId();
        $user =$this->user->getUser($userId);
        if($user->avatar){
            //Đường dẫn ảnh cũ
            $oldAvatarPath = public_path('clients/assets/images/user_profile/'. $user ->avatar);

            //Kiểm tra tệp cũ có tồn tại, xóa nếu có
            if(file_exists($oldAvatarPath)){
                unlink($oldAvatarPath);
            }
        }

        //Di chuyển ảnh vào thư mục public/clients/assets/images/user_profile/
        $avatar->move(public_path('clients/assets/images/user_profile'), $filename);
        $update = $this->user->updateUser($userId, ['avatar'=>$filename]);

        if(!$update){
            return response()->json(['fail'=> true, 'message' => 'Xảy ra lỗi. Không thể cập nhật ảnh!']);
        }
        return response()->json(['success'=> true, 'message' => 'Cập nhật ảnh thành công']);


        return;
    }
}
