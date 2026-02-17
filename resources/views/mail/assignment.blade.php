<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tugas Baru</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f5f6fa; margin: 0; padding: 0;">
    <div style="max-width: 600px; margin: auto; background: #ffffff; padding: 20px; border-radius: 8px;">
        <h2>Tugas baru</h2>
        <p>Hi, <b>{{ $student->name }}</b> kamu mempunyai tugas baru</p>

        <p><strong>Judul Tugas:</strong></p>

        <h3>
            {{ $assignmentTitle }}
        </h3>

        <p>
            Jangan lupa untuk mengumpulkannya tepat waktu
        </p>
        <br>

    </div>
</body>

</html>
