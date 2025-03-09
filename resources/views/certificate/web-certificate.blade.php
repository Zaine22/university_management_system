@php
    $imageSrc = 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('images/certificate.png')));
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate</title>
    <style>
        /* body {
            margin: 0;
            padding: 0;
            width: 595px;
            height: 842px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family:'Times New Roman', Times, serif;
            border: 1px solid red;
        }

        .certificate-container {
            position: relative;
            width: 100%;
            height: 100%;
            border: 1px solid black;
        }

        .certificate-container img.background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: auto;
            border-radius: 15px;
            z-index: -1;
        } */

        body {
            margin: 0;
            padding: 0;
            z-index: -2;
            font-family: 'Times New Roman', Times, serif;
            position: relative;
        }

        .certificate-container {
            float: left;
            height: 100%;
            width: 100%;
            overflow: hidden;
            position: relative;
        }

        .certificate-container img.background {
            float: left;
            /* Ensures proper alignment */
            width: 100%;
            /* Fills the container's width */
            /* Fills the container's height */
            height: 100%;
            /* Fills the container's width */
            /* Fills the container's height */
            object-fit: cover;
            /* Ensures the image covers the container without distortion */
            position: absolute;
            /* Places the image inside the container */
            top: 0;
            left: 0;
            border-radius: 15px;
            /* Matches the container's rounded corners */
            z-index: 2;
        }


        .certificate-content {
            position: relative;
            padding: 40px;
            text-align: center;
        }

        .certificate-header {
            padding-top: 170px;
        }

        .certificate-header h2 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .certificate-header span {
            font-size: 14px;
            display: block;
            margin-bottom: 20px;
        }

        .certificate-header .student-name {
            font-size: 20px;
            font-weight: bold;
        }

        .certificate-body {
            margin-top: 20px;
            font-size: 16px;
            line-height: 1.5;
        }

        .certificate-body .description {
            margin-bottom: 30px;
        }

        .certificate-body .subjects {
            margin-top: 20px;
            font-size: 14px;
            text-align: left;
        }
    </style>
</head>

<body>
    <div class="certificate-container">
        <!-- Background Image -->
        <img src="<?php echo $imageSrc; ?>" alt="Certificate Background" class="background">

        <!-- Certificate Content -->
        <div class="certificate-content">
            <div class="certificate-header">
                <h2>Certificate of Competence</h2>
                <span>This is to certify that</span>
                <div class="student-name">{{ $studentName }}</div>
            </div>
            <div class="certificate-body">
                <div class="description">
                    {!! $template->description !!}
                </div>
                <div class="subjects">
                    {!! $template->subjects !!}
                </div>
            </div>
        </div>
    </div>
</body>

</html>
