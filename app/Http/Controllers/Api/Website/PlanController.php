<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::all();
        return response()->json($plans, 200);
    }


    public function generatePlanSummary($plan)
{
    $summary = "**تفاصيل الخطة الخاصة بك:**\n\n";

    $summary .= "**الخطة**: {$plan->name}\n";
    $summary .= "**السعر**: {$plan->price}\n";

    $summary .= "--------------------\n";
    $summary .= "شكراً لاختيارك خدمتنا!";

    return $summary;
}

public function generateWhatsAppLink(Request $request)
{
    $planId = $request->input('plan_id');
    $plan = Plan::find($planId);

    if (!$plan) {
        return response()->json(['message' => 'الخطة غير موجودة.'], 404);
    }

    $summary = $this->generatePlanSummary($plan);
    $encodedMessage = urlencode($summary);

    // Replace with the recipient's phone number (include country code)
    $recipientPhoneNumber = $request->input('phone_number');

    $whatsappLink = "https://wa.me/$recipientPhoneNumber?text=$encodedMessage";

    return response()->json([
        'whatsapp_link' => $whatsappLink
    ]);
}



}
