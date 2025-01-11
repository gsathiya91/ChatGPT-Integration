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
	// 	Log::info('Came');

	// 	// Validate the user input
	// 	$request->validate([
	// 		'message' => 'required|string',
	// 	]);

	// 	// Get the user message from the request
	// 	$userMessage = $request->input('message');

	// 	// try {
	// 	// 	// Make the API request to OpenAI using the HTTP client
	// 	// 	$response = Http::withHeaders([
	// 	// 		'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
	// 	// 	])->post('https://api.openai.com/v1/chat/completions', [
	// 	// 		'model' => 'gpt-3.5-turbo',
	// 	// 		'messages' => [
	// 	// 			['role' => 'system', 'content' => 'You are a helpful assistant.'],
	// 	// 			['role' => 'user', 'content' => $userMessage],
	// 	// 		],
	// 	// 		'stream' => true, 
	// 	// 	]);

	// 	// 	// Check if the request was successful
	// 	// 	if ($response->successful()) {
	// 	// 		// Get the content of the ChatGPT response
	// 	// 		$chatGptResponse = $response->json()['choices'][0]['message']['content'];

	// 	// 		// Optionally, store the conversation in the database
	// 	// 		$conversation = Conversation::create([
	// 	// 				'user_message' => $userMessage,
	// 	// 				'chatgpt_response' => $chatGptResponse,
	// 	// 			]);

	// 	// 		// Return the response to the frontend
	// 	// 		return response()->json([
	// 	// 			'user_message' => $userMessage,
	// 	// 			'chatgpt_response' => $chatGptResponse,
	// 	// 		], 200);
	// 	// 	} else {
	// 	// 		// Handle error if the API call failed
	// 	// 		Log::error('ChatGPT API error: ' . $response->body());
	// 	// 		return response()->json([
	// 	// 			'error' => 'Failed to communicate with ChatGPT. ' . $response->body(),
	// 	// 		], 500);
	// 	// 	}
	// 	// } catch (\Exception $e) {
	// 	// 	// Log the error and return a failure message
	// 	// 	Log::error('ChatGPT Error: ' . $e->getMessage());
	// 	// 	return response()->json([
	// 	// 		'error' => 'Failed to communicate with ChatGPT. ' . $e->getMessage()
	// 	// 	], 500);
	// 	// }
	// 	try {
	// 		$headers = [
	// 			'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
	// 		];

	// 		$payload = [
	// 			'model' => 'gpt-3.5-turbo',
	// 			'messages' => [
	// 				['role' => 'system', 'content' => 'You are a helpful assistant.'],
	// 				['role' => 'user', 'content' => $userMessage],
	// 			],
	// 			'stream' => true, // Enable streaming
	// 		];

	// 		$response = Http::withHeaders($headers)
	// 			->withOptions(['stream' => true])
	// 			->post('https://api.openai.com/v1/chat/completions', $payload);
	// 		Log::info($response);
	// 		return response()->stream(function () use ($response) {
	// 			if ($response->successful()) {
	// 				$body = $response->getBody();

	// 				while (!$body->eof()) {
	// 					echo $body->read(1024);
	// 					ob_flush();
	// 					flush();
	// 				}
	// 			} else {
	// 				echo json_encode(['error' => 'Failed to stream response.']);
	// 			}
	// 		}, 200, [
	// 			'Content-Type' => 'text/event-stream',
	// 			'Cache-Control' => 'no-cache',
	// 			'Connection' => 'keep-alive',
	// 		]);
	// 	} catch (\Exception $e) {
	// 		Log::error('Error during streaming: ' . $e->getMessage());
	// 		return response()->json(['error' => $e->getMessage()], 500);
	// 	}
	// }
	// public function sendMessage(Request $request)
	// {
	// 	Log::info('Stream request received.');

	// 	// Validate the user input
	// 	$request->validate([
	// 		'message' => 'required|string',
	// 	]);

	// 	// Get the user message from the request
	// 	$userMessage = $request->input('message');
	// 	Log::info('User message: ' . $userMessage);

	// 	try {
	// 		$headers = [
	// 			'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
	// 		];

	// 		$payload = [
	// 			'model' => 'gpt-3.5-turbo',
	// 			'messages' => [
	// 				['role' => 'system', 'content' => 'You are a helpful assistant.'],
	// 				['role' => 'user', 'content' => $userMessage],
	// 			],
	// 			'stream' => true, // Enable streaming
	// 		];

	// 		$response = Http::withHeaders($headers)
	// 			->withOptions(['stream' => true])
	// 			->post('https://api.openai.com/v1/chat/completions', $payload);

	// 		Log::info('Streaming response initiated.');

	// 		return response()->stream(function () use ($response) {
	// 			if ($response->successful()) {
	// 				$body = $response->getBody();

	// 				while (!$body->eof()) {
	// 					$chunk = $body->read(1024);

	// 					// Log the raw chunk
	// 					Log::info("Chunk received: " . $chunk);

	// 					// Extract context from chunk if JSON
	// 					if (strpos($chunk, 'data:') !== false) {
	// 						$lines = explode("\n", $chunk);
	// 						foreach ($lines as $line) {
	// 							if (strpos($line, 'data:') === 0) {
	// 								$data = trim(substr($line, 5));
	// 								if ($data === '[DONE]') {
	// 									Log::info("Stream completed: [DONE]");
	// 									break;
	// 								}
	// 								try {
	// 									$decodedData = json_decode($data, true);
	// 									$content = $decodedData['choices'][0]['delta']['content'] ?? '';
	// 									Log::info("Streamed content: " . $content);
	// 								} catch (\Exception $e) {
	// 									Log::error("Error parsing streamed chunk: " . $e->getMessage());
	// 								}
	// 							}
	// 						}
	// 					}

	// 					echo $chunk;
	// 					ob_flush();
	// 					flush();
	// 				}
	// 			} else {
	// 				Log::error('Error in streaming response: ' . $response->body());
	// 				echo json_encode(['error' => 'Failed to stream response.']);
	// 			}
	// 		}, 200, [
	// 			'Content-Type' => 'text/event-stream',
	// 			'Cache-Control' => 'no-cache',
	// 			'Connection' => 'keep-alive',
	// 		]);
	// 	} catch (\Exception $e) {
	// 		Log::error('Error during streaming: ' . $e->getMessage());
	// 		return response()->json(['error' => $e->getMessage()], 500);
	// 	}
	// }
	public function sendMessage(Request $request)
	{
		Log::info('Stream request received.');

		// Validate the user input
		$request->validate([
			'message' => 'required|string',
		]);

		// Get the user message from the request
		$userMessage = $request->input('message');
		Log::info('User message: ' . $userMessage);

		try {
			$headers = [
				'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
			];

			$payload = [
				'model' => 'gpt-3.5-turbo',
				'messages' => [
					['role' => 'system', 'content' => 'You are a helpful assistant.'],
					['role' => 'user', 'content' => $userMessage],
				],
				'stream' => true, // Enable streaming
			];

			$response = Http::withHeaders($headers)
			->withOptions(['stream' => true])
				->post('https://api.openai.com/v1/chat/completions', $payload);

			Log::info('Streaming response initiated.');

			return response()->stream(function () use ($response, $userMessage) {
				if ($response->successful()) {
					$body = $response->getBody();
					$assistantMessage = ''; // Accumulate assistant's response

					while (!$body->eof()) {
						$chunk = $body->read(1024);

						// Log the raw chunk
						Log::info("Raw chunk received: " . $chunk);

						// Extract content from chunk if JSON
						if (strpos($chunk, 'data:') !== false) {
							$lines = explode("\n", $chunk);
							foreach ($lines as $line) {
								if (strpos($line, 'data:') === 0) {
									$data = trim(substr($line, 5));
									if ($data === '[DONE]') {
										Log::info("Stream completed: [DONE]");
										break;
									}
									try {
										$decodedData = json_decode($data, true);
										$content = $decodedData['choices'][0]['delta']['content'] ?? '';
										$assistantMessage .= $content; // Append to assistant's response
										Log::info("Streamed content: " . $content);
									} catch (\Exception $e) {
										Log::error("Error parsing streamed chunk: " . $e->getMessage());
									}
								}
							}
						}

						echo $chunk;
						ob_flush();
						flush();
					}

					// Save the user message and ChatGPT response to the database
					Conversation::create([
						'user_message' => $userMessage,
						'chatgpt_response' => $assistantMessage,
					]);
					Log::info('Conversation saved to database.');
				} else {
					Log::error('Error in streaming response: ' . $response->body());
					echo json_encode(['error' => 'Failed to stream response.']);
				}
			}, 200, [
				'Content-Type' => 'text/event-stream',
				'Cache-Control' => 'no-cache',
				'Connection' => 'keep-alive',
			]);
		} catch (\Exception $e) {
			Log::error('Error during streaming: ' . $e->getMessage());
			return response()->json(['error' => $e->getMessage()], 500);
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
