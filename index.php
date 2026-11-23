
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Discharge Management System</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            background: #f4f8fc;
            color: #18324b;
        }

        /* Navigation */
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 8%;
            background: white;
            box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        }

        .logo {
            font-size: 22px;
            font-weight: bold;
            color: #087e8b;
        }

        .logo span {
            color: #18324b;
        }

        nav a {
            text-decoration: none;
            color: #087e8b;
            font-weight: 600;
        }

        /* Hero section */
        .hero {
            min-height: 540px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 65px 8%;
            gap: 40px;
            background: linear-gradient(120deg, #e5f7f8, #f4f8fc);
        }

        .hero-content {
            max-width: 600px;
        }

        .tag {
            display: inline-block;
            background: #d2f0ed;
            color: #087e8b;
            padding: 9px 17px;
            border-radius: 25px;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 22px;
        }

        h1 {
            font-size: 46px;
            line-height: 1.2;
            margin-bottom: 20px;
        }

        h1 span {
            color: #087e8b;
        }

        .hero p {
            color: #63788c;
            font-size: 17px;
            line-height: 1.8;
            margin-bottom: 30px;
        }

        .btn {
            display: inline-block;
            background: #087e8b;
            color: white;
            padding: 14px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
        }

        .btn:hover {
            background: #065d68;
            transform: translateY(-2px);
        }

        /* Illustration card */
        .hero-card {
            width: 350px;
            min-height: 300px;
            background: white;
            border-radius: 22px;
            padding: 35px;
            box-shadow: 0 15px 40px rgba(8,126,139,0.12);
            text-align: center;
        }

        .medical-icon {
            font-size: 85px;
            margin: 15px 0 25px;
        }

        .hero-card h2 {
            color: #087e8b;
            margin-bottom: 12px;
        }

        .hero-card p {
            font-size: 14px;
            margin: 0;
        }

        /* Features */
        .features {
            padding: 65px 8%;
            text-align: center;
        }

        .features h2 {
            font-size: 30px;
            margin-bottom: 12px;
        }

        .subtitle {
            color: #718397;
            margin-bottom: 40px;
        }

        .feature-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
        }

        .feature-box {
            background: white;
            padding: 30px 22px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.04);
            transition: 0.3s;
        }

        .feature-box:hover {
            transform: translateY(-7px);
        }

        .feature-box .icon {
            font-size: 40px;
            margin-bottom: 18px;
        }

        .feature-box h3 {
            margin-bottom: 12px;
            color: #087e8b;
        }

        .feature-box p {
            color: #718397;
            line-height: 1.7;
            font-size: 14px;
        }

        /* Footer */
        footer {
            background: #18324b;
            color: white;
            text-align: center;
            padding: 23px;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .hero {
                flex-direction: column;
                text-align: center;
            }

            h1 {
                font-size: 34px;
            }

            .hero-card {
                width: 100%;
                max-width: 350px;
            }

            .feature-container {
                grid-template-columns: 1fr;
            }

            nav {
                padding: 18px 5%;
            }
        }
    </style>
</head>

<body>

    <!-- Navigation -->
    <nav>
        <div class="logo">✚ Medi<span>Care</span></div>
        <a href="login.php">Staff Login →</a>
    </nav>

    <!-- Hero Section -->
    <section class="hero">

        <div class="hero-content">
            <div class="tag">SMART HEALTHCARE MANAGEMENT</div>

            <h1>
                Hospital Discharge
                <span>Management System</span>
            </h1>

            <p>
                Making hospital discharge simpler, faster and more organised.
                Manage discharge activities, coordinate departments and
                track every step through one centralised system.
            </p>

            <a href="login.php" class="btn">Get Started →</a>
        </div>

        <div class="hero-card">
            <div class="medical-icon">🏥</div>
            <h2>Better Coordination</h2>
            <p>
                Connecting doctors, nurses, billing and pharmacy
                departments for a smoother discharge process.
            </p>
        </div>

    </section>

    <!-- Features Section -->
    <section class="features" id="features">

        <h2>Our Key Features</h2>

        <p class="subtitle">
            Everything needed to organise the hospital discharge process.
        </p>

        <div class="feature-container">

            <div class="feature-box">
                <div class="icon">👨‍⚕️</div>
                <h3>Doctor Approval</h3>
                <p>
                    Doctors can initiate and approve patient discharge
                    requests after clinical assessment.
                </p>
            </div>

            <div class="feature-box">
                <div class="icon">💳</div>
                <h3>Billing Clearance</h3>
                <p>
                    Track billing activities and payment clearance
                    before final discharge.
                </p>
            </div>

            <div class="feature-box">
                <div class="icon">💊</div>
                <h3>Pharmacy Management</h3>
                <p>
                    Coordinate prescribed medicines and pharmacy
                    preparation for patients.
                </p>
            </div>

            <div class="feature-box">
                <div class="icon">🩺</div>
                <h3>Nursing Clearance</h3>
                <p>
                    Monitor nursing tasks and required patient
                    care clearances.
                </p>
            </div>

            <div class="feature-box">
                <div class="icon">📋</div>
                <h3>Discharge Summary</h3>
                <p>
                    Organise discharge documents and summary
                    preparation.
                </p>
            </div>

            <div class="feature-box">
                <div class="icon">📊</div>
                <h3>Discharge Tracking</h3>
                <p>
                    View pending, ongoing and completed discharge
                    activities in one place.
                </p>
            </div>

        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>© 2026 Hospital Discharge Management System | Academic Project</p>
    </footer>

</body>
</html>


