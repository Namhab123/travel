/* @global $ */

/* ==========================================================================
                                PAGE LOGIN
    ========================================================================== */

$(document).ready(function () {
    //handle click switch tab
    $("#sign-up").click(function () {
        $(".sign-in").hide();
        $(".signup").show();

    });
    $("#sign-in").click(function () {
        $(".signup").hide();
        $(".sign-in").show();

    });

    //handle form signin
    $("#login-form").on("submit", function (e) {
        e.preventDefault();
        var username = $("#username_login").val().trim();
        var password = $("#password_login").val().trim();

        // Ẩn lỗi ban đầu
        $("#validate_username").hide().text("");
        $("#validate_password").hide().text("");

        var isValid = true;

        // Kiểm tra độ dài mật khẩu
        if (password.length < 6) {
            isValid = false;
            $("#validate_password")
                .show()
                .text("Mật khẩu phải có ít nhất 6 ký tự!");
        }

        // Kiểm tra ký tự đặc biệt
        var sqlInjectionPattern = /[<'"%;()%+>]/;
        if (sqlInjectionPattern.test(username)) {
            isValid = false;
            $("#validate_username")
                .show()
                .text("Tên đăng nhập không được chứa ký tự đặc biệt!");
        }

        if (sqlInjectionPattern.test(password)) {
            isValid = false;
            $("#validate_password")
                .show()
                .text("Mật khẩu không được chứa ký tự đặc biệt!");
        }

        if (isValid) {
            var formData = {
                'username': username,
                'password': password,
                '_token': $('input[name="_token"]').val(),
            };
            console.log(formData);
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: formData,
                success: function (response) {
                    if (response.success) {
                        window.location.href = '/';
                    } else {
                        $('#error_login').text(response.message).show();
                    }
                },
                error: function (xhr, textStatus, errorThown) {
                    alert('Có lỗi xảy ra!');
                },
            });

        }
    });

    $('#message').hide();
    $('#error').hide();
    $('#error_login').hide()

    //handle form signup
    $("#register-form").on("submit", function (e) {
        e.preventDefault();

        //Lấy giá trị của các trường nhập dữ liệu
        var username = $("#username_register").val().trim();
        var email = $("#email_register").val().trim();
        var password = $("#password_register").val().trim();
        var rePass = $("#re_pass").val().trim();

        //Đặt lại nội dung thông báo lỗi và ẩn chúng
        $("#validate_username_regis").hide().text("");
        $("#validate_email_regis").hide().text("");
        $("#validate_password_regis").hide().text("");
        $("#validate_repass").hide().text("");


        //Kiểm tra lỗi
        var isValid = true;

        //Kiểm tra tên đăng nhập không chứa kí tự SQL injection
        var sqlInjectionPattern = /[<'"%;()%+>]/;
        if (sqlInjectionPattern.test(username)) {
            isValid = false;
            $("#validate_username_regis")
                .show()
                .text("Tên tài khoản không được chứa ký tự đặc biệt!");
        }

        var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email)) {
            isValid = false;
            $("#validate_email_regis").show().text("Email không hợp lệ. Vui lòng nhập lại!");
        }

        //Kiểm tra độ dài mật khẩu
        if (password.length < 6) {
            isValid = false;
            $("#validate_password_regis")
                .show()
                .text("Mật khẩu phải có ít nhất 6 ký tự!");
        }

        if (sqlInjectionPattern.test(password)) {
            isValid = false;
            $("#validate_password_regis")
                .show()
                .text("Mật khẩu không được chứa ký tự đặc biệt!");
        }

        //Kiểm tra nhập lại mạt khẩu
        if (password !== rePass) {
            isValid = false;
            $("#validate_repass").show().text("Mật khẩu nhập lại không khớp. Vui lòng nhập lại!")
        }

        if (isValid) {
            var formData = {
                'username_regis': username,
                'email_regis': email,
                'password_regis': password,
                '_token': $('input[name="_token"]').val()
            };
            console.log(formData);
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: formData,
                success: function (response) {
                    if (response.success) {
                        $('#message').text(response.message).show();
                        $('#error').hide();
                        //reser form
                        $('#register-form').trigger('reset');

                    } else {
                        $('#message').hide();
                        $('#error').text(response.message).show();
                    }
                },
                error: function (xhr, textStatus, errorThown) {
                    alert('Có lỗi xảy ra!');
                },
            });

        }
    })


    /* ==========================================================================
                                HOME  PAGE
    ========================================================================== */
    //Homepage
    $("#start_date, #end_date").datetimepicker({
        format: "d/m/Y", //định dang ngày(dd/mm/yyyy)
        timepicker: false,//tắt chức năng chọn giờ
    });

    /* ==========================================================================
                                HEADER
    ========================================================================== */
    //header icon login
    $("#userDropdown").click(function () {
        $("#dropdownMenu").toggle();
    })

    /* ==========================================================================
                                PAGE TOURS
    ========================================================================== */

    //Kiểm tra nếu thanh trượt đã tồn tại 
    if ($(".price-slider-range").length) {
        $(".price-slider-range").on("slide", function (event, ui) {
            filterTours(ui.values[0], ui.values[1]);
        })
    }
    $('#price').on('change', filterTours);
    $('input[name="domain"]').on('change', filterTours);
    $('input[name="filter_star"]').on('change', filterTours);
    $('input[name="duration"]').on('change', filterTours);

    $('#sorting_tours').on("change", function () {
        filterTours(null, null);
    });

    function filterTours(minPrice = null, maxPrice = null) {
        $(".loader").show();
        $("#tours-container").addClass("hidden-content");

        if (minPrice === null || maxPrice === null) {
            minPrice = $(".price-slider-range").slider("values", 0);
            maxPrice = $(".price-slider-range").slider("values", 1);
        }

        var domain = $('input[name="domain"]:checked').val();
        var star = $('input[name="filter_star"]:checked').val();
        var duration = $('input[name="duration"]:checked').val();
        var sorting = $('#sorting_tours').val();

        formDataFilter = {
            minPrice: minPrice,
            maxPrice: maxPrice,
            domain: domain,
            star: star,
            time: duration,
            sorting: sorting
        };

        $.ajax({
            url: filterToursUrl,
            method: 'GET',
            data: formDataFilter,
            success: function (res) {
                $('#tours-container').html(res).removeClass("hidden-content");
                $('#tours-container .destination-item').addClass("aos-animate");
                $(".loader").hide();
            },
        });
    }

    // Hàm để clear các filter đã chọn
    $(".clear-filter").on("click", function (e) {
        e.preventDefault();

        // Reset slider giá về giá trị mặc định (ví dụ: 0 đến 10 triệu)
        $(".price-slider-range").slider("values", [0, 10000000]);

        // Bỏ chọn các radio và checkbox nếu đang được chọn
        $("input[name='domain']").prop("checked", false);
        $("input[name='filter_start']").prop("checked", false);
        $("input[name='duration']").prop("checked", false);

        // Gọi lại hàm filterTours để áp dụng bộ lọc mới (sau khi reset)
        filterTours(0, 10000000);
    });



    /* ==========================================================================
                                PAGE USER-PROFILE
    ========================================================================== */

    $('.updateUser').on('submit', function (e) {
        e.preventDefault();
        var fullName = $('#inputFullName').val();
        var address = $('#inputLocation').val();
        var email = $('#inputEmailAddress').val();
        var phoneNumber = $('#inputPhone').val();

        var dataUpdate = {
            'fullName': fullName,
            'address': address,
            'email': email,
            'phoneNumber': phoneNumber,
            '_token': $('input[name="_token"]').val()        }
        console.log(dataUpdate);
         
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: dataUpdate,
            success: function (response) {
                alert('Cập nhật thông tin thành công!');
                console.log(response);
            },
            error: function (xhr, textStatus, errorThown) {
                alert('Có lỗi xảy ra!');
            },
        });

    });

    $('#update_passwword_profile').click(function () {
        $("#card_change_password").toggle().fadeIn();
    });


    $('.change_password_profile').on('submit', function (e) {
        e.preventDefault();
        var oldPass = $('#inpuOldPass').val();
        var newPass = $('#inputNewPass').val();
        var confirmPass = $('#inputConfirmPass').val();
        var isValid = true;
        var sqlInjectionPattern = /[<'"%;()%+>]/;


        //Kiểm tra độ dài mật khẩu
        if (oldPass.length < 6 || newPass.length < 6) {
            isValid = false;
            $("#validate_password")
                .show()
                .text("Mật khẩu phải có ít nhất 6 ký tự!");
        }

        if (sqlInjectionPattern.test(oldPass) || sqlInjectionPattern.test(newPass)) {
            isValid = false;
            $("#validate_password")
                .show()
                .text("Mật khẩu không được chứa ký tự đặc biệt!");
        }

        //Kiểm tra nhập lại mật khẩu
        if (newPass !== confirmPass) {
            isValid = false;
            $("#validate_password").show().text("Mật khẩu mới không khớp!")
        }
         
        if(isValid){
            $("#validate_password").hide().text("Đổi mật khẩu thành công");

            var updatePass = {
                'oldPass': oldPass,
                'newPass': newPass,
                'confirmPass': confirmPass,
                '_token': $('input[name="_token"]').val()        
            }
        console.log(updatePass);

            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: updatePass,
                success: function (response) {
                    if(response.success){
                        $("#validate_password").hide().text("Đổi mật khẩu thành công");
                        alert('Đổi mật khẩu thành công!');
                    } else{
                        alert('Có lỗi xảy ra!');
                    }
                    console.log(response);
                },
                error: function (xhr, textStatus, errorThown) {
                    $("#validate_password")
                        .show()
                        .text( xhr.responseJSON.message);
                },
            });
        } 

    });


    //Update avatar
    $('#avatar').on('change', function(){
        const file = event.target.files[0];

        
         if (file) {
            // Hiển thị ảnh preview
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#avatarPreview').attr('src', e.target.result);
            };
            reader.readAsDataURL(file);
            var __token = $(this).closest(".card-body").find('input.__token').val();
            var url =$(this).closest(".card-body").find('input.label_avatar').val();;

            // Tạo FormData để gửi qua AJAX
            const formData = new FormData();
            formData.append('avatar', file);

            console.log(formData.get('avatar'));
            

            // Gửi AJAX đến server
             $.ajax({
                url: url,           // Đường dẫn API
                type: 'POST',                    // Phương thức POST
                headers:{
                    'X-CSRF-TOKEN': __token
                },
                data: formData,                  // Dữ liệu là file avatar
                contentType: false,              // Không thiết lập kiểu dữ liệu (do dùng FormData)
                processData: false,              // Không xử lý dữ liệu (để nguyên dạng FormData)
                success: function(data) {
                    if (data.success) {
                        alert('Cập nhật ảnh thành công!');
                    } else {
                        alert('Có lỗi xảy ra, vui lòng thử lại!');
                    }
                },
                error: function(xhr, status, error){
                    console.error('Error', error);
                    alert('Có lỗi xảy ra, vui lòng thử lại!');
                }
            }); 
        } 
        
    });
});



