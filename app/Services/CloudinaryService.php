<?php
namespace App\Services;

use App\Repositories\EducationRepository;
use Cloudinary\Api\Utils\ApiUtils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CloudinaryService
{

    protected $educationRepository;

    public function __construct(EducationRepository $educationRepository)
    {
        $this->educationRepository = $educationRepository;
    }



    public function generateSignature()
    {
        $timestamp = time();
        $expiry = strtotime('+10 minutes');
        $paramsToSign = [
            'timestamp' => $timestamp,
            'upload_preset' => 'ml_default', // يجب أن تكون قد أنشأت مسبقًا في إعدادات Cloudinary
            'expiry' => $expiry,
        ];

        $apiSecret = env('CLOUDINARY_API_SECRET');
        $signature = \Cloudinary\Api\ApiUtils::signParameters($paramsToSign, $apiSecret);

        return response()->json([
            'signature' => $signature,
            'timestamp' => $timestamp,
            'api_key' => env('CLOUDINARY_API_KEY'),
            'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
            'upload_preset' => 'ml_default',
            'expiry' => $expiry,
        ]);
    }


    public function saveExplanationUrl($explanatId, $url,$title)
    {
        return $this->educationRepository->saveExplanationUrl($explanatId, $url,$title);
    }

    public function uploadToCloudinary1(Request $request)
    {
        // تحقق من أن الطلب يحتوي على ملف مرفق
        if (!$request->hasFile('image')) {
            return response()->json(['error' => 'No image file found in the request'], 400);
        }

        // احصل على التوقيع الرقمي من المشروع الآخر
        $signatureResponse = $this->generateSignature();
        $signatureData = $signatureResponse->getData(true); // تحويل الاستجابة إلى مصفوفة

        $file = $request->file('image');

        // إنشاء FormData وإضافة البيانات اللازمة
        $formData = [
            'file' => fopen($file->getPathname(), 'r'),
            'timestamp' => $signatureData['timestamp'],
            'api_key' => $signatureData['api_key'],
            'signature' => $signatureData['signature'],
            'upload_preset' => $signatureData['upload_preset'],
        ];

        // إرسال طلب الرفع إلى Cloudinary مع زيادة مهلة الاتصال
        $uploadResponse = Http::timeout(150) // تحديد المهلة بـ 30 دقيقة
        ->asMultipart()
            ->post("https://api.cloudinary.com/v1_1/{$signatureData['cloud_name']}/image/upload", $formData);

        if ($uploadResponse->successful()) {
            return $uploadResponse->json();
        } else {
            // إذا فشل الطلب، قم بإعادة الاستجابة مع تفاصيل الخطأ
            return response()->json(['error' => 'Failed to upload image', 'details' => $uploadResponse->json()], 500);

        }
    }


//    public function uploadToCloudinary($image)
//    {
//        // احصل على التوقيع الرقمي من المشروع الآخر
//        $signatureResponse = $this->generateSignature();
//        $signatureData = $signatureResponse->getData(true); // تحويل الاستجابة إلى مصفوفة
//
//
////        $file = $request->file('image');
//
//        // إنشاء FormData وإضافة البيانات اللازمة
//        $formData = [
//            'file' => fopen($image->getPathname(), 'r'),
//            'timestamp' => $signatureData['timestamp'],
//            'api_key' => $signatureData['api_key'],
//            'signature' => $signatureData['signature'],
//            'upload_preset' => $signatureData['upload_preset'],
//        ];
//
//        // إرسال طلب الرفع إلى Cloudinary مع زيادة مهلة الاتصال
//        $uploadResponse = Http::timeout(150) // تحديد المهلة بـ 60 ثانية
//        ->asMultipart()
//            ->post("https://api.cloudinary.com/v1_1/{$signatureData['cloud_name']}/image/upload", $formData);
//
//        return $uploadResponse;
//    }




}
