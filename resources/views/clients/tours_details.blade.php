@include('clients.blocks.header')
<section class="page-banner-two rel z-1">
    <div class="container-fluid">
        <hr class="mt-0">
        <div class="container">
            <div class="banner-inner pt-15 pb-25">
                <h2 class="page-title mb-10" data-aos="fade-left" data-aos-duration="1500" data-aos-offset="50">
                    {{ $tourDetail->destination }}</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center mb-20" data-aos="fade-right" data-aos-delay="200"
                        data-aos-duration="1500" data-aos-offset="50">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>
<!-- Tour Gallery start -->
<div class="tour-gallery">
    <div class="container-fluid">
        <div class="row gap-10 justify-content-center rel">
            <div class="col-lg-4 col-md-6">
                <div class="gallery-item">
                    <img src="{{ asset('admin/assets/images/gallery-tours/' . $tourDetail->images[0] . '') }}"
                        alt="Destination" class="tour-image">
                </div>
                <div class="gallery-item">
                    <img src="{{ asset('admin/assets/images/gallery-tours/' . $tourDetail->images[1] . '') }}"
                        alt="Destination" class="tour-image">
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="gallery-item gallery-between">
                    <img src="{{ asset('admin/assets/images/gallery-tours/' . $tourDetail->images[2] . '') }}"
                        alt="Destination" class="tour-image">
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="gallery-item">
                    <img src="{{ asset('admin/assets/images/gallery-tours/' . $tourDetail->images[3] . '') }}"
                        alt="Destination" class="tour-image">
                </div>
                <div class="gallery-item">
                    <img src="{{ asset('admin/assets/images/gallery-tours/' . $tourDetail->images[4] . '') }}"
                        alt="Destination" class="tour-image">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Tour Gallery End -->



<!-- Tour Header Area start -->
<section class="tour-header-area pt-70 rel z-1">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-xl-6 col-lg-7">
                <div class="tour-header-content mb-15" data-aos="fade-left" data-aos-duration="1500"
                    data-aos-offset="50">
                    <span class="location d-inline-block mb-10"><i class="fal fa-map-marker-alt"></i>
                        {{ $tourDetail->destination }}</span>
                    <div class="section-title pb-5">
                        <h2>{{ $tourDetail->title }}</h2>
                    </div>
                    <div class="ratting">
                        @for ($i = 0; $i < 5; $i++)
                            @if ($avgStar && $i < $avgStar)
                                <i class="fas fa-star"></i>
                            @else
                                <i class="far fa-star"></i>
                            @endif
                        @endfor

                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-5 text-lg-end" data-aos="fade-right" data-aos-duration="1500"
                data-aos-offset="50">
                <div class="tour-header-social mb-10">
                    <a href="#"><i class="far fa-share-alt"></i>Share tours</a>
                    <a href="#"><i class="fas fa-heart bgc-secondary"></i>Wish list</a>
                </div>
            </div>
        </div>
        <hr class="mt-50 mb-70">
    </div>
</section>
<!-- Tour Header Area end -->


