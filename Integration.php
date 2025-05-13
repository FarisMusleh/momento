<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'pdo.php';
$data = $_SESSION['data'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Global Media Hub | Integration Options</title>
    <meta name="description"
        content="Access our content through API integration, RSS feeds, or direct image downloads to incorporate into your workflow.">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Open+Sans:wght@400;600&display=swap"
        rel="stylesheet">
     <!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Roboto:wght@300;500;700&family=Dancing+Script&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
	
    <!-- CUSTOM CSS -->
    <link rel="stylesheet" href="css/header.css">

    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #1a2b45;
            --accent-journalism: #3498db;
            --accent-marketing: #e74c3c;
            --accent-education: #2ecc71;
            --accent-research: #9b59b6;
            --light-accent: #e8f4fc;
            --text-color: #333;
            --light-text: #7f8c8d;
            --white: #ffffff;
            --light-bg: #f8fafc;
            --border-radius: 8px;
            --box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease-in-out;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            background-color: var(--light-bg);
            color: var(--text-color);
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            color: var(--primary-color);
        }

        header {
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--primary-color) 100%);
            color: var(--white);
            padding: 100px 0 80px;
            text-align: center;
            margin-bottom: 60px;
            position: relative;
            overflow: hidden;
        }

        header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0.6;
            background-color: #25303b;
            background-size: cover;
        }

        header .container {
            position: relative;
            z-index: 1;
        }

        header h1 {
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 20px;
            line-height: 1.3;
        }

        header p {
            font-size: 1.25rem;
            color: rgba(255, 255, 255, 0.85);
            max-width: 700px;
            margin: 0 auto 30px;
        }

        .btn-custom {
            border-radius: var(--border-radius);
            font-size: 1rem;
            font-weight: 500;
            padding: 12px 30px;
            border: none;
            transition: var(--transition);
            letter-spacing: 0.5px;
            text-transform: uppercase;
            font-family: 'Montserrat', sans-serif;
        }


        .btn-journalism {
            background-color: var(--accent-journalism);
            color: var(--white);
        }

        .btn-marketing {
            background-color: var(--accent-marketing);
            color: var(--white);
        }

        .btn-education {
            background-color: var(--accent-education);
            color: var(--white);
        }

        .btn-research {
            background-color: var(--accent-research);
            color: var(--white);
        }

        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            opacity: 0.9;
        }

        .btn-outline-custom {
            background-color: transparent;
            border: 2px solid;
        }

        .btn-outline-journalism {
            color: var(--accent-journalism);
            border-color: var(--accent-journalism);
        }

        .btn-outline-marketing {
            color: var(--accent-marketing);
            border-color: var(--accent-marketing);
        }

        .btn-outline-education {
            color: var(--accent-education);
            border-color: var(--accent-education);
        }

        .btn-outline-research {
            color: var(--accent-research);
            border-color: var(--accent-research);
        }

        .btn-outline-custom:hover {
            color: var(--white);
        }

        .btn-outline-journalism:hover {
            background-color: var(--accent-journalism);
        }

        .btn-outline-marketing:hover {
            background-color: var(--accent-marketing);
        }

        .btn-outline-education:hover {
            background-color: var(--accent-education);
        }

        .btn-outline-research:hover {
            background-color: var(--accent-research);
        }

        .professional-card {
            background-color: var(--white);
            padding: 30px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            height: 100%;
            border-top: 4px solid;
            position: relative;
            overflow: hidden;
        }

        .professional-card.api {
            border-top-color: var(--accent-journalism);
        }

        .professional-card.rss {
            border-top-color: var(--accent-education);
        }

        .professional-card.download {
            border-top-color: var(--accent-research);
        }

        .professional-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.03) 0%, rgba(255, 255, 255, 0) 100%);
            z-index: 0;
            opacity: 0;
            transition: var(--transition);
        }

        .professional-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.12);
        }

        .professional-card:hover::before {
            opacity: 1;
        }

        .professional-card h3 {
            font-size: 1.5rem;
            color: var(--primary-color);
            margin-bottom: 15px;
            position: relative;
            z-index: 1;
        }

        .professional-card p {
            font-size: 1rem;
            color: var(--light-text);
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }

        .professional-card .icon {
            font-size: 2.5rem;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }

        .professional-card.api .icon {
            color: var(--accent-journalism);
        }

        .professional-card.rss .icon {
            color: var(--accent-education);
        }

        .professional-card.download .icon {
            color: var(--accent-research);
        }

        .section-title {
            font-size: 2.2rem;
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 50px;
            text-align: center;
            position: relative;
            padding-bottom: 15px;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background-color: var(--accent-journalism);
            border-radius: 2px;
        }

        .section-intro {
            font-size: 1.1rem;
            color: var(--light-text);
            max-width: 800px;
            margin: 0 auto 60px;
            text-align: center;
            line-height: 1.8;
        }

        .feature-box {
            text-align: center;
            padding: 30px 20px;
            margin-bottom: 30px;
        }

        .feature-box .icon {
            font-size: 2.5rem;
            color: var(--accent-journalism);
            margin-bottom: 20px;
        }

        .feature-box h4 {
            font-size: 1.3rem;
            margin-bottom: 15px;
        }

        .feature-box p {
            color: var(--light-text);
        }

        .integration-tabs {
            margin-bottom: 40px;
        }


        .social-links a {
            display: inline-block;
            width: 40px;
            height: 40px;
            background-color: rgba(255, 255, 255, 0.1);
            color: var(--white);
            border-radius: 50%;
            text-align: center;
            line-height: 40px;
            margin-right: 10px;
            transition: var(--transition);
        }

        .social-links a:hover {
            background-color: var(--accent-journalism);
            color: var(--white);
            transform: translateY(-3px);
        }

        .container {
            max-width: 1200px;
            margin: auto;
            padding: 0 20px;
        }

        .mb-60 {
            margin-bottom: 60px;
        }

        .integration-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 15px;
        }

        .badge-api {
            background-color: rgba(52, 152, 219, 0.1);
            color: var(--accent-journalism);
        }

        .badge-rss {
            background-color: rgba(46, 204, 113, 0.1);
            color: var(--accent-education);
        }

        .badge-download {
            background-color: rgba(155, 89, 182, 0.1);
            color: var(--accent-research);
        }

        pre.code-block {
            background-color: #f8f9fa;
            border-radius: var(--border-radius);
            padding: 15px;
            overflow-x: auto;
            font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            font-size: 0.9rem;
            color: #212529;
            margin-bottom: 1rem;
            border-left: 4px solid var(--accent-journalism);
        }

        .feature-list li {
            margin-bottom: 10px;
        }

        .api-endpoint {
            background-color: rgba(52, 152, 219, 0.1);
            padding: 8px 12px;
            border-radius: 4px;
            font-family: monospace;
            color: var(--accent-journalism);
            font-weight: 600;
        }

        .feed-url {
            background-color: rgba(46, 204, 113, 0.1);
            padding: 8px 12px;
            border-radius: 4px;
            font-family: monospace;
            color: var(--accent-education);
            font-weight: 600;
        }

        .download-format {
            background-color: rgba(155, 89, 182, 0.1);
            padding: 8px 12px;
            border-radius: 4px;
            font-family: monospace;
            color: var(--accent-research);
            font-weight: 600;
        }

        .code-example {
            margin-top: 20px;
        }

        .code-example .code-tabs {
            border-bottom: 1px solid #dee2e6;
            margin-bottom: 15px;
        }

        .code-example .code-tabs .nav-link {
            padding: 8px 15px;
            font-size: 0.9rem;
            color: var(--text-color);
            border: none;
            border-bottom: 2px solid transparent;
            transition: var(--transition);
        }

        .code-example .code-tabs .nav-link.active {
            color: var(--accent-journalism);
            background-color: transparent;
            border-bottom: 2px solid var(--accent-journalism);
        }

        @media (max-width: 768px) {
            header {
                padding: 80px 0 60px;
            }

            header h1 {
                font-size: 2.2rem;
            }

            header p {
                font-size: 1.1rem;
            }

            .section-title {
                font-size: 1.8rem;
            }

            .integration-tabs .nav-link {
                padding: 8px 15px;
                font-size: 0.9rem;
            }
        }

        h1,
        h2 {
            color: #2c3e50;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }

        code {
            background-color: #f4f4f4;
            padding: 2px 5px;
            border-radius: 4px;
        }

        pre {
            background-color: #f4f4f4;
            padding: 10px;
            border-left: 3px solid #ccc;
            overflow-x: auto;
        }
    </style>
</head>

<body>

<?php require("header.php"); ?>  
    <main class="py-5">
        <div class="container">
            <section class="mb-60">
                <h2 class="section-title">Seamless Access Options</h2>
                <p class="section-intro">Choose from multiple integration methods to incorporate our visual content
                    directly into your workflow, systems, and applications.</p>

                <div class="row">
                    <div class="col-md-4">
                        <div class="feature-box">
                            <div class="icon">
                                <i class="fas fa-code"></i>
                            </div>
                            <h4>API Integration</h4>
                            <p>Programmatic access with custom endpoints for developers.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-box">
                            <div class="icon">
                                <i class="fas fa-rss"></i>
                            </div>
                            <h4>RSS Feeds</h4>
                            <p>Automated content delivery for your specific needs.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-box">
                            <div class="icon">
                                <i class="fas fa-download"></i>
                            </div>
                            <h4>Direct Download</h4>
                            <p>Immediate access to high-quality images in various formats.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section id="integrations" class="mb-60">
                <h2 class="section-title">Integration Methods</h2>
                <p class="section-intro">Select your preferred integration method for detailed information and
                    implementation guides.</p>
                <div class="tab-content" id="integration-tabContent">
                    <div class="tab-pane fade show active" id="api" role="tabpanel">
                        <div class="row row-cols-1 row-cols-md-3 g-4">
                            <div class="col">
                                <div class="professional-card api">
                                    <span class="integration-badge badge-api">API</span>
                                    <div class="icon">
                                        <i class="fas fa-plug"></i>
                                    </div>
                                    <h3>RESTful API</h3>
                                    <p>Full-featured REST API with JSON responses for easy integration with any
                                        platform.</p>
                                    <!--  <a href="/api/documentation" class="btn-custom btn-outline-journalism">View API
                                        Docs</a> -->
                                </div>
                            </div>
                            <div class="col">
                                <div class="professional-card rss">
                                    <span class="integration-badge badge-rss">RSS</span>
                                    <div class="icon">
                                        <i class="fas fa-tags"></i>
                                    </div>
                                    <h3>Category Feeds</h3>
                                    <p>Topic-specific image feeds tailored to different professional
                                        requirements.</p>
                                    <!--  <a href="/rss/categories" class="btn-custom btn-outline-education">Browse
                                        Categories</a> -->
                                </div>
                            </div>

                            <div class="col">
                                <div class="professional-card download">
                                    <span class="integration-badge badge-download">Download</span>
                                    <div class="icon">
                                        <i class="fas fa-file-image"></i>
                                    </div>
                                    <h3>Image Formats</h3>
                                    <p>Download images in JPEG, PNG, and TIFF formats for various
                                        applications.</p>
                                    <!--  <a href="/download/formats" class="btn-custom btn-outline-research">View
                                        Formats</a> -->
                                </div>
                            </div>
                        </div>
                        <div class="card mt-5 ">
                            <div class="card-body">
                                <h4 class="card-title">API Quick Start</h4>
                                <p class="card-text">Get started with our API in minutes. Here's a basic example:</p>

                                <div class="api-endpoint mb-3">
                                    <code>GET http://localhost/momento/api_categories.php</code>
                                </div>
                                <h2 style="margin-top: 30px;">🧾 Query Parameters</h2>
                                <table style="margin-top: 20px; margin-bottom: 5px;">
                                    <thead>
                                        <tr>
                                            <th>Parameter</th>
                                            <th>Type</th>
                                            <th>Required</th>
                                            <th>Default</th>
                                            <th>Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><code>category</code></td>
                                            <td>string or array</td>
                                            <td>No</td>
                                            <td>"war"</td>
                                            <td>Search category. Can be a single string or array.</td>
                                        </tr>
                                        <tr>
                                            <td><code>page</code></td>
                                            <td>integer</td>
                                            <td>No</td>
                                            <td>1</td>
                                            <td>Page number for pagination.</td>
                                        </tr>
                                        <tr>
                                            <td><code>per_page</code></td>
                                            <td>integer</td>
                                            <td>No</td>
                                            <td>10</td>
                                            <td>Number of results per page (maximum 50).</td>
                                        </tr>
                                    </tbody>
                                </table>

                                <h2 style="margin-top: 30px;">📦 Sample JSON Response</h2>
                                <pre><code>{
                            "original_query": "war",
                            "resolved_categories": ["war", "battle", "conflict"],
                            "page": 1,
                            "per_page": 10,
                            "count": 3,
                            "images": [
                                 {
                                    "id": 12,
                                    "file_name": "warrior.jpg",
                                    "label": ["war", "battle"],
                                    "likes": 102,
                                    "created_at": "2025-05-12 15:23:54",
                                    "url": "/momento/uploads/Images/warrior.jpg"
                                    },
                                    {
                                    "id": 13,
                                    "file_name": "explosion.jpg",
                                    "label": ["war", "conflict"],
                                    "likes": 99,
                                    "created_at": "2025-05-11 12:45:10",
                                    "url": "/momento/uploads/Images/explosion.jpg"
                                    }
                                ]
                            }
</code></pre>