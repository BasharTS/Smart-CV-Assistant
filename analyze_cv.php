<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Analyze CV - Smart CV Assistant</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom Styles -->
    <style>
        body { padding-top: 70px; }
        .form-section { margin-bottom: 2rem; }
        #analysis-result { margin-top: 2rem; }
        pre { background-color: #f8f9fa; padding: 1rem; overflow-x: auto; border: 1px solid #dee2e6; }
        .loader { display: none; margin-top: 1rem; }
        pre {
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        #raw-content {
            max-height: 300px;
            overflow-y: auto;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">Smart CV Assistant</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="public/index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="create_cv.php">Create CV</a></li>
                <li class="nav-item"><a class="nav-link active" href="analyze_cv.php">Analyze CV</a></li>
                <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Main Container -->
<div class="container mt-4">
    <h2 class="text-center">Analyze Your CV</h2>
    <p class="text-center">Upload a .docx or .txt file and receive structured feedback to improve your resume.</p>

    <form id="analyzeForm" enctype="multipart/form-data">
        <div class="form-section mb-3">
            <label class="form-label">Upload CV File</label>
            <!-- Fixed input name -->
            <input type="file" class="form-control" name="cvFile" accept=".docx,.txt" required>
        </div>
        <div class="form-section mt-3">
          <label class="form-label">Optional Job Description (If you want to get Match Score)</label>
          <textarea class="form-control" name="jobDescription" rows="5" placeholder="Paste the job description to assess match..."></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Analyze CV</button>
        <!-- Loader hidden initially -->
        <!-- <div class="loader text-primary mt-2" id="loader" style="display: none;">Analyzing... Please wait.</div> -->
        <div id="loader" class="mt-3 text-center" style="display: none;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Analyzing...</span>
            </div>
            <div class="text-muted mt-2">Analyzing... Please wait.</div>
        </div>
    </form>

    <!-- Hide result block until analysis completes -->
    <div id="analysis-result" class="mt-4" style="display: none;">
        <h4>Extracted Resume Content</h4>
        <pre id="raw-content" class="border p-3 bg-light">Your uploaded resume text will appear here...</pre>
        <div id="match-score-box" class="mt-4" style="display:none;">
            <h4>Job Match Score</h4>
            <div class="progress" style="height: 25px;">
                <div id="match-score-bar" class="progress-bar progress-bar-striped" role="progressbar" style="width: 0%">0%</div>
            </div>
            <div id="match-score-text" class="mt-2 small text-muted"></div>

        </div>
        <h4>AI Feedback</h4>
        <div id="ai-feedback" class="border p-3 bg-light">Detailed feedback will be shown here...</div>
    </div>
</div>


<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    /*document.getElementById('analyzeForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);
        formData.append('action', 'analyze-cv');

        const loader = document.getElementById('loader');
        loader.style.display = 'block';

        fetch('api.php', {
            method: 'POST',
            body: formData
        }).then(response => response.json())
          .then(data => {
              loader.style.display = 'none';
              document.getElementById('analysis-result').style.display = 'block';
              document.getElementById('raw-content').textContent = data.raw || '❌ Unable to extract content.';
              document.getElementById('ai-feedback').innerHTML = data.feedback || '<p>❌ No feedback received.</p>';
          }).catch(error => {
              loader.style.display = 'none';
              alert('Something went wrong while analyzing the CV.');
              console.error(error);
          });
    });*/
    document.getElementById('analyzeForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);
        formData.append('action', 'analyze-cv');

        document.getElementById('loader').style.display = 'block';
        document.getElementById('match-score-box').style.display = 'none';

        fetch('api.php', {
            method: 'POST',
            body: formData
        }).then(response => { if (!response.ok) throw new Error('Network response not ok');
            return response.json(); // this line throws if response isn't JSON
        })
        .then(data => {
            document.getElementById('loader').style.display = 'none';
            document.getElementById('raw-content').textContent = data.raw || 'Unable to extract content.';
            document.getElementById('ai-feedback').innerHTML = data.feedback || '<p>No feedback received.</p>';

            // Conditionally show match score only if it's a proper object
                if (data.score && typeof data.score === 'object') {
                    const scoreValue = parseInt(data.score.value);
                    const explanation = data.score.explanation;

                    const bar = document.getElementById('match-score-bar');
                    bar.style.width = scoreValue + '%';
                    bar.textContent = scoreValue + '%';

                    bar.classList.remove('bg-success', 'bg-warning', 'bg-danger');
                    bar.classList.add(
                        scoreValue >= 80 ? 'bg-success' :
                        scoreValue >= 50 ? 'bg-warning' : 'bg-danger'
                    );

                    document.getElementById('match-score-text').innerHTML =
                        `<strong>AI Score Explanation:</strong><br>${explanation}`;
                    document.getElementById('match-score-box').style.display = 'block';
                } else {
                    // explicitly hide if no score (null or undefined)
                    document.getElementById('match-score-box').style.display = 'none';
                    document.getElementById('analysis-result').style.display = 'block';
                }

        }).catch(error => {
            document.getElementById('loader').style.display = 'none';
            alert('Something went wrong while analyzing the CV.');
            console.error(error);
        });
    });

</script>


</body>
</html>
