# ChatGPT Integration in Laravel

This project demonstrates how to integrate OpenAI's ChatGPT API into a Laravel application. It features a real-time chat interface, persistent conversation history, and support for Markdown rendering.

---

## 🚀 Features

- **ChatGPT API Integration**: Communicate with OpenAI's GPT models.
- **Real-Time Chat Interface**: Send and receive messages dynamically.
- **User-Friendly Layout**: User messages on the left, ChatGPT responses on the right.
- **Markdown Rendering**: ChatGPT responses are displayed with Markdown support.
- **Persistent Storage**: Save and retrieve conversations in the database.
- **Error Logging**: Comprehensive error handling and debugging logs.

---

## ⚙️ Requirements

- **PHP**: >= 8.0
- **Laravel**: >= 9.x
- **Composer**
- **OpenAI API Key**
- **Database**: MySQL, SQLite, or other Laravel-supported databases.

---

## 🛠️ Installation

### Step 1: Clone the Repository
```bash
git clone https://github.com/your-repo/chatgpt-integration.git
cd chatgpt-integration
```
### Step 2: Install Dependencies
Run the following command to install all necessary dependencies for the Laravel application:
```bash
composer install
```
### Step 3: Configure Environment Variables

1. Copy the `.env.example` file to `.env`:
   ```bash
   cp .env.example .env
   ```
2. Open the .env file and update the following settings:

  OpenAI API Key: Add your OpenAI API key for accessing ChatGPT services.
  Database Credentials: Configure your database connection details.
  ```bash
    # OpenAI API Key
    OPENAI_API_KEY=your_openai_api_key
    
    # Database Configuration
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database_name
    DB_USERNAME=your_database_username
    DB_PASSWORD=your_database_password
```
3. Test the environment configuration:

    Ensure the OpenAI API key is valid.
    Verify database connectivity using Laravel's migrations:
    ```bash
    php artisan migrate
    ```
4. Generate Application Key
    ```bash
    php artisan key:generate
    ```
5. Start the Application
   ```bash
    php artisan serve
   ```

### Usage

## Chat Interface
Navigate to http://127.0.0.1:8000/chat.
Enter a message in the input box and click "Send" to interact with ChatGPT.
    
   
