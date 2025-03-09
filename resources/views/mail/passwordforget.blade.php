<!DOCTYPE html>
<html>

    <head>
        <title>Event Mail</title>
        {{-- <link rel="stylesheet" href="{{ asset('assets/css/mail.css') }}"> --}}
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

            button {
                padding: 8px 16px;
                background-color: #053067;
                border: none;
                border-radius: 8px;
                box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
                cursor: pointer;
            }

            a {
                text-decoration: none;
                color: white;
            }
        </style>
    </head>

    <body>
        <div class="logo-container">
            <img src="{{ asset("assets/images/Example.png") }}">
        </div>

        <div class="container">
            <h2>Password Forgot Token</h2>

            <div>
                <p>We are delighted to inform you about an exciting event at our institute!</p>
                <p>{{ $data }}</p>

                <button>
                    <a href="">Learn More</a>
                </button>

                <p>Thank you, <br> Example</p>
            </div>

            <div>
                <small>CONTACT INFORMATION</small> <br>
                <small>No.5, Yuzana Street, Mingalar Taung Nyunt, Yangon. <br> 09964051332, 09262620754</small>
            </div>
        </div>

        <div class="footer-container">
            <p>Example. all rights reserved.</p>
        </div>
    </body>

</html>
