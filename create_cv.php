<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create CV - Smart CV Assistant</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
                <li class="nav-item"><a class="nav-link active" href="create_cv.php">Create CV</a></li>
                <li class="nav-item"><a class="nav-link" href="analyze_cv.php">Analyze CV</a></li>
                <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4 mb-5" style="padding-top: 55px; padding-left: 50px; padding-right: 50px;">
    <h1 class="mb-4" style="text-align: center;">Create Your CV</h1>
    <!-- Main Form -->
    <form id="cvForm">
        <h4>Personal Information</h4>
        <div class="row mb-3">
            <div class="col-md-6"><input type="text" id="name" class="form-control" placeholder="Full Name" required></div>
            <div class="col-md-6"><input type="text" id="address" class="form-control" placeholder="Home Address" required></div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3"><input type="text" id="phone" class="form-control" placeholder="Phone" required></div>
            <div class="col-md-3"><input type="email" id="email" class="form-control" placeholder="Email" required></div>
            <div class="col-md-3"><input type="url" id="linkedin" class="form-control" placeholder="LinkedIn Profile"></div>
            <div class="col-md-3"><input type="url" id="portfolio" class="form-control" placeholder="Portfolio URL (Optional)"></div>
        </div>
        <div class="row mb-3">
            <div class="col-md-2"><input type="text" id="gender" class="form-control" placeholder="Gender"></div>
            <div class="col-md-2"><input type="text" id="dob" class="form-control" placeholder="Date of Birth"></div>
            <div class="col-md-2"><input type="text" id="lga" class="form-control" placeholder="Local Goverment"></div>
            <div class="col-md-2"><input type="text" id="state" class="form-control" placeholder="state"></div>
            <div class="col-md-2"><input type="text" id="nationality" class="form-control" placeholder="Nationality"></div>
            <div class="col-md-2"><input type="text" id="languages" class="form-control" placeholder="language(s) separated by comma"></div>
        </div>

        <h4>Work Experience</h4>
        <div id="experienceContainer"></div>
        <button type="button" class="btn btn-outline-primary mb-3" onclick="addExperience()">Add Experience</button>

        <h4>Education</h4>
        <div id="educationContainer"></div>
        <button type="button" class="btn btn-outline-primary mb-3" onclick="addEducation()">Add Education</button>

        <h4>Skills</h4>
        <div class="mb-3">
            <textarea id="skills" class="form-control" rows="2" placeholder="Comma-separated list (e.g., Python, HTML, Research)"></textarea>
        </div>

        <h4>Certifications</h4>
        <div class="mb-3">
            <textarea id="certifications" class="form-control" rows="2" placeholder="List certifications, separated by new lines"></textarea>
        </div>

        <h4>Publications</h4>
        <div class="mb-3">
            <textarea id="publications" class="form-control" rows="3" placeholder="List publications, separated by new lines"></textarea>
        </div>

        <h4>Conferences / Workshops</h4>
        <div class="mb-3">
            <textarea id="workshops" class="form-control" rows="3" placeholder="List conferences or workshops, separated by new lines"></textarea>
        </div>

        <h4>Referees</h4>
        <div id="refereeContainer"></div>
        <button type="button" class="btn btn-outline-primary mb-3" onclick="addReferee()">Add Referee</button>

        <h4>Job Description (Optional)</h4>
        <div class="mb-3"><textarea id="jobDescription" class="form-control" rows="4" placeholder="Paste the job description here (optional)"></textarea></div>

        <div class="d-flex gap-3">
            <button type="button" class="btn btn-success" onclick="generateCV()">Generate Tailored CV</button>
            <button type="button" class="btn btn-warning" id="generateLetter" style = "display: none"> Generate Application Letter</button>
        </div>
    </form>
</div>

