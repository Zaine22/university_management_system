<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
  </head>
    <style>
        @import url("https://fonts.googleapis.com/css?family=Open+Sans|Pinyon+Script|Rochester");

        body {
            margin: auto auto;
            padding: 20px 0;
            background: transparent;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 595px;
            height: 842px;
            /* A4 size */
            /* Optional: If you want a background for the whole body */
        }

        .pm-certificate-container {
            position: relative;
            width: 100%;
            height: 100%;
            margin: 5px;
            font-family: "Open Sans", sans-serif;
            z-index: 999;
            background-image: url("/images/certificate.png");
            background-size: 100% 100%;
            background-position: center;
            background-repeat: no-repeat;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .pm-certificate-border {
            position: relative;
            width: 90%;
            /* Ensures some padding from the border */
            height: 90%;
            /* Ensures some padding from the border */
            padding: 20px;
            text-align: center;
            border-radius: 10px;
            overflow: hidden;
            /* Prevent content from spilling over */
            transform: translate(0%, 0%);
            /* Keep it centered */
            box-sizing: border-box;
        }

        .pm-certificate-header {
            margin-bottom: 20px;
            margin-top: 150px;
            font-family: "Bookman Old Style", serif;
        }

        .pm-certificate-title h2 {
            font-size: 20px;
            color: #333;
            letter-spacing: 2px;
            font-weight: 999;
            font-family: "Bookman Old Style", serif;
        }

        .pm-certificate-header span {
            font-size: 12px;
            font-family: "Bell MT", serif;
        }

        .pm-certificate-header h2 {
            font-family: "Bell MT", serif;
            font-weight: 666;
        }

        .pm-certificate-body {
            color: #333;
            font-size: 16px;
            /* Adjust font size to fit within the border */
            line-height: 1.5;
            /* Ensure proper line spacing */
        }

        .pm-name-text {
            font-family: "Bell MT", serif;
            margin-bottom: 15px;
            font-weight: 510;
        }

        .section-text {
            margin-top: 40px;
            font-family: "Aparajita", serif;
        }
    </style>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
  <body>
    <div class="container place-content-center pm-certificate-container">
      <div class="outer-border"></div>
      <div class="inner-border"></div>

      <div class="pm-certificate-border col-span-9">
        <div class="row pm-certificate-header">
          <div class="pm-certificate-title cursive col-span-9 text-center">
            <h2>Certificate of Competence</h2>
          </div>
          <span>This is to certify that</span>
          <h2>Your Name</h2>
        </div>

        <div class="row pm-certificate-body">
          <div class="pm-certificate-block">
            <div class="col-span-12">
              <div class="row">
                <div class="col-xs-2 pm-name-text">
                  has attended the Intensive Training in
                  <span class="font-extrabold"
                    >Foundation Diploma in Information Technology & Data Science</span
                  >
                  from start date to end date and has been awarded this certificate of proficiency. The student has had a minimum of 720-hour hands-on experience. The following courses had successfully completed.
                </div>
              </div>
            </div>
          </div>

          <div class="grid grid-cols-2 gap-2 section-text ">
            
            <div class="text-left whitespace-normal">Semester-1</div>
            <div class="text-left whitespace-normal">Semester-2</div>
            
            <div class="text-left whitespace-normal">
              <div>•	Microsoft Office</div>
              <div>•	Computing Maths</div>
              <div>•	Component, Hardware and Software</div>
              <div>•	Human Interface and Multimedia</div>
              <div>•	Database</div>
              
            </div>
          
            <!-- ... -->
            <div class="text-left">
              <div>•	Networking</div>
              <div>•	Security</div>
              <div>•	Programming Logic</div>
              <div>•	Data Structure</div>
              <div>•	Introduction to Java</div>
              <div>•	Introduction to Web Development</div>
 
            </div>
          </div>

        </div>
      </div>
    </div>
  </body>
</html>