<!-- Tour Details Area start -->
<section class="tours_details-page pb-100">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="tours_details-content">
                    <h3>Khám phá Tour</h3>
                    <p>{!! $tourDetail->description !!}</p>
                    <div class="row pb-55">
                        {{-- <div class="col-md-6">
                            <div class="tour-include-exclude mt-30">
                                <h5>Bao gồm những dịch vụ</h5>
                                <ul class="list-style-one check mt-25">
                                    <li><i class="far fa-check"></i> Dịch vụ đón và trả khách</li>
                                    <li><i class="far fa-check"></i> 1 Bữa Ăn Mỗi Ngày</li>
                                    <li><i class="far fa-check"></i> Bữa tối trên du thuyền và sự kiện âm nhạc</li>
                                    <li><i class="far fa-check"></i> Ghé thăm 7 địa điểm tuyệt vời nhất trong thành phố
                                    </li>
                                    <li><i class="far fa-check"></i> Nước đóng chai trên xe buýt</li>
                                    <li><i class="far fa-check"></i> Xe buýt du lịch sang trọng</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="tour-include-exclude mt-30">
                                <h5>Không bao gồm </h5>
                                <ul class="list-style-one mt-25">
                                    <li><i class="far fa-times"></i> Tiền thưởng</li>
                                    <li><i class="far fa-times"></i> Đón và trả khách tại khách sạn</li>
                                    <li><i class="far fa-times"></i> Bữa trưa, Thức ăn & Đồ uống</li>
                                    <li><i class="far fa-times"></i> Tùy chọn nâng cấp lên kính</li>
                                    <li><i class="far fa-times"></i> Dịch vụ bổ sung</li>
                                    <li><i class="far fa-times"></i> Bảo hiểm</li>
                                </ul>
                            </div>
                        </div> --}}
                    </div>
                </div> 

                <h3>Lịch trình</h3>
                <div class="accordion-two mt-25 mb-60" id="faq-accordion-two">
                    @php
                        $day = 1;
                    @endphp
                    @foreach ($tourDetail->timeline as $timeline)
                        <div class="accordion-item">
                            <h5 class="accordion-header">
                                <button class="accordion-button collapsed" data-bs-toggle="collapse"
                                    data-bs-target="#collapseTwo{{ $timeline->timeLineId }}">
                                    Ngày {{ $day++ }} - {{ $timeline->title }}
                                </button>
                            </h5>
                            <div id="collapseTwo{{ $timeline->timeLineId }}" class="accordion-collapse collapse"
                                data-bs-parent="#faq-accordion-two">
                                <div class="accordion-body">
                                    <p>{!! $timeline->description !!}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <h3>Quy định</h3>
                <p>{!! $tourDetail->regulatery !!}</p>

                <div id="partials_reviews">
                    @include('clients.partials.reviews')
                </div>

                <h3 class="{{ $checkDisplay }}">Thêm Đánh giá</h3>
                <form id="comment-form" class="comment-form bgc-lighter z-1 rel mt-30 {{ $checkDisplay }}"
                    name="review-form" action="{{ route('reviews') }}" method="post" data-aos="fade-up"
                    data-aos-duration="1500" data-aos-offset="50">
                    @csrf
                    <div class="comment-review-wrap">
                        <div class="comment-ratting-item">
                            <span class="title">Đánh giá</span>
                            <div class="ratting" id="rating-stars">
                                <i class="far fa-star" data-value="1"></i>
                                <i class="far fa-star" data-value="2"></i>
                                <i class="far fa-star" data-value="3"></i>
                                <i class="far fa-star" data-value="4"></i>
                                <i class="far fa-star" data-value="5"></i>
                            </div>
                        </div>

                    </div>
                    <hr class="mt-30 mb-40">
                    <h5>Để lại phản hồi</h5>
                    <div class="row gap-20 mt-20">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="message">Nội dung</label>
                                <textarea name="message" id="message" class="form-control" rows="5" required=""></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-0">
                                <button type="submit" class="theme-btn bgc-secondary style-two" id="submit-reviews"
                                    data-url-checkBooking="{{ route('checkBooking') }}"
                                    data-tourId-reviews="{{ $tourDetail->tourId }}">
                                    <span data-hover="Gửi đánh giá">Gửi đánh giá</span>
                                    <i class="fal fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
            <div class="col-lg-4 col-md-8 col-sm-10 rmt-75">
                <div class="blog-sidebar tour-sidebar">

                    <div class="widget widget-booking" data-aos="fade-up" data-aos-duration="1500"
                        data-aos-offset="50">
                        <h5 class="widget-title">Tour Booking</h5>
                        <form action="{{ route('booking', ['id' => $tourDetail->tourId]) }}" method="POST">
                            @csrf
                            <div class="date mb-25">
                                <b>Ngày bắt đầu</b>
                                <input type="text" value="{{ date('d-m-Y', strtotime($tourDetail->startDate)) }}"
                                    name="startdate" disabled>
                            </div>
                            <hr>
                            <div class="date mb-25">
                                <b>Ngày kết thúc</b>
                                <input type="text" value="{{ date('d-m-Y', strtotime($tourDetail->endDate)) }}"
                                    name="enddate" disabled>
                            </div>
                            <hr>
                            <div class="time py-5">
                                <b>Thời gian :</b>
                                <p>{{ $tourDetail->time }}</p>
                                <input type="hidden" name="time">
                            </div>
                            <hr class="mb-25">
                            <h6>Vé:</h6>
                            <ul class="tickets clearfix">
                                <li>
                                    Người lớn <span
                                        class="price">{{ number_format($tourDetail->priceAdult, 0, ',', '.') }}VND</span>
                                </li>
                                <li>
                                    Trẻ em(dưới 11 tuổi) <span
                                        class="price">{{ number_format($tourDetail->priceChild, 0, ',', '.') }}VND</span>
                                </li>
                            </ul>
                            <button type="submit" class="theme-btn style-two w-100 mt-15 mb-5">
                                <span data-hover="Đặt ngay">Đặt ngay</span>
                                <i class="fal fa-arrow-right"></i>
                            </button>
                            <div class="text-center">
                                <a href="{{ route('contact') }}">Bạn cần sự giúp đỡ?</a>
                            </div>
                        </form>
                    </div>

                    <div class="widget widget-contact" data-aos="fade-up" data-aos-duration="1500"
                        data-aos-offset="50">
                        <h5 class="widget-title">Cần trợ giúp?</h5>
                        <ul class="list-style-one">
                            <li><i class="far fa-envelope"></i> <a href="emilto:manh8h@gmail.com">manh8h@gmail.com</a>
                            </li>
                            <li><i class="far fa-phone-volume"></i> <a href="callto:+000(123)45688">+000 (123) 456
                                    88</a></li>
                        </ul>
                    </div>
                    @if (!empty($tourRecommendations))
                        <div class="widget widget-tour" data-aos="fade-up" data-aos-duration="1500"
                            data-aos-offset="50">
                            <h6 class="widget-title">Tours Tương Tự</h6>
                            @foreach ($tourRecommendations as $tour)
                                <div class="destination-item tour-grid style-three bgc-lighter">
                                    <div class="image">
                                        {{-- <span class="badge">10% Off</span> --}}
