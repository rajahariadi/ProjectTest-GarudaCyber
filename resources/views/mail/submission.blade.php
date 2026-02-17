<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Penilaian Tugas</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f5f6fa; margin: 0; padding: 0;">
    <div style="max-width: 600px; margin: auto; background: #ffffff; padding: 20px; border-radius: 8px;">
        <h2>Tugas kamu telah dinilai</h2>
        <p>Hi, <b>{{ $student->name }}</b> kamu mempunyai tugas yang telah dinilai</p>

        <p><strong>Judul Tugas:</strong></p>

        <h3>
            {{ $assignmentTitle }}
        </h3>

        <h3>Nilai : {{ $score }}</h3>

        <p>
            Terus belajar dan tetap semangat
        </p>
        <br>

    </div>
</body>

</html>
