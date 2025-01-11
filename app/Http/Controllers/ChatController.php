<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversation;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
	public function index()
	{
		return view('chat');
	}

	public function sendMessage(Request $request)
	{
		Log::info('Stream request received.');

		$request->validate([
			'message' => 'required|string',
		]);

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
				'stream' => true, 
			];

			$response = Http::withHeaders($headers)
			->withOptions(['stream' => true])
				->post('https://api.openai.com/v1/chat/completions', $payload);

			Log::info('Streaming response initiated.');

			return response()->stream(function () use ($response, $userMessage) {
				if ($response->successful()) {
					$body = $response->getBody();
					$assistantMessage = ''; 
					while (!$body->eof()) {
						$chunk = $body->read(1024);

						Log::info("Raw chunk received: " . $chunk);

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
										$assistantMessage .= $content; 
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
