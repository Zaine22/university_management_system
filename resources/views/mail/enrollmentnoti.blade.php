<!DOCTYPE html>
<html>

<head>
    <title>Enrollment Email</title>
    {{-- <link rel="stylesheet" href="{{asset('assets/css/mail.css')}}"> --}}
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            background-color: #E2E8F0;
            margin: 0;
            padding: 20px;
        }

        .container {
            background-color: #F8FAFC;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .logo-container {
            display: flex;
            gap: 5px;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
            margin-top: 20px;
        }

        .footer-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h2 {
            color: #333333;
        }

        p,
        small {
            color: #666666;
        }

        img {
            width: 10%;
        }
    </style>
</head>

<body>
    <div class="logo-container">
        <img src="{{ asset('assets/images/RILOGO.png') }}">
    </div>
    <div class="container">
        <h2>Welcome to RI Institute</h2>
        
        <div>
            <p>Dear {{ $enrollment->student->student_name }},</p>
            <p>Welcome to {{ $enrollment->batch->batch_name }}! We are thrilled to have you on board and look forward to guiding you through this excitinglearning journey.</p>
            <p>Choose RI for your Quality of Education! <br> RI Institute</p>
        </div>

        <div>
            <small>CONTACT INFORMATION</small> <br>
            <small>No.5, Yuzana Street, Mingalar Taung Nyunt, Yangon. <br> 09964051332, 09262620754</small>
        </div>
    </div>

    <div class="footer-container">
        <p>RI INSTITUTE. all rights reserved.</p>
    </div>
</body>

</html>
