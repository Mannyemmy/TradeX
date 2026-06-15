<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Mail\NewNotification;
use App\Models\Kyc;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Services\NotificationService;

class KycController extends Controller
{

    public function processKyc(Request $request)
    {
        $application = Kyc::find($request->kyc_id);
        $user = User::where('id', $application->user_id)->first();

        // will use API key
        if ($request->action == 'Accept') {
            User::where('id', $user->id)
                ->update([
                    'account_verify' => 'Verified',
                ]);
            $application->status = "Verified";
            $application->save();
        } else {
            if (Storage::disk('public')->exists($application->frontimg) and Storage::disk('public')->exists($application->backimg)) {
                Storage::disk('public')->delete($application->frontimg);
                Storage::disk('public')->delete($application->backimg);
            }

            // Update the user verification status
            $user->account_verify = 'Rejected';
            $user->save();
            // delete the application form database so user can resubmit application
            $application->delete();
        }

        try {
            Mail::to($user->email)->send(new NewNotification($request->message, $request->subject, $user->name));
        } catch (\Exception $e) {
            Log::error('KYC notification email failed: ' . $e->getMessage());
        }

        $kycStatus = ($request->action == 'Accept') ? 'Verified' : 'Rejected';
        NotificationService::notifyUser($user, 'kyc', 'KYC ' . $kycStatus, ($kycStatus == 'Verified') ? 'Your identity verification has been approved.' : 'Your identity verification was rejected. Please resubmit your documents.', url('dashboard/verification'));

        return redirect()->route('kyc')->with('success', 'Action Sucessful!');
    }
}
