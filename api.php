<?php
require __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\Html;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");

// Ensure no previous output starts
if (ob_get_length() > 0) {
    ob_end_clean();
}
mb_internal_encoding("UTF-8");

$method = $_SERVER['REQUEST_METHOD'];
if ($method !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Only POST allowed']);
    exit;
}


$data = json_decode(file_get_contents('php://input'), true);
$action = $data['action'] ?? $_POST['action'] ?? null;

if (!$action) {
    echo json_encode(['error' => 'No action specified.']);
    exit;
}

// === CONFIG ===
$GEMINI_API_KEY = 'PLACE YOUR API KEY HERE'; 
$GEMINI_API_URL = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key='. $GEMINI_API_KEY;

// === GEMINI HELPER ===
function callGeminiAPI($prompt)
{
    global $GEMINI_API_URL;

    $postData = [
        'contents' => [
            ['parts' => [['text' => $prompt]]]
        ]
    ];

    $ch = curl_init($GEMINI_API_URL);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => json_encode($postData),
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_SSL_VERIFYPEER => false // stop ssl cert. verification
    ]);

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        return "Error: Gemini API call failed. cURL error: " . curl_error($ch);
    }

    curl_close($ch);
    $result = json_decode($response, true);
    return $result['candidates'][0]['content']['parts'][0]['text'] ?? null;
}

