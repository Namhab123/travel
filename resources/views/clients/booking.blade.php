@include('clients.blocks.header')
@include('clients.blocks.banner')

<section class="container" style="margin-top:50px; margin-bottom: 100px">
    <form action="{{ route('create-booking') }}" method="post" class="booking-container">
        @csrf
        <!-- Thông Tin Liên Lạc -->
        <div class="booking-info">
            <h2 class="booking-header">Thông Tin Liên Lạc</h2>
            <div class="booking__infor">
                <div class="form-group">
                    <label for="username">Họ và tên*</label>
                    <input type="text" id="username" placeholder="Nhập Họ và tên" name="fullName" required>
                </div>

                <div class="form-group">
                    <label for="email">Email*</label>
                    <input type="email" id="email" placeholder="sample@gmail.com" name="email" required>
                </div>

                <div class="form-group">
                    <label for="tel">Số điện thoại*</label>
                    <input type="number" id="tel" placeholder="Nhập số điện thoại liên hệ" name="tel"
                        required>
                </div>

                <div class="form-group">
                    <label for="address">Địa chỉ*</label>
                    <input type="text" id="address" placeholder="Nhập địa chỉ liên hệ" name="address" required>
                </div>
            </div>

            <!-- Hành Khách -->
            <h2 class="booking-header">Hành Khách</h2>
            <div class="booking__quantity">
                <div class="form-group quantity-selector">
                    <label>Người lớn</label>
                    <div class="input__quanlity">
                        <button type="button" class="quantity-btn">-</button>
                        <input type="number" class="quantity-input" value="1" min="1" id="numAdults"
                            name="numAdults" data-price-adults="{{ $tour->priceAdult }}">
                        <button type="button" class="quantity-btn">+</button>
                    </div>
                </div>

                <div class="form-group quantity-selector">
                    <label>Trẻ em</label>
                    <div class="input__quanlity">
                        <button type="button" class="quantity-btn">-</button>
                        <input type="number" class="quantity-input" value="0" min="0" id="numChildren"
                            name="numChildren" data-price-children="{{ $tour->priceChild }}">
                        <button type="button" class="quantity-btn">+</button>
                    </div>
                </div>
            </div>

            <!-- Điều khoản -->
            <div class="privacy-section">
                <p>Bằng cách nhấp chuột vào nút "ĐỒNG Ý", bạn đồng ý với điều khoản của Travela.</p>
                <div class="privacy-checkbox">
                    <input type="checkbox" id="agree" name="agree" required>
                    <label for="agree">Tôi đã đọc và đồng ý với <a href="#" target="_blank">Điều khoản thanh
                            toán</a></label>
                </div>
            </div>

            <!-- Phương Thức Thanh Toán -->
            <h2 class="booking-header">Phương Thức Thanh Toán</h2>

            <label class="payment-option">
                <input type="radio" name="payment" value="office-payment" required>
                <img src="{{ asset('clients/assets/images/contact/icon.png') }}" alt="Office Payment">
                Thanh toán tại văn phòng
            </label>

            <label class="payment-option">
                <input type="radio" name="payment" value="paypal-payment" required>
                <img src="{{ asset('clients/assets/images/booking/cong-thanh-toan-paypal.jpg') }}" alt="PayPal">
                Thanh toán bằng PayPal
            </label>

            <label class="payment-option">
                <input type="radio" name="payment" value="momo-payment" required>
                <img src="{{ asset('clients/assets/images/booking/thanh-toan-momo.jpg') }}" alt="MoMo">
                Thanh toán bằng Momo
                @if (!is_null($transIdMomo))
                    <input type="hidden" name="transactionIdMomo" value="{{ $transIdMomo }}">
                @endif
            </label>

            <input type="hidden" name="payment_hidden" id="payment_hidden">
        </div>

        <!-- Tổng Quan Đơn -->
        <div class="booking-summary">
            <div class="summary-section">
                <div>
                    <p>Mã tour : {{ $tour->tourId }}</p>
                    <input type="hidden" name="tourId" id="tourId" value="{{ $tour->tourId }}">
                    <h5 class="widget-title">{{ $tour->title }}</h5>
                    <p>Ngày khởi hành : {{ date('d-m-Y', strtotime($tour->startDate)) }}</p>
                    <p>Ngày kết thúc : {{ date('d-m-Y', strtotime($tour->endDate)) }}</p>
                    <p class="quantityAvailable">Số chỗ còn nhận : {{ $tour->quantity }}</p>
                </div>

                <div class="order-summary">
                    <div class="summary-item">
                        <span>Người lớn:</span>
                        <div>
                            <span class="quantity__adults">1</span>
                            <span>X</span>
                            <span class="total-price">0 VNĐ</span>
                        </div>
                    </div>
                    <div class="summary-item">
                        <span>Trẻ em:</span>
                        <div>
                            <span class="quantity__children">0</span>
                            <span>X</span>
                            <span class="total-price">0 VNĐ</span>
                        </div>
                    </div>
                    <div class="summary-item">
                        <span>Giảm giá:</span>
                        <div>
                            <span class="total-price">0 VNĐ</span>
                        </div>
                    </div>
                    <div class="summary-item total-price">
                        <span>Tổng cộng:</span>
                        <span>0 VNĐ</span>
                        <input type="hidden" class="totalPrice" name="totalPrice" value="">
                    </div>
                </div>

                <div class="order-coupon">
                    <input type="text" placeholder="Mã giảm giá" style="width: 65%;">
                    <button style="width: 30%" class="booking-btn btn-coupon">Áp dụng</button>
                </div>

                <div id="paypal-button-container"></div>

                <!-- ✅ Nút xác nhận có ID -->
                <button type="submit" id="btn-confirm" class="booking-btn btn-submit-booking">Xác Nhận</button>

                <button id="btn-momo-payment" class="booking-btn" style="display: none;"
                    data-urlmomo="{{ route('createMomoPayment') }}">
                    Thanh toán với Momo
                    <img src="{{ asset('clients/assets/images/booking/icon-thanh-toan-momo.png') }}" alt=""
                        style="width: 10%">
                </button>
            </div>
        </div>
    </form>
</section>



@include('clients.blocks.footer')
