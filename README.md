# ChatGPT Integration in Laravel

This project demonstrates how to integrate OpenAI's ChatGPT API into a Laravel application. It features a real-time chat interface, persistent conversation history, and support for Markdown rendering.

---

## 📋 Table of Contents

- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Usage](#usage)
- [File Structure](#file-structure)
- [Customization](#customization)
- [Troubleshooting](#troubleshooting)
- [Contributing](#contributing)
- [License](#license)

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

### Step 2: Install Dependencies
Run the following command to install all necessary dependencies for the Laravel application:
```bash
composer install

### Step 3: Configure Environment Variables

1. Copy the `.env.example` file to `.env`:
   ```bash
   cp .env.example .env