<script>
    function addExperience() {
        const html = `
        <div class="experience-group mb-3 border p-3 rounded bg-light">
            <input type="text" class="form-control mb-2 job-title" placeholder="Job Title">
            <input type="text" class="form-control mb-2 company-name" placeholder="Company Name">
            <input type="text" class="form-control mb-2 job-duration" placeholder="Duration (e.g., Jan 2020 - Dec 2022)">
            <textarea class="form-control job-description" rows="2" placeholder="Responsibilities / Achievements"></textarea>
            <button type="button" class="btn btn-danger btn-sm mt-2" onclick="this.parentElement.remove()">Remove</button>
        </div>`;
        document.getElementById('experienceContainer').insertAdjacentHTML('beforeend', html);
    }
    function addEducation() {
        const html = `
        <div class="education-group mb-3 border p-3 rounded bg-light">
            <input type="text" class="form-control mb-2 degree" placeholder="Degree">
            <input type="text" class="form-control mb-2 institution" placeholder="Institution">
            <input type="text" class="form-control mb-2 education-duration" placeholder="Duration (e.g., 2018 - 2022)">
            <button type="button" class="btn btn-danger btn-sm mt-2" onclick="this.parentElement.remove()">Remove</button>
        </div>`;
        document.getElementById('educationContainer').insertAdjacentHTML('beforeend', html);
    }
    function addReferee() {
        const html = `
        <div class="referee-group mb-3 border p-3 rounded bg-light">
            <input type="text" class="form-control mb-2 referee-name" placeholder="Name">
            <input type="text" class="form-control mb-2 referee-title" placeholder="Title / Relationship">
            <input type="email" class="form-control mb-2 referee-email" placeholder="Email">
            <input type="text" class="form-control mb-2 referee-phone" placeholder="Phone Number">
            <button type="button" class="btn btn-danger btn-sm mt-2" onclick="this.parentElement.remove()">Remove</button>
        </div>`;
        document.getElementById('refereeContainer').insertAdjacentHTML('beforeend', html);
    }
    function collectData() {
        const personal = {
            name: document.getElementById('name').value,
            address: document.getElementById('address').value,
            phone: document.getElementById('phone').value,
            email: document.getElementById('email').value,
            linkedin: document.getElementById('linkedin').value,
            portfolio: document.getElementById('portfolio').value,
            gender: document.getElementById('gender').value,
            dob: document.getElementById('dob').value,
            lga: document.getElementById('lga').value,
            state: document.getElementById('state').value,
            languages: document.getElementById('languages').value,
            nationality: document.getElementById('nationality').value
        };

        const experience = [];
        document.querySelectorAll('.experience-group').forEach(group => {
            experience.push({
                title: group.querySelector('.job-title').value,
                company: group.querySelector('.company-name').value,
                duration: group.querySelector('.job-duration').value,
                description: group.querySelector('.job-description').value
            });
        });

        const education = [];
        document.querySelectorAll('.education-group').forEach(group => {
            education.push({
                degree: group.querySelector('.degree').value,
                institution: group.querySelector('.institution').value,
                duration: group.querySelector('.education-duration').value
            });
        });

        const referees = [];
        document.querySelectorAll('.referee-group').forEach(group => {
            referees.push({
                name: group.querySelector('.referee-name').value,
                title: group.querySelector('.referee-title').value,
                email: group.querySelector('.referee-email').value,
                phone: group.querySelector('.referee-phone').value
            });
        });

        return {
            action: '',
            personal,
            experience,
            education,
            skills: document.getElementById('skills').value,
            certifications: document.getElementById('certifications').value,
            publications: document.getElementById('publications').value,
            workshops: document.getElementById('workshops').value,
            referees,
            jobDescription: document.getElementById('jobDescription').value
        };
    }
    function generateCV() {
        const data = collectData();
        data.action = 'generate-cv';

        fetch('api.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
        .then(res => res.blob())
        .then(blob => {
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = "Smart-CV-Assistant.docx";
            a.click();
        })
        .catch(err => {
            console.error(err);
            alert("Failed to generate CV.");
        });
    }
    
    function generateLetter() {
        const data = collectData();
        data.action = 'generate-letter';

        fetch('api.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
        .then(res => res.blob())
        .then(blob => {
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = "Application_Letter.docx";
            a.click();
        })
        .catch(err => {
            console.error(err);
            alert("Failed to generate application letter.");
        });
    }
    // Enable/disable application letter button
    document.getElementById('jobDescription').addEventListener('input', function () {
        const letterBtn = document.getElementById('generateLetter');
        letterBtn.style.display = this.value.trim() ? 'inline-block' : 'none';
    });
    document.getElementById('generateLetter').addEventListener('click', generateLetter);

    // Initialize with one field each
    addExperience();
    addEducation();
    addReferee();

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
