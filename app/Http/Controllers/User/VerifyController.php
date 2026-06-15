<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\KycApplicationRequest;
use App\Mail\NewNotification;
use App\Models\Kyc;
use App\Models\Settings;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Services\NotificationService;

class VerifyController extends Controller
{
    //
    public function verifyaccount(KycApplicationRequest $request)
    {
        $user = Auth::user();
        $whitelist = array('jpeg', 'jpg', 'png');

        // filter front of document upload
        $frontimg = $request->file('frontimg');
        $backimg = $request->file('backimg');
        $backimgExtention = $backimg->extension();
        $extension = $frontimg->extension();

        if (!in_array($extension, $whitelist) or !in_array($backimgExtention, $whitelist)) {
            return redirect()->back()
                ->with('message', 'Unaccepted Image Uploaded, please make sure to upload the correct document.');
        }

        // upload documents to storage
        $frontimgPath = $frontimg->store('uploads', 'public');
        $backimgPath = $backimg->store('uploads', 'public');

        $kyc = new Kyc();

        $kyc->document_type = $request->document_type;
        $kyc->frontimg = $frontimgPath;
        $kyc->backimg = $backimgPath;
        $kyc->status = 'Under review';
        $kyc->user_id = $user->id;
        $kyc->save();


        //update user
        User::where('id', $user->id)
            ->update([
                'kyc_id' => $kyc->id,
                'account_verify' => 'Under review',
            ]);

        $settings = Settings::find(1);
        $message = "This is to inform you that $user->name just submitted a request for KYC(identity verification), please login your admin account to review and take neccessary action.";
        $subject = "Identity Verification Request from $user->name";
        $url = config('app.url') . '/admin/dashboard/kyc';
        try {
            Mail::to($settings->contact_email)->send(new NewNotification($message, $subject, 'Admin', $url));
        } catch (\Exception $e) {
            Log::error('KYC submission admin email failed: ' . $e->getMessage());
        }

        NotificationService::notifyUser(User::find($user->id), 'kyc', 'KYC Documents Submitted', 'Your identity verification documents have been submitted and are under review.', url('dashboard/verification'));

        \App\Services\NotificationService::notifyAdmin('kyc', 'New KYC Submission', $user->name . ' submitted KYC documents and is awaiting review.', url('admin/dashboard/kyc-applications'));

        return redirect()->back()->with('success', 'Action Sucessful! Please wait while we verify your application. You will receive an email regarding the status of your application.');
    }
}