@if (!empty($tour->images) && isset($tour->images[0]))
    <img src="{{ asset('admin/assets/images/gallery-tours/' . $tour->images[0]) }}" alt="Destination">
@else
    <img src="{{ asset('admin/assets/images/gallery-tours/default.jpg') }}" alt="Default Image">
@endif
                                            alt="Tour" style="max-height: 137px">
                                    </div>
                                    <div class="content">
                                        <div class="destination-header">
                                            <span class="location"><i class="fal fa-map-marker-alt"></i>
                                                {{ $tour->destination }}</span>
                                            <div class="ratting">
                                                <i class="fas fa-star"></i>
                                                <span>({{ $tour->rating }})</span>
                                            </div>
                                        </div>
                                        <h6><a
                                                href="{{ route('tours_details', ['id' => $tour->tourId]) }}">{{ $tour->title }}</a>
                                        </h6>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</section>
<!-- Tour Details Area end -->
<!-- Lightbox Viewer -->
<!-- Lightbox Viewer -->
<div id="lightbox"
    style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.85); justify-content:center; align-items:center; z-index:9999;">
    <!-- Nút đóng -->
    <span
        style="position:absolute; top:20px; right:30px; font-size:48px; font-weight: bold; color:white; cursor:pointer;"
        onclick="closeLightbox()">×</span>

    <!-- Nút mũi tên trái -->
    <button id="prevBtn"
        style="position:absolute; left:30px; font-size:60px; font-weight: bold; color:white; background:none; border:none; cursor:pointer; z-index:10000;">
        ‹
    </button>

    <!-- Ảnh -->
    <img id="lightbox-img" src="" style="max-width:90%; max-height:90%;" />

    <!-- Nút mũi tên phải -->
    <button id="nextBtn"
        style="position:absolute; right:30px; font-size:60px; font-weight: bold; color:white; background:none; border:none; cursor:pointer; z-index:10000;">
        ›
    </button>
</div>

<script>
    const images = Array.from(document.querySelectorAll('.tour-image'));
    let currentIndex = 0;

    const lightbox = document.getElementById('lightbox');
    const lightboxImg = document.getElementById('lightbox-img');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');

    images.forEach((img, index) => {
        img.addEventListener('click', function() {
            currentIndex = index;
            showImage();
        });
    });

    function showImage() {
        lightboxImg.src = images[currentIndex].src;
        lightbox.style.display = 'flex';
    }

    function closeLightbox() {
        lightbox.style.display = 'none';
    }

    function showNext() {
        currentIndex = (currentIndex + 1) % images.length;
        showImage();
    }

    function showPrev() {
        currentIndex = (currentIndex - 1 + images.length) % images.length;
        showImage();
    }

    nextBtn.addEventListener('click', showNext);
    prevBtn.addEventListener('click', showPrev);

    // Đóng khi click nền đen
    lightbox.addEventListener('click', function(e) {
        if (e.target === lightbox) {
            closeLightbox();
        }
    });

    // Hỗ trợ phím trái / phải / ESC
    document.addEventListener('keydown', function(e) {
        if (lightbox.style.display === 'flex') {
            if (e.key === 'ArrowRight') showNext();
            if (e.key === 'ArrowLeft') showPrev();
            if (e.key === 'Escape') closeLightbox();
        }
    });
</script>


@include('clients.blocks.news_letter')
@include('clients.blocks.footer')
