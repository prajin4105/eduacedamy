<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Certificate of Completion</title>
    <style>
        @page {
            margin: 0;
            size: A4 landscape;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: #f5f5f5;
            width: 297mm;
            height: 210mm;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .certificate {
            background: linear-gradient(135deg, #d4a5a5 0%, #c49999 50%, #b88a8a 100%);
            width: 100%;
            height: 100%;
            border-radius: 12px;
            position: relative;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            overflow: hidden;
        }

        /* Decorative border */
        .certificate::before {
            content: '';
            position: absolute;
            top: 25px;
            left: 25px;
            right: 25px;
            bottom: 25px;
            border: 3px solid #4a4a4a;
            border-radius: 8px;
            pointer-events: none;
        }

        /* Header section */
        .header {
            background-color: #4a4a4a;
            color: white;
            text-align: center;
            padding: 20px 40px;
            margin: 20px auto 60px;
            border-radius: 0;
            max-width: 500px;
            position: relative;
            z-index: 2;
            letter-spacing: 3px;
        }

        .header h1 {
            font-size: 28px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
        }

        /* Certificate text */
        .certificate-intro {
            text-align: center;
            color: #555;
            font-size: 18px;
            margin-bottom: 40px;
            position: relative;
            z-index: 2;
        }

        /* Student name section */
        .student-section {
            text-align: center;
            margin-bottom: 50px;
            position: relative;
            z-index: 2;
        }

        .student-name {
            font-size: 48px;
            font-weight: bold;
            color: #f5f5f5;
            text-transform: uppercase;
            letter-spacing: 4px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            margin: 20px 0;
            line-height: 1.2;
        }

        /* Course description */
        .course-description {
            text-align: center;
            color: #555;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 20px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            position: relative;
            z-index: 2;
        }

        .course-title {
            font-weight: bold;
            color: #4a4a4a;
        }

        .completion-date {
            text-align: center;
            color: #666;
            font-size: 14px;
            margin-bottom: 60px;
            position: relative;
            z-index: 2;
        }

        /* Signatures section */
        .signatures {
            display: flex;
            justify-content: space-around;
            align-items: flex-end;
            margin-top: auto;
            padding: 0 60px;
            position: relative;
            z-index: 2;
        }

        .signature {
            text-align: center;
            flex: 1;
            margin: 0 30px;
        }

        .signature-line {
            width: 180px;
            height: 2px;
            background-color: #4a4a4a;
            margin: 0 auto 15px;
            position: relative;
        }

        .signature-image {
            position: absolute;
            top: -40px;
            left: 50%;
            transform: translateX(-50%);
            width: 120px;
            height: 40px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 120 40'%3E%3Cpath d='M10,25 Q20,15 30,20 T50,18 Q60,12 70,20 T90,22 Q100,15 110,25' stroke='%23333' stroke-width='2' fill='none'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-size: contain;
            background-position: center;
        }

        .signature-image.signature2 {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 120 40'%3E%3Cpath d='M15,20 Q25,10 35,25 Q45,30 55,15 Q65,8 75,22 Q85,28 95,18 Q105,12 115,20' stroke='%23333' stroke-width='2' fill='none'/%3E%3C/svg%3E");
        }

        .signature-name {
            font-size: 16px;
            font-weight: bold;
            color: #4a4a4a;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }

        .signature-title {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Certificate number */
        .cert-number {
            position: absolute;
            bottom: 15px;
            right: 25px;
            font-size: 10px;
            color: #888;
            font-family: 'Courier New', monospace;
            z-index: 2;
        }

        /* Background decorative elements */
        .bg-decoration {
            position: absolute;
            opacity: 0.1;
            color: #fff;
            font-size: 200px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-15deg);
            z-index: 1;
            pointer-events: none;
        }

        /* Responsive adjustments */
        @media print {
            body {
                background: none;
                padding: 0;
            }

            .certificate {
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="certificate">
        <!-- Background decoration -->
        <div class="bg-decoration">★</div>

        <!-- Header -->
        <div class="header">
            <h1>Certificate of Completion</h1>
        </div>

        <!-- Certificate intro text -->
        <div class="certificate-intro">
            This certificate goes to
        </div>

        <!-- Student name section -->
        <div class="student-section">
            <div class="student-name">{{ $student_name }}</div>
        </div>

        <!-- Course description -->
        <div class="course-description">
            for successfully completing the <span class="course-title">{{ $course_title }}</span><br>
            conducted by EduAcademy Training Centre with excellence and dedication.
        </div>

        <div class="completion-date">on {{ $issue_date }}</div>

        <!-- Signatures -->
        <div class="signatures">
            <div class="signature">
                <div class="signature-line">
                    <div class="signature-image"></div>
                </div>
                <div class="signature-name">{{ $instructor_name }}</div>
                <div class="signature-title">Head Instructor</div>
            </div>
            <div class="signature">
                <div class="signature-line">
                    <div class="signature-image signature2"></div>
                </div>
                <div class="signature-name">Director of Education</div>
                <div class="signature-title">General Manager</div>
            </div>
        </div>

        <!-- Certificate number -->
        <div class="cert-number">Certificate No: {{ $certificate_number }}</div>
    </div>
</body>
</html>
