# ChatGPT-Integration
This project demonstrates how to integrate OpenAI's ChatGPT API into a Laravel application. It includes features for sending messages to ChatGPT, displaying responses, and managing conversations in a user-friendly chat interface.

Features
ChatGPT API integration using Laravel's HTTP client.
Real-time conversation interface.
Message alignment (User on the left, ChatGPT on the right).
Markdown support for ChatGPT responses.
Error handling and logging for debugging.
Persistent storage of conversation history.
Requirements
PHP >= 8.0
Composer
Laravel >= 9.x
OpenAI API Key
A database (MySQL, SQLite, etc.) configured in the .env file.
Installation
Clone the Repository:

bash
Copy code
git clone https://github.com/your-repo/chatgpt-integration.git
cd chatgpt-integration
Install Dependencies:

bash
Copy code
composer install
Set Up Environment Variables: Copy the .env.example file to .env and configure your environment:

bash
Copy code
cp .env.example .env
Add your OpenAI API Key to the .env file:

makefile
Copy code
OPENAI_API_KEY=your_openai_api_key
Generate Application Key:

bash
Copy code
php artisan key:generate
Set Up Database: Update your .env file with database credentials and run migrations:

bash
Copy code
php artisan migrate
Start the Application:

bash
Copy code
php artisan serve
The application will be available at http://127.0.0.1:8000.

Usage
Chat Interface:

Access the chat interface at http://127.0.0.1:8000/chat.
Type a message and click "Send" to communicate with ChatGPT.
