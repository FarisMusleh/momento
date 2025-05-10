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
    <title>Terms & Conditions</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
   <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Include Bootstrap Icons if not already -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Roboto:wght@300;500;700&family=Dancing+Script&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
	
    <!-- CUSTOM CSS -->
    <link rel="stylesheet" href="css/header.css">

    <style>
        :root {
            --primary-color: #1a3b5d;
            --primary-light: #e8f0f8;
            --secondary-color: #3a7bd5;
            --accent-color: #e84c3d;
            --neutral-dark: #2d3748;
            --neutral-medium: #718096;
            --neutral-light: #edf2f7;
            --success-color: #38a169;
            --warning-color: #f6ad55;
            --border-radius: 8px;
            --box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
            --font-heading: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            --font-body: 'Inter', 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }

        body {
            font-family: var(--font-body);
            line-height: 1.8;
            margin: 0;
            padding: 0;
            color: var(--neutral-dark);
            background-color:rgb(185, 194, 201);
        }

        .wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .container {
            max-width: 1140px;
            margin: 0 auto;
            padding: 0 24px;
            width: 100%;
            box-sizing: border-box;
        }

        .main-content {
            flex: 1;
            padding: 40px 0 80px;
        }

        .document-header {
            margin-bottom: 30px;
            text-align: center;
        }

        h1 {
            color: var(--primary-color);
            font-family: var(--font-heading);
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .last-updated {
            color: var(--neutral-medium);
            font-size: 14px;
        }

        .sections-container {
            display: grid;
            grid-template-columns: 240px 1fr;
            gap: 32px;
        }

        .sidebar {
            position: sticky;
            top: 100px;
            height: fit-content;
            padding: 24px;
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
        }

        .sidebar-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .sidebar-title i {
            color: var(--secondary-color);
        }

        .toc {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .toc li {
            margin-bottom: 12px;
        }

        .toc a {
            display: block;
            padding: 8px 12px;
            color: var(--neutral-dark);
            text-decoration: none;
            border-radius: var(--border-radius);
            transition: var(--transition);
            font-size: 14px;
        }

        .toc a:hover,
        .toc a.active {
            background-color: var(--primary-light);
            color: var(--primary-color);
        }

        .toc a.active {
            font-weight: 500;
            border-left: 3px solid var(--secondary-color);
        }

        .content-area {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
        }

        .section {
            padding: 32px;
            border-bottom: 1px solid #edf2f7;
        }

        .section:last-child {
            border-bottom: none;
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
        }

        .section-number {
            background: var(--secondary-color);
            color: white;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 16px;
            flex-shrink: 0;
        }

        h2 {
            color: var(--primary-color);
            font-family: var(--font-heading);
            font-size: 24px;
            font-weight: 600;
            margin: 0;
        }

        .term-item {
            margin-bottom: 24px;
            padding-left: 20px;
            border-left: 2px solid #edf2f7;
        }

        .term-item:last-child {
            margin-bottom: 0;
        }

        .term-item:hover {
            border-left-color: var(--secondary-color);
        }

        .term-title {
            font-family: var(--font-heading);
            font-weight: 600;
            margin-bottom: 12px;
            color: var(--neutral-dark);
            font-size: 18px;
        }

        .term-content {
            color: var(--neutral-medium);
        }

        .highlight {
            background-color: rgba(58, 123, 213, 0.1);
            padding: 2px 4px;
            border-radius: 3px;
            font-weight: 500;
            color: var(--secondary-color);
        }

        .legal-reference {
            font-size: 13px;
            color: var(--neutral-medium);
            margin-top: 12px;
            padding: 8px 12px;
            background-color: var(--neutral-light);
            border-radius: var(--border-radius);
        }

        .legal-reference a {
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .legal-reference a:hover {
            text-decoration: underline;
        }

        ul {
            padding-left: 20px;
            margin: 12px 0;
        }

        ul li {
            margin-bottom: 8px;
        }

        .button-container {
            margin-top: 32px;
            display: flex;
            justify-content: center;
            gap: 12px;
        }

        .button {
            padding: 12px 20px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: var(--border-radius);
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            transition: var(--transition);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .button:hover {
            background-color: var(--secondary-color);
        }

        .button.secondary {
            background-color: white;
            color: var(--primary-color);
            border: 1px solid #ddd;
        }

        .button.secondary:hover {
            background-color: var(--neutral-light);
            border-color: var(--neutral-medium);
        }

        .footer {
            background-color: white;
            border-top: 1px solid #e2e8f0;
            padding: 40px 0;
        }

        .footer-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
            text-align: center;
        }

        .footer-links {
            display: flex;
            gap: 24px;
            margin-bottom: 8px;
        }

        .footer-links a {
            color: var(--secondary-color);
            text-decoration: none;
            font-size: 14px;
        }

        .footer-links a:hover {
            text-decoration: underline;
        }

        .copyright {
            color: var(--neutral-medium);
            font-size: 14px;
        }

        .industry-selector {
            position: fixed;
            bottom: 24px;
            right: 24px;
            display: flex;
            align-items: center;
            background-color: white;
            padding: 12px 16px;
            border-radius: 100px;
            box-shadow: var(--box-shadow);
            z-index: 10;
        }


        .theme-legal {
             --primary-color: #1e40af;
            --secondary-color: #3b82f6;
            --accent-color: #ef4444;
        }
        /* Mobile styles */
        @media (max-width: 768px) {
            .sections-container {
                grid-template-columns: 1fr;
            }

            .sidebar {
                position: relative;
                top: 0;
                margin-bottom: 24px;
            }

            .section {
                padding: 24px 20px;
            }

            h1 {
                font-size: 28px;
            }

            h2 {
                font-size: 20px;
            }

            .industry-selector {
                bottom: 16px;
                right: 16px;
                padding: 8px 12px;
            }
        }

        /* Print styles */
        @media print {

            .header,
            .footer,
            .sidebar,
            .industry-selector,
            .button-container {
                display: none;
            }

            .sections-container {
                grid-template-columns: 1fr;
            }

            .content-area {
                box-shadow: none;
            }

            .section {
                page-break-inside: avoid;
                border-bottom: none;
            }
        }
    </style>
</head>

<body class="theme-legal">
   <?php require('header.php'); ?>
    <div class="wrapper">
        <main class="main-content">
            <div class="container">
                <div id="en-content">
                    <div class="document-header" style="margin-top: 40px;">
                        <h1>Terms & Conditions</h1>
                        <div class="last-updated">Last updated: May 10, 2025</div>
                    </div>

                    <div class="sections-container">
                        <aside class="sidebar">
                            <div class="sidebar-title">
                                <i class="fas fa-list-ul"></i> Contents
                            </div>
                            <ul class="toc">
                                <li><a href="#section-1" class="active">Content Liability</a></li>
                                <li><a href="#section-2">Intellectual Property Rights</a></li>
                                <li><a href="#section-3">Legal Compliance</a></li>
                                <li><a href="#section-4">Account Management</a></li>
                                <li><a href="#section-5">Data Protection</a></li>
                                <li><a href="#section-6">Policy Amendments</a></li>
                            </ul>
                        </aside>

                        <div class="content-area">
                            <section id="section-1" class="section">
                                <div class="section-header">
                                    <div class="section-number">1</div>
                                    <h2>Content Liability</h2>
                                </div>
                                <div class="term-item">
                                    <div class="term-title">User Responsibility</div>
                                    <div class="term-content">
                                        <p>Users assume full <span class="highlight">legal liability</span> for all
                                            content uploaded to the
                                            platform, including but not limited to images, videos, and textual content.
                                            This includes
                                            responsibility for any third-party rights infringement.</p>
                                        <div class="legal-reference">Reference: Jordanian Cybercrime Law No. 27 of 2015,
                                            Article 3</div>
                                    </div>
                                </div>
                                <div class="term-item">
                                    <div class="term-title">Platform Exemption</div>
                                    <div class="term-content">
                                        <p>Company Name disclaims all <span class="highlight">civil and criminal
                                                liability</span> for
                                            user-generated content that violates applicable laws or regulations. The
                                            platform operates as a
                                            hosting service under Jordanian law.</p>
                                    </div>
                                </div>
                                <div class="term-item">
                                    <div class="term-title">Prohibited Content</div>
                                    <div class="term-content">
                                        <p>Users are expressly prohibited from uploading content that includes:</p>
                                        <ul>
                                            <li><span class="highlight">Privacy violations</span> under Personal Data
                                                Protection Act</li>
                                            <li><span class="highlight">Copyright infringement</span> per Copyright Law
                                                No. 22 of 1992</li>
                                            <li>Hate speech, discrimination, or incitement to violence</li>
                                            <li>Obscene or sexually explicit material</li>
                                            <li>Unauthorized personal images or biometric data</li>
                                        </ul>
                                    </div>
                                </div>
                            </section>

                            <section id="section-2" class="section">
                                <div class="section-header">
                                    <div class="section-number">2</div>
                                    <h2>Intellectual Property Rights</h2>
                                </div>
                                <div class="term-item">
                                    <div class="term-title">Content Ownership</div>
                                    <div class="term-content">
                                        <p>All content uploaded without prior written agreement shall be considered
                                            <span class="highlight">proprietary content</span> of Company Name, subject
                                            to the user's underlying
                                            rights. Company Name reserves all derivative rights.
                                        </p>
                                    </div>
                                </div>
                                <div class="term-item">
                                    <div class="term-title">Usage License</div>
                                    <div class="term-content">
                                        <p>By uploading content, users grant Company Name a <span
                                                class="highlight">perpetual, worldwide,
                                                non-exclusive, royalty-free, sublicensable license</span> to use,
                                            reproduce, modify, and
                                            distribute the content for platform operations and marketing.</p>
                                        <div class="legal-reference">Reference: Jordanian Copyright Law, Articles 7-9
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <section id="section-3" class="section">
                                <div class="section-header">
                                    <div class="section-number">3</div>
                                    <h2>Legal Compliance</h2>
                                </div>
                                <div class="term-item">
                                    <div class="term-title">Jurisdictional Requirements</div>
                                    <div class="term-content">
                                        <p>Users must comply with all <span class="highlight">Jordanian
                                                legislation</span>, including but
                                            not limited to:</p>
                                        <ul>
                                            <li>Cybercrime Law No. 27 of 2015</li>
                                            <li>Telecommunications Law No. 13 of 1995</li>
                                            <li>Personal Data Protection Act</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="term-item">
                                    <div class="term-title">Law Enforcement Cooperation</div>
                                    <div class="term-content">
                                        <p>Company Name will <span class="highlight">fully cooperate</span> with
                                            authorized government agencies
                                            and comply with all lawful requests for user information pursuant to
                                            Jordanian legal procedures.
                                        </p>
                                    </div>
                                </div>
                            </section>

                            <section id="section-4" class="section">
                                <div class="section-header">
                                    <div class="section-number">4</div>
                                    <h2>Account Management</h2>
                                </div>
                                <div class="term-item">
                                    <div class="term-title">Content Moderation</div>
                                    <div class="term-content">
                                        <p>Company Name reserves the unilateral right to <span class="highlight">remove
                                                content or suspend
                                                accounts</span> without prior notice for violations of these Terms or
                                            applicable law.
                                            Appeals may be submitted via our contact form.</p>
                                    </div>
                                </div>
                            </section>

                            <section id="section-5" class="section">
                                <div class="section-header">
                                    <div class="section-number">5</div>
                                    <h2>Data Protection</h2>
                                </div>
                                <div class="term-item">
                                    <div class="term-title">Privacy Commitment</div>
                                    <div class="term-content">
                                        <p>User data is processed in accordance with our <span class="highlight">Privacy
                                                Policy</span> and
                                            applicable data protection laws. Data may be disclosed when legally required
                                            by competent
                                            authorities.</p>
                                    </div>
                                </div>
                                <div class="term-item">
                                    <div class="term-title">Right to Erasure</div>
                                    <div class="term-content">
                                        <p>Users may request <span class="highlight">permanent deletion</span> of
                                            personal data pursuant to
                                            Article 15 of Jordan's Personal Data Protection Act, subject to legal
                                            retention requirements.
                                        </p>
                                    </div>
                                </div>
                            </section>

                            <section id="section-6" class="section">
                                <div class="section-header">
                                    <div class="section-number">6</div>
                                    <h2>Policy Amendments</h2>
                                </div>
                                <div class="term-item">
                                    <div class="term-title">Modification Rights</div>
                                    <div class="term-content">
                                        <p>These Terms may be amended at Company Name's discretion. Material changes
                                            will be notified via
                                            platform notice or email. <span class="highlight">Continued use constitutes
                                                acceptance</span> of
                                            revised Terms.</p>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>


                </div>
            </div>
        </main>

        <footer class="footer">
            <div class="container">
                <div class="footer-content">
                    <div class="copyright">
                        © 2025 <span class="company-name">Momento</span>. All rights reserved.
                    </div>
                    <div class="company-location">
                        Registered in the Hashemite Kingdom of Jordan
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <script>

        // Active section highlighting
        document.addEventListener('DOMContentLoaded', function () {
            const sections = document.querySelectorAll('.section');
            const navLinks = document.querySelectorAll('.toc a');

            const observerOptions = {
                root: null,
                rootMargin: '0px',
                threshold: 0.5
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const activeSection = entry.target.id;
                        navLinks.forEach(link => {
                            if (link.getAttribute('href') === '#' + activeSection) {
                                link.classList.add('active');
                            } else {
                                link.classList.remove('active');
                            }
                        });
                    }
                });
            }, observerOptions);

            sections.forEach(section => {
                observer.observe(section);
            });

            // Smooth scrolling for navigation links
            navLinks.forEach(link => {
                link.addEventListener('click', function (e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href');
                    const targetElement = document.querySelector(targetId);

                    window.scrollTo({
                        top: targetElement.offsetTop - 100,
                        behavior: 'smooth'
                    });

                    navLinks.forEach(link => link.classList.remove('active'));
                    this.classList.add('active');
                });
            });
        });
    </script>
</body>

</html>