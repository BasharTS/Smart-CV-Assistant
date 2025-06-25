<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Smart CV Assistant - Home</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        body {
            padding-top: 70px;
        }
        .hero-section {
            background: #f8f9fa;
            padding: 60px 20px;
            text-align: center;
        }
        .feature-card {
            transition: transform 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">Smart CV Assistant</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="../create_cv.php">Create CV</a></li>
                <li class="nav-item"><a class="nav-link" href="../analyze_cv.php">Analyze CV</a></li>
                <li class="nav-item"><a class="nav-link" href="../about.php">About</a></li>
                <li class="nav-item"><a class="nav-link" href="../contact.php">Contact</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <h1 class="display-5 fw-bold">Welcome to Smart CV Assistant</h1>
        <p class="lead">Create professional CVs and get expert analysis powered by AI.</p>
        <a href="../create_cv.php" class="btn btn-primary btn-lg me-2">Start Building</a>
        <a href="../analyze_cv.php" class="btn btn-outline-secondary btn-lg">Analyze My CV</a>
    </div>
</section>

<!-- Feature Cards Section -->
<section class="py-5">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-4 mb-4">
                <div class="card feature-card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">CV Maker</h5>
                        <p class="card-text">Quickly build a professional CV with our dynamic and user-friendly form.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card feature-card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">AI Tailored Output</h5>
                        <p class="card-text">Provide a job description and get a personalized CV or cover letter tailored to it.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card feature-card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">CV Analyzer</h5>
                        <p class="card-text">Upload your CV to receive detailed, AI-driven feedback for improvement.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Bootstrap 5 JS CDN -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>