<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>API Test Result</title>
    <style>
        h1,
        td {
            text-align: center
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        tr:nth-child(even) {
            background-color: #f1eded;
        }

        td {
            border: 1px solid #b8b8b8;
            padding: .25rem;
        }

        p span {
            display: inline-block;
            padding: .5rem;
            background-color: #e71717;
            color: white;
            font-weight: 600;
            border-radius: 1rem;
        }

        h2 {
            background-color: #3e3b3b;
            color: white;
            text-transform: unset;
            padding: .75rem;
        }
    </style>
</head>

<body>
    <h1>Beezlinq API Test Result</h1>
    <h2>Latest Test Results</h2>
    <p>
        <span>{{ $test_data->failure_percent }}% failure</span>
        <strong> {{ count($test_data->failed_tests) }} endpoints</strong> failed out of <strong>
            {{ $test_data->tested_endpoint_count }} tested</strong>
        endpoints.
    </p>
    <h3>Failed endpoints:</h3>
    <table>
        <thead>
            <tr>
                <th>S/N</th>
                <th>Name</th>
                <th>Endpoint</th>
                <th>Status Code</th>
                <th>Tested At</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($test_data->failed_tests as $key => $failed_test)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $failed_test->endpoint->name }}</td>
                    <td>{{ $test_data->base_url . $failed_test->endpoint->link }}</td>
                    <td>{{ $failed_test->status_code }}</td>
                    <td>{{ $failed_test->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <h2>All-time Test Results</h2>
</body>

</html>
