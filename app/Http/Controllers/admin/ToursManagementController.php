<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\ToursModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Log;

class ToursManagementController extends Controller
{
    private $tours;

    public function __construct()
    {
        $this->tours = new ToursModel();
    }

    public function index()
    {
        $title = 'Quản lý Tours';

        $tours = $this->tours->getAllTours();
        return view('admin.tours', compact('title', 'tours'));
    }

    public function pageAddTours()
    {
        $title = 'Thêm Tours';

        return view('admin.add-tours', compact('title'));
    }

    public function addTours(Request $request)
    {
        try {
            // Lấy dữ liệu từ request
            $name = $request->input('name');
            $destination = $request->input('destination');
            $domain = $request->input('domain');
            $quantity = $request->input('number');
            $price_adult = $request->input('price_adult');
            $price_child = $request->input('price_child');
            $start_date = $request->input('start_date');
            $end_date = $request->input('end_date');
            $description = $request->input('description');
            $regulatery = $request->input('regulatery');

            // Tính số ngày và đêm
            $time = $this->calculateTripDuration($start_date, $end_date);

            // Kiểm tra nếu có lỗi trong tính toán thời gian
            if (strpos($time, 'Lỗi') !== false || strpos($time, 'không thể nhỏ hơn') !== false) {
                return response()->json([
                    'success' => false,
                    'message' => $time
                ], 400);
            }

            // Chuyển đổi định dạng ngày
            $startDate = Carbon::createFromFormat('d/m/Y', $start_date)->format('Y-m-d');
            $endDate = Carbon::createFromFormat('d/m/Y', $end_date)->format('Y-m-d');

            // Chuẩn bị dữ liệu để lưu tour
            $dataTours = [
                'title' => $name,
                'time' => $time,
                'description' => $description,
                'quantity' => $quantity,
                'priceAdult' => $price_adult,
                'priceChild' => $price_child,
                'destination' => $destination,
                'domain' => $domain,
                'availability' => 0,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'regulatery' => $regulatery,
            ];

            // Tạo tour mới
            $createTour = $this->tours->createTours($dataTours);

            return response()->json([
                'success' => true,
                'message' => 'Tour added successfully!',
                'tourId' => $createTour
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function addImagesTours(Request $request)
    {
        try {
            $image = $request->file('image');
            $tourId = $request->tourId;

            if (!$image->isValid()) {
                return response()->json(['success' => false, 'message' => 'Invalid file upload'], 400);
            }

            $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $image->getClientOriginalExtension();
            $filename = preg_replace('/[^A-Za-z0-9_\-]/', '_', $originalName) . '_' . time() . '.' . $extension;

            $manager = new ImageManager(new Driver());
            $resizedImage = $manager->read($image)->resize(400, 350);
            $destinationPath = public_path('admin/assets/images/gallery-tours/');
            $resizedImage->save($destinationPath . $filename);

            $dataUpload = [
                'tourId' => $tourId,
                'imageURL' => $filename,
                'description' => $originalName
            ];

            $uploadImage = $this->tours->uploadImages($dataUpload);

            if ($uploadImage) {
                return response()->json([
                    'success' => true,
                    'message' => 'Image uploaded successfully',
                    'data' => [
                        'filename' => $filename,
                        'tourId' => $tourId
                    ]
                ], 200);
            }

            return response()->json(['success' => false, 'message' => 'Failed to save image data'], 500);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function addTimeline(Request $request)
    {
        $tourId = $request->tourId;

        $timelines = [];

        foreach ($request->all() as $key => $value) {
            if (preg_match('/^day-(\d+)$/', $key, $matches)) {
                $dayNumber = $matches[1];
                $itineraryKey = "itinerary-{$dayNumber}";
                if ($request->has($itineraryKey)) {
                    $timelines[] = [
                        'tourId' => $tourId,
                        'title' => $value,
                        'description' => $request->input($itineraryKey),
                        'regulatery' => $request->input($itineraryKey),
                    ];
                }
            }
        }

        foreach ($timelines as $timeline) {
            $this->tours->addTimeLine($timeline);
        }

        $dataUpdate = ['availability' => 1];
        $updateAvailability = $this->tours->updateTour($tourId, $dataUpdate);
        toastr()->success('Thêm tour thành công!');
        return redirect()->route('admin.page-add-tours');
    }

    public function getTourEdit(Request $request)
    {
        $tourId = $request->tourId;

        $getTour = $this->tours->getTour($tourId);
        $startDate = Carbon::parse($getTour->startDate);
        $today = Carbon::now();

        if ($startDate->lessThanOrEqualTo($today)) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể chỉnh sửa vì tour đã hoặc đang diễn ra.',
            ]);
        }

        $getImages = $this->tours->getImages($tourId);
        $getTimeLine = $this->tours->getTimeLine($tourId);
        if ($getTour) {
            return response()->json([
                'success' => true,
                'tour' => $getTour,
                'images' => $getImages,
                'timeline' => $getTimeLine
            ]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function uploadTempImagesTours(Request $request)
    {
        try {
            $image = $request->file('image');
            $tourId = $request->input('tourId');

            if (!$image || !$image->isValid()) {
                return response()->json(['success' => false, 'message' => 'Invalid file upload'], 400);
            }

            $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $image->getClientOriginalExtension();
            $filename = preg_replace('/[^A-Za-z0-9_\-]/', '_', $originalName) . '_' . time() . '.' . $extension;

            $destinationPath = public_path('admin/assets/images/gallery-tours/');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0775, true);
            }

            $manager = new ImageManager(new Driver());
            $resizedImage = $manager->read($image->getRealPath())->resize(400, 350, function ($constraint) {
                $constraint->aspectRatio();
            });

            $resizedImage->save($destinationPath . $filename);

            $dataUpload = [
                'tourId' => $tourId,
                'imageTempURL' => $filename,
            ];

            $uploadImage = $this->tours->uploadTempImages($dataUpload);

            if ($uploadImage) {
                return response()->json([
                    'success' => true,
                    'message' => 'Image uploaded successfully',
                    'data' => ['filename' => $filename, 'tourId' => $tourId]
                ], 200);
            }

            return response()->json(['success' => false, 'message' => 'Failed to save image data'], 500);
        } catch (\Exception $e) {
            Log::error('Upload error: ' . $e->getMessage(), ['tourId' => $request->input('tourId'), 'stack' => $e->getTraceAsString()]);
            return response()->json(['success' => false, 'message' => 'Server error: ' . $e->getMessage()], 500);
        }
    }

    public function updateTour(Request $request)
    {
        try {
            $tourId = $request->tourId;
            $name = $request->input('name');
            $destination = $request->input('destination');
            $domain = $request->input('domain');
            $quantity = $request->input('number');
            $price_adult = $request->input('price_adult');
            $price_child = $request->input('price_child');
            $description = $request->input('description');
            $regulatery = $request->input('regulatery');
            $start_date = $request->input('start_date');
            $end_date = $request->input('end_date');

            // Tính số ngày và đêm
            $time = $this->calculateTripDuration($start_date, $end_date);

            // Kiểm tra nếu có lỗi trong tính toán thời gian
            if (strpos($time, 'Lỗi') !== false || strpos($time, 'không thể nhỏ hơn') !== false) {
                return response()->json([
                    'success' => false,
                    'message' => $time
                ], 400);
            }

            // Chuyển đổi định dạng ngày
            $startDate = Carbon::createFromFormat('d/m/Y', $start_date)->format('Y-m-d');
            $endDate = Carbon::createFromFormat('d/m/Y', $end_date)->format('Y-m-d');

            // Chuẩn bị dữ liệu để cập nhật tour
            $dataTours = [
                'title'       => $name,
                'time'        => $time,
                'description' => $description,
                'quantity'    => $quantity,
                'priceAdult'  => $price_adult,
                'priceChild'  => $price_child,
                'destination' => $destination,
                'domain'      => $domain,
                'regulatery'  => $regulatery,
                'startDate'   => $startDate,
                'endDate'     => $endDate,
            ];

            // Xóa timeline và images cũ
            $delete_timeline = $this->tours->deleteData($tourId, 'tbl_timeline');
            $delete_images = $this->tours->deleteData($tourId, 'tbl_images');

            // Cập nhật tour
            $updateTour = $this->tours->updateTour($tourId, $dataTours);

            // Xử lý ảnh
            $images = $request->input('images');
            if ($images && is_array($images)) {
                foreach ($images as $image) {
                    $dataUpload = [
                        'tourId' => $tourId,
                        'imageURL' => $image,
                        'description' => $name
                    ];
                    $this->tours->uploadImages($dataUpload);
                }
            }

            // Xử lý timeline
            $timelines = $request->input('timeline');
            if ($timelines && is_array($timelines)) {
                foreach ($timelines as $timeline) {
                    $data = [
                        'tourId' => $tourId,
                        'title' => $timeline['title'],
                        'description' => $timeline['itinerary'],
                        'regulatery' => $timeline['itinerary'],
                    ];
                    $this->tours->addTimeLine($data);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Sửa thành công!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteTour(Request $request)
    {
        $tourId = $request->tourId;

        $result = $this->tours->deleteTour($tourId);
        $tours = $this->tours->getAllTours();
        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'data' => view('admin.partials.list-tours', compact('tours'))->render()
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => $result['message']
            ]);
        }
    }

    /**
     * Hàm tính số ngày và đêm cho tour
     * @param string $start_date Định dạng d/m/Y
     * @param string $end_date Định dạng d/m/Y
     * @return string Kết quả dạng "X ngày Y đêm" hoặc thông báo lỗi
     */
    private function calculateTripDuration($start_date, $end_date)
    {
        try {
            // Chuyển định dạng ngày từ d/m/Y sang đối tượng Carbon
            $startDate = Carbon::createFromFormat('d/m/Y', $start_date);
            $endDate = Carbon::createFromFormat('d/m/Y', $end_date);

            // Kiểm tra nếu end_date nhỏ hơn start_date
            if ($endDate->lessThan($startDate)) {
                return "Ngày kết thúc không thể nhỏ hơn ngày bắt đầu.";
            }

            // Tính số ngày (bao gồm cả ngày kết thúc)
            $days = $startDate->diffInDays($endDate) + 1;

            // Tính số đêm (số ngày - 1, nhưng không âm)
            $nights = max(0, $days - 1);

            // Định dạng kết quả
            return "{$days} ngày {$nights} đêm";
        } catch (\Exception $e) {
            return "Lỗi: Định dạng ngày không hợp lệ.";
        }
    }
}
