<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversation;
use Illuminate\Support\Facades\Http;
// use App\Services\ChatGptService;
use Illuminate\Support\Facades\Log;
class ChatController extends Controller
{
    //  protected $chatGptService;

    // public function __construct(ChatGptService $chatGptService)
    // {
    //     $this->chatGptService = $chatGptService;
    // }

	public function index()
	{
		return view('chat');
	}
	// public function sendMessage(Request $request)
	// {
	// 	Log::info($request->all());
	//     $userMessage = $request->input('message');
	//     $chatGptResponse = $this->chatGptService->sendMessage($userMessage);

	//     $conversation = Conversation::create([
	//         'user_message' => $userMessage,
	//         'chatgpt_response' => $chatGptResponse['choices'][0]['message']['content'],
	//     ]);

	//     return response()->json($chatGptResponse);
	// }
	// public function sendMessage(Request $request)
	// {
	//     $request->validate([
	//         'message' => 'required|string',
	//     ]);

	//     $userMessage = $request->input('message');

	//     try {
	//         // Sending request to OpenAI
	//         $response = Http::withHeaders([
	//             'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
	//         ])->post('https://api.openai.com/v1/chat/completions', [
	//             'model' => 'gpt-3.5-turbo',
	//             'messages' => [
	//                 ['role' => 'system', 'content' => 'You are a helpful assistant.'],
	//                 ['role' => 'user', 'content' => $userMessage],
	//             ],
	//         ]);

	//         if ($response->failed()) {
	//             throw new \Exception('Failed to get a valid response from OpenAI');
	//         }

	//         return response()->json($response->json(), 200);

	//     } catch (\Exception $e) {
	//         return response()->json(['error' => 'Failed to communicate with ChatGPT. ' . $e->getMessage()], 500);
	//     }
	// }
	//  public function sendMessage(Request $request)
	// {
	// 	Log::info('Came');
	//     // Validate the user input
	//     $request->validate([
	//         'message' => 'required|string',
	//     ]);

	//     // Get the user message from the request
	//     $userMessage = $request->input('message');

	//     try {
	//         // Use OpenAI's API through the OpenAI facade
	//         $response = OpenAI::chat()->create([
	//             'model' => 'gpt-3.5-turbo',
	//             'messages' => [
	//                 ['role' => 'system', 'content' => 'You are a helpful assistant.'],
	//                 ['role' => 'user', 'content' => $userMessage],
	//             ],
	//         ]);

	//         // Get the content of the ChatGPT response
	//         $chatGptResponse = $response['choices'][0]['message']['content'];

	//         // Optionally, store the conversation in the database
	//         $conversation = Conversation::create([
	//             'user_message' => $userMessage,
	//             'chatgpt_response' => $chatGptResponse,
	//         ]);

	//         // Return the response to the frontend
	//         return response()->json([
	//             'user_message' => $userMessage,
	//             'chatgpt_response' => $chatGptResponse,
	//         ], 200);

	//     } catch (\Exception $e) {
	//         // Log the error and return a failure message
	//         Log::error('ChatGPT Error: ' . $e->getMessage());
	//         return response()->json([
	//             'error' => 'Failed to communicate with ChatGPT. ' . $e->getMessage()
	//         ], 500);
	//     }
	// }
	public function sendMessage(Request $request)
	{
		Log::info('Came');

		// Validate the user input
		$request->validate([
			'message' => 'required|string',
		]);

		// Get the user message from the request
		$userMessage = $request->input('message');

		try {
			// Make the API request to OpenAI using the HTTP client
			$response = Http::withHeaders([
				'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
			])->post('https://api.openai.com/v1/chat/completions', [
				'model' => 'gpt-3.5-turbo',
				'messages' => [
					['role' => 'system', 'content' => 'You are a helpful assistant.'],
					['role' => 'user', 'content' => $userMessage],
				],
			]);

			// Check if the request was successful
			if ($response->successful()) {
				// Get the content of the ChatGPT response
				$chatGptResponse = $response->json()['choices'][0]['message']['content'];

				// Optionally, store the conversation in the database
				$conversation = Conversation::create([
						'user_message' => $userMessage,
						'chatgpt_response' => $chatGptResponse,
					]);

				// Return the response to the frontend
				return response()->json([
					'user_message' => $userMessage,
					'chatgpt_response' => $chatGptResponse,
				], 200);
			} else {
				// Handle error if the API call failed
				Log::error('ChatGPT API error: ' . $response->body());
				return response()->json([
					'error' => 'Failed to communicate with ChatGPT. ' . $response->body(),
				], 500);
			}
		} catch (\Exception $e) {
			// Log the error and return a failure message
			Log::error('ChatGPT Error: ' . $e->getMessage());
			return response()->json([
				'error' => 'Failed to communicate with ChatGPT. ' . $e->getMessage()
			], 500);
		}
	}
	public function getConversations()
	{
		try {
			$conversations = Conversation::orderBy('created_at', 'asc')->get();

			return response()->json([
				'status' => 'success',
				'data' => $conversations
			], 200);
		} catch (\Exception $e) {
			return response()->json([
				'status' => 'error',
				'message' => $e->getMessage()
			], 500);
		}
	}
}
