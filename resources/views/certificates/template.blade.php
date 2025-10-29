<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of Achievement</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
        }

        .certificate-container {
            width: 100%;
            max-width: 1200px;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            box-sizing: border-box;
        }

        .certificate-border {
            border: 5px solid #a87d32; /* Gold border */
            padding: 30px;
            position: relative;
            text-align: center;
            box-sizing: border-box;
        }

        .certificate-content {
            padding: 20px;
            border: 2px dashed #ccc;
            box-sizing: border-box;
        }

        .title {
            font-size: 3em;
            color: #a87d32;
            margin-bottom: 20px;
        }

        .award-text {
            font-size: 1.2em;
            margin-bottom: 10px;
        }

        .recipient-name {
            font-size: 2.5em;
            color: #333;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        .description {
            font-size: 1.1em;
            margin-bottom: 5px;
        }

        .course-name {
            font-size: 1.5em;
            font-weight: bold;
            color: #555;
            margin-bottom: 30px;
        }

        .date {
            font-size: 1em;
            color: #777;
            margin-bottom: 40px;
        }

        .signatures {
            display: flex;
            justify-content: space-around;
            margin-top: 40px;
        }

        .signature-block {
            flex-basis: 45%;
            text-align: center;
        }

        .signature-line {
            border-bottom: 1px solid #000;
            width: 80%;
            margin: 0 auto 5px auto;
        }

        .signer-title {
            font-size: 0.9em;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <div class="certificate-border">
            <div class="certificate-content">
                <h1 class="title">Certificate of Achievement</h1>
                <p class="award-text">This certificate is proudly presented to</p>

                <!-- Student name -->
                <h2 class="recipient-name">{{ $student_name }}</h2>

                <p class="description">For outstanding achievement in completing the</p>

                <!-- Course name -->
                <p class="course-name">{{ $course_title }}</p>

                <!-- Date -->
                <p class="date">Awarded on: {{ $issue_date }}</p>

                <!-- Signatures -->
                <div class="signatures">
                    <div class="signature-block">
                        <img src="{{ public_path('images/s.png') }}" alt="Signature 1" style="width:150px; height:auto; margin-bottom:5px;">
                        <p class="signature-line"></p>
                        <p class="signer-title">Head Instructor</p>
                    </div>
                    <div class="signature-block">
                        <img src="{{ public_path('images/s2.png') }}" alt="Signature 1" style="width:150px; height:auto; margin-bottom:5px;">

                        <p class="signature-line"></p>
                        <p class="signer-title">Director of Education</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
