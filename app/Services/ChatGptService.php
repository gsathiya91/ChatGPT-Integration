namespace App\Services;

use Illuminate\Support\Facades\Http;

class ChatGptService
{
    public function sendMessage(string $message)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
        ])
        ->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-3.5-turbo', // Or the model you want to use
            'messages' => [
                ['role' => 'user', 'content' => $message],
            ],
            'max_tokens' => 150,
            'temperature' => 0.7,
        ]);

        return $response->json();
    }
}