// === ACTION: GENERATE CV ===
if ($action === 'generate-cv') {
    $personal = $data['personal'] ?? [];
    $experience = $data['experience'] ?? [];
    $education = $data['education'] ?? [];
    $skills = $data['skills'] ?? '';
    $certifications = $data['certifications'] ?? '';
    $publications = $data['publications'] ?? '';
    $workshops = $data['workshops'] ?? '';
    $referees = $data['referees'] ?? [];
    $jobDescription = $data['jobDescription'] ?? '';

    // Combine all info for Gemini to use in generating the CV
    $cvData = [];

    foreach (['personal', 'experience', 'education', 'skills', 'certifications', 'publications', 'workshops', 'referees' ] as $key) {
            if (!empty($data[$key])) {
                $cvData[$key] = $data[$key];
            }
    }
    
    $prompt = "You are an expert CV (Resume) writer. Based on the provided user details, generate a professional, clean and Application Tracking System (ATS)-Friendly CV. Ensure all output is **pure plain text**, consisting only of standard alphanumeric characters, common punctuation (like periods, commas, hyphens, parentheses), and standard line breaks. Do NOT include any special Unicode characters, control characters, invisible characters, emojis, or XML/HTML tags. Use clear section titles in UPPERCASE (e.g., WORK EXPERIENCE) and include sections like 'Professional Summary (Use the relevant information from the CV Data to generate the Professional Summary)', 'Personal Information (Full name, Gender, LGA, State, Nationality and Languages)', 'Work Experience', 'Education', 'Skills', 'certifications', 'publications', 'conferences and workshops' and 'Referees' strictly in that order. Rephrase and correct any grammatical error, subtitute word provided by the user with synonymous yet better keywords that align with the job description if available. You do not need to include a Contact Information section — it will be added separately. Use bullet points where appropriate. Avoid markdown or HTML, only include the raw CV content. Of this sections only include sections that contain actual content. CV Data: " . json_encode($cvData, JSON_PRETTY_PRINT) ." jobDescription: " .($jobDescription ?? '');
    
    $text = callGeminiAPI($prompt);
    
    //Debug
        error_log("Gemini text starts: " . substr($text, 0, 4000));
        file_put_contents(__DIR__ . '/debug_gemini_output.txt', $text);
    //Debug

    if (!$text || substr($text, 0, 5) === "Error") {
        echo json_encode(['feedback' => 'Gemini failed to respond or returned error.']);
        exit;
    }

    $phpWord = new PhpWord();
    $section = $phpWord->addSection();

    $phpWord->addTitleStyle(1, ['bold' => true, 'size' => 16, 'name' => 'Times New Roman'], ['alignment' => 'center']);
    $phpWord->addFontStyle('heading', ['bold' => true, 'size' => 12, 'name' => 'Calibri']);
    $phpWord->addFontStyle('normal', ['size' => 12, 'name' => 'Times New Roman']);
    $phpWord->addFontStyle('labelBold', ['bold' => true, 'size' => 11, 'name' => 'Times New Roman']);
    $phpWord->addFontStyle('entryBold', ['bold' => true, 'size' => 11, 'name' => 'Times New Roman']);
    $phpWord->addParagraphStyle('tight', ['spaceAfter' => 50], ['alignment' => 'both']);
    $phpWord->addParagraphStyle('justified', ['alignment' => 'both']);


    // === Contact Information (Centralized) ===
    if (!empty($personal['name'])) {
        $section->addText($personal['name'], ['bold' => true, 'size' => 18, 'name' => 'Times New Roman'], ['alignment' => 'center']);
    }
    if (!empty($personal['address'])) {
        $section->addText($personal['address'], ['size' => 12, 'name' => 'Times New Roman'], ['alignment' => 'center'], 'tight');
    }
    if (!empty($personal['email'])) {
        $section->addText($personal['email'], ['size' => 12, 'name' => 'Times New Roman'], ['alignment' => 'center'], 'tight');
    }
    if (!empty($personal['phone'])) {
        $section->addText($personal['phone'], ['size' => 12, 'name' => 'Times New Roman'], ['alignment' => 'center'], 'tight');
    }
    if (!empty($personal['portfolio'])) {
        $section->addText($personal['portfolio'], ['size' => 12, 'name' => 'Times New Roman'], ['alignment' => 'center'], 'tight');
    }
    if (!empty($personal['linkedin'])) {
        $section->addText($personal['linkedin'], ['size' => 12, 'name' => 'Times New Roman'], ['alignment' => 'center'], 'tight');
    }
    $section->addTextBreak(0.5);
    
    $section->addLine(['weight' => 1, 'width' => 480, 'height' => 0, 'color' => '777777']);
    $section->addTextBreak(0.5);
    
    $text = preg_replace('/[^\x{0009}\x{000A}\x{000D}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}\x{10000}-\x{10FFFF}]/u', '', $text);
    $text = str_replace(["\r\n", "\r"], "\n", $text);
    $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');

    if (mb_detect_encoding($text, 'UTF-8', true) === false) {
        $text = utf8_encode($text);
    }



    $lines = explode("\n", $text);
    $inSummary = false;
    $inPersonalInfo = false;

    foreach ($lines as $index => $line) {
        $line = trim($line);
        if ($line === '') continue;

        // Sanitize invisible characters
        //$line = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $line);
        $line = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F\xEF\xBB\xBF]/u', '', $line);


        // Section Headers
        if (preg_match('/^PROFESSIONAL SUMMARY$/i', $line)) {
            $section->addText($line, 'heading', 'tight');
            $inSummary = true;
            $inPersonalInfo = false;
            continue;
        }

        if (preg_match('/^CERTIFICATIONS$/i', $line)) {
            $section->addText($line, 'heading', 'tight');
            $inPersonalInfo = true;
            $inSummary = false;
            continue;
        }
        if (preg_match('/^PUBLICATIONS$/i', $line)) {
            $section->addText($line, 'heading', 'tight');
            $inPersonalInfo = true;
            $inSummary = false;
            continue;
        }
        if (preg_match('/^CONFERENCES AND WORKSHOPS$/i', $line)) {
            $section->addText($line, 'heading', 'tight');
            $inPersonalInfo = true;
            $inSummary = false;
            continue;
        }

        if (preg_match('/^[A-Z\s]{3,}$/', $line)) {
            // New section
            $section->addText($line, 'heading', 'tight');
            $inSummary = false;
            $inPersonalInfo = false;
            continue;
        }

        try {
            // Summary paragraph
            if ($inSummary) {
                $section->addText($line, 'normal', 'justified');
            }

            // Personal Info label:value format
            elseif ($inPersonalInfo && strpos($line, ':') !== false) {
                [$label, $value] = explode(':', $line, 2);
                $textrun = $section->addTextRun();
                $textrun->addText(trim($label) . ': ', 'labelBold');
                $textrun->addText(trim($value), 'normal');
            }

            // Bullet points
            elseif (preg_match('/^[-*•]\s+/', $line)) {
                $section->addListItem(preg_replace('/^[-*•]\s+/', '', $line), 0, 'normal');
            }

            // Section entries like "Founder/CEO | Btshehu Digital Solutions"
            elseif (preg_match('/^[^:\n]{3,}\|/', $line)) {
                $section->addText($line, 'entryBold');
            }

            // Normal fallback line
            else {
                $section->addText($line, 'normal');
            }
        } catch (Exception $e) {
            error_log("About to save DOCX with " . count($lines) . " lines.");

            error_log("Failed to write line $index: $line | " . $e->getMessage());
        }
    }


    $temp = tempnam(sys_get_temp_dir(), 'cv') . '.docx';
    $writer = IOFactory::createWriter($phpWord, 'Word2007');

    try {
        $writer->save($temp);
    } catch (Exception $e) {
        echo json_encode(['feedback' => 'Failed to write DOCX: ' . $e->getMessage()]);
        exit;
    }
    ob_end_clean();
    header("Content-Description: File Transfer");
    header('Content-Disposition: attachment; filename="Smart-CV-Assistant.docx"');
    header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
    header('Content-Length: ' . filesize($temp));
    flush();
    readfile($temp);
    unlink($temp);
    exit;
}

