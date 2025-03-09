{{-- @props(['timetables']) --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10px;
        }

        h1 {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
        }

        .table-container {
            display: flex;
            justify-content: center;
            padding: 0 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-family: 'Times New Roman', Times, serif;
        }

        th, td {
            border: 1px solid black; 
            padding: 3px;
            text-align: center;
            font-size: 10px; 
        }

        th {
            background-color: #f8f8f8; 
        }

        td {
            background-color: #ffffff; 
        }

        td div {
            margin: 3px 0;
        }
    </style>
</head>

<body>

    @php
        $startTimes = [];

        foreach ($timetables as $date => $dailyTimetables) {
            foreach ($dailyTimetables as $timetable) {
                $startsAt = date('H:i a', strtotime($timetable->starts_at));
                $endsAt = date('H:i a', strtotime($timetable->ends_at));

                if (!in_array("$startsAt - $endsAt", $startTimes)) {
                    $startTimes[] = "$startsAt - $endsAt";
                }
            }
        }

        sort($startTimes);
    @endphp

    <h1>{{ $batch }}</h1>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Date</th>

                    @foreach ($startTimes as $startTime)
                        <th>{{ $startTime }}</th>
                    @endforeach
                </tr>
            </thead>

            <tbody>

                @foreach ($timetables as $date => $dailyTimetables)
                    <tr>
                        <td>{{ $date }}</td>

                        @php
                            $timeSlots = [];

                            foreach ($startTimes as $time) {
                                $timeSlots[$time] = null;
                            }

                            foreach ($dailyTimetables as $timetable) {
                                $startsAt = date('H:i a', strtotime($timetable->starts_at));
                                $endsAt = date('H:i a', strtotime($timetable->ends_at));
                                $timeSlots["$startsAt - $endsAt"] = $timetable;
                            }
                        @endphp

                        @foreach ($timeSlots as $time => $timetable)
                            <td>
                                @if ($timetable)
                                    <div>{{ $timetable->teacher->teacher_name ?? '' }}</div>
                                    <div>{{ $timetable->subject->subject_name ?? '' }}</div>
                                    <div>{{ $timetable->chapter->chapter_name ?? '' }}</div>
                                @else
                                    <div></div>
                                @endif
                            </td>
                        @endforeach

                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>
</body>

</html>
