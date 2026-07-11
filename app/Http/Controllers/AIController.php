<?php

namespace App\Http\Controllers;

use App\Services\AIService;
use App\Models\AiLog;
use Illuminate\Http\Request;

class AIController extends Controller
{
    protected $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * AJAX endpoint for Student Chatbot.
     */
    public function ajaxChatbot(Request $request)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        $userMessage = $request->message;
        $userId = auth()->id();

        // Call Gemini Service
        $reply = $this->aiService->chatbotResponse($userMessage, $userId);

        return response()->json([
            'success' => true,
            'reply' => $reply
        ]);
    }

    /**
     * AI Log records listing (Admin view).
     */
    public function logs()
    {
        $this->middleware('role:admin');
        
        $logs = AiLog::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.ai_logs', compact('logs'));
    }
}
