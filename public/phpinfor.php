<!-- $(document).on("click", ".confirm-booking", function (e) {
        e.preventDefault();

        const bookingId = $(this).data("bookingid");
        const urlConfirm = $(this).data("urlconfirm");
        console.log("Booking ID:", bookingId);
        console.log("urlConfirm:", urlConfirm);

        // Thực hiện các hành động khác, ví dụ gọi AJAX
        $.ajax({
            url: urlConfirm,
            method: "POST",
            data: {
                bookingId: bookingId,
                _token: $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.success) {
                    $("#tbody-booking").html(response.data);
                    $(".confirm-booking").remove();
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function (error) {
                toastr.error("Có lỗi xảy ra. Vui lòng thử lại sau.");
            },
        });
    });

    $(document).on("click", ".finish-booking", function (e) {
        e.preventDefault();

        const bookingId = $(this).data("bookingid");
        const urlFinish = $(this).data("urlfinish");
        console.log("Booking ID:", bookingId);
        console.log("urlFinish:", urlFinish);

        // Thực hiện các hành động khác, ví dụ gọi AJAX
        $.ajax({
            url: urlFinish,
            method: "POST",
            data: {
                bookingId: bookingId,
                _token: $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.success) {
                    $("#tbody-booking").html(response.data);
                    $(".finish-booking").remove();
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function (error) {
                toastr.error("Có lỗi xảy ra. Vui lòng thử lại sau.");
            },
        });
    });
 -->