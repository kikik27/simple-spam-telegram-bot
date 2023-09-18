
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spam Bot Tele</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<?php
if (isset($_POST['submit'])) {
    $text = $_POST['text'];
    $jumlah = $_POST['jumlah'];
    $url = $_POST['url'];
    $formula = $url . $text;

    // Fungsi untuk mengirim permintaan HTTP ke API
    function sendRequest($url, $method = 'POST', $data = null, $headers = array())
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        if ($data !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            $headers[] = 'Content-Type: application/json';
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);

        if ($response === false) {
            die(curl_error($ch));
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return array('http_code' => $httpCode, 'response' => $response);
    }

    for ($i = 0; $i < $jumlah; $i++) {
        $result = sendRequest($formula, 'POST');
        if ($result['http_code'] == 200) {
            $response = json_decode($result['response']);

            // Mengakses berbagai properti respons API
            $message_id = $response->result->message_id;
            $from_first_name = $response->result->from->first_name;
            $chat_id = $response->result->chat->id;
            $text = $response->result->text;

            echo "
            <div class='alert alert-success' role='alert'>
                Pesan ke-$i: Message ID: $message_id, Dari: $from_first_name, Chat ID: $chat_id, Text: $text
            </div>
        ";
        } else {
            $errorResponse = json_decode($result['response']);
            $error_code = $errorResponse->error_code;
            $description = $errorResponse->description;
            
            $isSuccess = false;

            echo "
            <div class='alert alert-danger' role='alert'>
                Permintaan API ke-$i gagal dengan kode HTTP: " . $result['http_code'] . ", Error Code: $error_code, Description: $description
            </div>
        ";
        }
    }
=
}
?>
<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Spam Bot Tele</h5>
                <form action="" method="post">
                    <div class="mb-3">
                        <input type="text" class="form-control" name="text" id="text" placeholder="Masukkan pesan">
                    </div>
                    <div class="mb-3">
                        <input type="number" class="form-control" name="jumlah" id="jumlah" placeholder="Jumlah spam">
                    </div>
                    <div class="mb-3">
                        <input type="text" class="form-control" name="url" id="url" placeholder="Masukkan link API bot">
                    </div>
                    <button type="submit" name="submit" class="btn btn-success">Kirim</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