// === ACTION: GENERATE LETTER ===
if ($action === 'generate-letter') {
    $personal = $data['personal'] ?? [];
    $experience = $data['experience'] ?? [];
    $education = $data['education'] ?? [];
    $skills = $data['skills'] ?? '';
    $jobDescription = $data['jobDescription'] ?? '';
    $certifications = $data['certifications'] ?? '';
    $publications = $data['publications'] ?? '';
    $workshops = $data['workshops'] ?? '';
    $referees = $data['referees'] ?? [];

    $cvData = [
        'personal' => $personal,
        'experience' => $experience,
        'education' => $education,
        'skills' => $skills,
        'certifications' => $certifications,
        'publications' => $publications,
        'workshops' => $workshops,
        'referees' => $referees
    ];

    $prompt = "You are a professional application letter (cover letter) writer. Based on the applicant's full CV details and the provided job description, draft a concise, compelling and well-structured application letter.\n\n. ";
    $prompt .= "Use formal business letter format with sender address, date, salutation, subject line, body paragraphs, and closing.\n\n";
    $prompt .= "Only return the letter content. No explanation.";
    $prompt .= "Applicant CV Details:\n" . json_encode($cvData, JSON_PRETTY_PRINT) . "\n\n";
    $prompt .= "Job Description:\n" . $jobDescription;

    $text = callGeminiAPI($prompt);

    $phpWord = new PhpWord();
    $phpWord->addFontStyle('letterText', ['size' => 11, 'name' => 'Times New Roman']);
    $phpWord->addParagraphStyle('tight', ['spaceAfter' => 0], ['alignment' => 'both']);
    $phpWord->addParagraphStyle('justified', ['alignment' => 'both']);    

    $section = $phpWord->addSection();

    foreach (explode("\n", $text) as $line) {
        $section->addText(trim($line), 'letterText', ['alignment' => 'both'], 'tight');
    }

    $temp = tempnam(sys_get_temp_dir(), 'letter') . '.docx';
    IOFactory::createWriter($phpWord, 'Word2007')->save($temp);

    header("Content-Description: File Transfer");
    header('Content-Disposition: attachment; filename="Application_Letter.docx"');
    header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
    header('Content-Length: ' . filesize($temp));
    readfile($temp);
    unlink($temp);
    exit;
}

// === ACTION: ANALYZE CV ===
if ($action === 'analyze-cv') {
    $resumeText = '';

    if (!isset($_FILES['cvFile']) || $_FILES['cvFile']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['feedback' => 'Error uploading file.']);
        exit;
    }

    $file = $_FILES['cvFile']['tmp_name'];
    $fileType = mime_content_type($file);

    function endsWith($haystack, $needle) {
        return substr($haystack, -strlen($needle)) === $needle;
    }

    if (endsWith($_FILES['cvFile']['name'], '.docx')) {
        try {
            $phpWord = \PhpOffice\PhpWord\IOFactory::load($file);
            foreach ($phpWord->getSections() as $section) {
                foreach ($section->getElements() as $element) {
                    if (method_exists($element, 'getText')) {
                        $resumeText .= $element->getText() . "\n";
                    }
                }
            }
        } catch (Exception $e) {
            echo json_encode(['feedback' => 'Error reading .docx: ' . $e->getMessage()]);
            exit;
        }
    } elseif (endsWith($_FILES['cvFile']['name'], '.txt')) {
        $resumeText = file_get_contents($file);
    } else {
        echo json_encode(['feedback' => 'Unsupported file type.']);
        exit;
    }

    // === Step 1: Gemini Analysis Prompt ===
    $promptFeedback = "You are an expert HR professional and resume coach. Provide constructive feedback on the following resume. Format the response using HTML tags only (<h3>, <p>, <ul>, <li>, <strong>, <em>), and avoid Markdown. Your response must include:
        <h3>Executive Summary</h3>
        <h3>Strengths</h3>
        <h3>Missing Sections & Critical Gaps</h3>
        <h3>Targeted Improvement Suggestions</h3>
        <h3>Next Steps.</h3>

        Resume Content: " . $resumeText;

    $feedback = callGeminiAPI($promptFeedback);
    $feedback = str_replace(['```html', '```'], '', $feedback);

    // === Step 2: Gemini Match Score Prompt (optional) ===
    $score = null;
    if (!empty($_POST['jobDescription'])) {
        $jobDescription = $_POST['jobDescription'];

        $promptScore = "You are a recruitment expert. Compare the following resume and job description.
        Provide only a numeric Match Score (0-100) on the first line.
        Then on the second line, provide one short paragraph explaining the reasoning.

        Resume: ".$resumeText ."Job Description: ". $jobDescription;

        $rawScoreResponse = callGeminiAPI($promptScore);

        // Split score and explanation
        $scoreLines = explode("\n", trim($rawScoreResponse));
        $scoreValue = trim($scoreLines[0]); // First line should be the score
        $scoreExplanation = implode("\n", array_slice($scoreLines, 1)); // Rest of it

        // Validate score is numeric
        if (is_numeric($scoreValue)) {
            $score = [
                'value' => (int)$scoreValue,
                'explanation' => $scoreExplanation
            ];
        }
    }
    echo json_encode([
        'feedback' => $feedback ?: 'Error: No response from Gemini.',
        'raw' => $resumeText,
        'score' => $score // this is either null or an array with value + explanation
    ]);
    exit;
}