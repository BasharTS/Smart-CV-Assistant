# Smart-CV-Assistant
Smart CV Assistant is an AI-powered web application that helps users create, customize, analyze, and improve their CVs/resumes and application letters with ease. Powered by Google Gemini API and built with PHP and Bootstrap, it combines simplicity with intelligence.

# Features
•	CV Maker - Generate a professional, well-formatted CV by filling out simple fields.
•	AI-Powered Summary & Letter - Automatically generates a Professional Summary and an Application Letter tailored to the job.
•	CV Analyzer - Upload an existing .docx or .txt CV to receive structured feedback and an optional Job Match Score.
•	Download as DOCX - Output is downloadable in Word format with proper formatting.
•	Beginner-Friendly - Designed with a clean UI and maintainable PHP code structure.

# Technologies Used
•	PHP + PhpWord
•	Bootstrap 5
•	Google Gemini (AI)
•	JavaScript

# How to Set Up Locally
Prerequisites: PHP 7.x, Composer, Web Server (e.g., WAMP), and a Gemini API key.
# Steps:
1.	Clone the repository:
   git clone https://github.com/yourusername/smart-cv-assistant.git
2.	Navigate into the folder:
   cd smart-cv-assistant
3.	Install dependencies:
   composer install
4.	Configure your Gemini API key in api.php
5.	Run the application on localhost using a server like XAMPP or WAMP.
Folder Structure

── Public/index.php       # Landing Page
── api.php                # Handles all backend API actions
── create_cv.php          # Main CV creation UI
── analyze_cv.php         # Upload & analyze CV
── assets/                # (Optional) Images, CSS, JS
── vendor/                # Composer dependencies

AI Models
Powered by Gemini 2.0 Flash for resume formatting, feedback, and scoring.

Author
Bashar Tukur Shehu
Email: bashartukurshehu@gmail.com
GitHub: https://github.com/BasharTukurShehu
