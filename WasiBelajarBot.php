<?php
$TOKEN = 'xx';
$api = 'https://api.telegram.org/bot' . $TOKEN;
$output = json_decode(file_get_contents('php://input'), TRUE);
@$chat_id = $output['message']['chat']['id'];
@$message = $output['message']['text'];
@$callback_query = $output['callback_query'];
@$data = $callback_query['data'];
@$chat_id_in = $callback_query['message']['chat']['id'];
@$message_id = $callback_query['message']['message_id'];

// DATABASE CONNECT
// ============================================================
$server = "localhost";
$username = "root";
$password = "";
$db_name = "catatan_pemweb";

$mysqli = new mysqli($server, $username, $password, $db_name);

// MAIN MESSAGE
// ============================================================
if ($mysqli->connect_errno) {
    $bantuan = $mysqli->connect_error;
} else{
    switch($message) {
        case '/start':
            sendReply($chat_id, "Hai, Selamat Datang \"".$output["message"]["from"]["first_name"]."\"");
            sendReply($chat_id, "Bot ini adalah bot otomatis yang dibuat dengan menggunakan bahasa pemrograman PHP dan database MySQL.");
            sendReply($chat_id, "Klik /menu untuk menampilkan menu");
        break;
        case '/menu':
            sendReply($chat_id, "Silahkan pilih perintah di bawah ini!");
            sendReply($chat_id, "1. /start -> Memulai percakapan.
2. /menu -> Menampilkan menu bot.
3. /data -> Menampilkan data dari database.
4. /chart -> Menampilkan diagram berupa gambar.
5. /about -> Menampilkan profile bot.");
        break;
    }
}

// DATA
// ============================================================
if($message == "/data"){
    sendReply($chat_id, "Silahkan pilih perintah di bawah ini!");
    sendReply($chat_id, "1. /firstdata -> Menampilkan data terbaru.
2. /lastdata -> Menampilkan data terakhir.
3. /showdata -> Menampilkan beberapa data.
4. /showdatabyid -> Menampilkan data berdasarkan id.
5. /totaldata -> Menampilkan jumlah banyak data.");
    sendReply($chat_id, "Back to /menu");
} 
// CHART
// ============================================================
if ($message == "/chart"){
    sendReply($chat_id, "Silahkan pilih perintah di bawah ini!");
    sendReply($chat_id, "1. /charthargaidr -> Menampilkan diagram harga \"idr\".
2. /charthargausdt -> Menampilkan diagram harga \"usdt\".
3. /chartvolbtc -> Menampilkan diagram volume \"btc\".
4. /chartvolidr -> Menampilkan diagram volume \"idr\".
5. /chartlastbuy -> Menampilkan diagram pembelian terakhir.
6. /chartlastsell -> Menampilkan diagram penjualan terakhir.");
    sendReply($chat_id, "Back to /menu");
}
// ABOUT
// ============================================================
if ($message == "/about"){
    sendReply($chat_id, "Bot ini adalah bot otomatis yang dibuat dengan menggunakan bahasa pemrograman PHP dan database MySQL.");
    sendReply($chat_id, "Bot Versi 1.0");
    sendReply($chat_id, "Back to /menu");
}
// FIRSTDATA
// ============================================================
if ($message == "/firstdata"){
    $data = $mysqli->query("SELECT * FROM btc ORDER BY id DESC limit 5")->fetch_assoc();

    sendReply($chat_id, "Menampilkan data terbaru!");
    sendReply($chat_id, "-> ID : ".$data['id']."
-> Tanggal : ".$data['tanggal']."
-> Sinyal : ".$data['sinyal']."
-> Level : ".$data['level']."
-> Harga IDR : Rp. ".$data['hargaidr']."
-> Harga USDT : $".$data['hargausdt']."
-> Vol. BTC : ".$data['volidr']."
-> Vol. IDR : Rp. ".$data['volusdt']."
-> Last Buy : Rp. ".$data['lastbuy']."
-> Last Sell : Rp. ".$data['lastsell']."
-> Jenis : ".$data['jenis']."");
    sendReply($chat_id, "Back to /data");
}
// LASTDATA
// ============================================================
if ($message == "/lastdata"){
    $data = $mysqli->query("SELECT * FROM btc ORDER BY id ASC limit 5")->fetch_assoc();

    sendReply($chat_id, "Menampilkan data terlama!");
    sendReply($chat_id, "-> ID : ".$data['id']."
-> Tanggal : ".$data['tanggal']."
-> Sinyal : ".$data['sinyal']."
-> Level : ".$data['level']."
-> Harga IDR : Rp. ".$data['hargaidr']."
-> Harga USDT : $".$data['hargausdt']."
-> Vol. BTC : ".$data['volidr']."
-> Vol. IDR : Rp. ".$data['volusdt']."
-> Last Buy : Rp. ".$data['lastbuy']."
-> Last Sell : Rp. ".$data['lastsell']."
-> Jenis : ".$data['jenis']."");
    sendReply($chat_id, "Back to /data");
}
// SHOWDATA
// ============================================================
if ($message == "/showdata"){
    $param = explode("/showdata ",$text);
    $resultString=$param.$expStr[1];
    $getInteger = (INT)$resultString;

    if(isset($resultString)){
        if(is_numeric ($getInteger)){
        sendReply($chat_id, "Gunakan perintah seperti di bawah ini");
        sendReply($chat_id, "Contoh: /showdata 5");
        sendReply($chat_id, "Output yang dihasilkan pada contoh tersebut berupa data terbaru sebanyak 5 data");
        sendReply($chat_id, "Back to /data");
            $result = $mysqli->query("SELECT * FROM btc ORDER BY id desc limit '$getInteger'")->fetch_all(MYSQLI_ASSOC);
            sendReply($chat_id, "Menampilkan data terbaru sebanyak $getInteger data");
            foreach($result as $data){
                sendReply($chat_id, "-> ID : ".$data['id']."
-> Tanggal : ".$data['tanggal']."
-> Sinyal : ".$data['sinyal']."
-> Level : ".$data['level']."
-> Harga IDR : Rp. ".$data['hargaidr']."
-> Harga USDT : $".$data['hargausdt']."
-> Vol. BTC : ".$data['volidr']."
-> Vol. IDR : Rp. ".$data['volusdt']."
-> Last Buy : Rp. ".$data['lastbuy']."
-> Last Sell : Rp. ".$data['lastsell']."
-> Jenis : ".$data['jenis']."");
    sendReply($chat_id, "Back to /data");
            }
        } else{
            sendReply($chat_id, "Parameter yang anda masukkan salah");
        }
    } else{
        sendReply($chat_id, "Back to /data");
    }
}
// SHOWDATABYID
// ============================================================
if ($message == "/showdatabyid"){
    $key = explode(" ", $text);
    $expStr=explode("$key", $key);
    $resultString=$key.$expStr[1];
    $getInteger = (INT)$resultString;

    if(isset($resultString)){
        if(is_numeric ($getInteger)){
            sendReply($chat_id, "Gunakan perintah seperti di bawah ini");
            sendReply($chat_id, "Contoh: /showdatabyid 41234");
            sendReply($chat_id, "Output yang dihasilkan pada contoh tersebut berupa data dari id tersebut");
            sendReply($chat_id, "Back to /data");
            $result = $mysqli->query("SELECT * FROM btc WHERE id = '$getInteger' ORDER BY id DESC LIMIT 1")->fetch_all(MYSQLI_ASSOC);
            foreach($result as $data){
                sendReply($chat_id, "Menampilkan data berdasarkan id yang dimasukkan");
                sendReply($chat_id, "-> ID : ".$data['id']."
-> Tanggal : ".$data['tanggal']."
-> Sinyal : ".$data['sinyal']."
-> Level : ".$data['level']."
-> Harga IDR : Rp. ".$data['hargaidr']."
-> Harga USDT : $".$data['hargausdt']."
-> Vol. BTC : ".$data['volidr']."
-> Vol. IDR : Rp. ".$data['volusdt']."
-> Last Buy : Rp. ".$data['lastbuy']."
-> Last Sell : Rp. ".$data['lastsell']."
-> Jenis : ".$data['jenis']."");
    sendReply($chat_id, "Back to /data");
            }
        } else{
            sendReply($chat_id, "ID yang anda masukkan salah");
        }
    } else{
        sendReply($chat_id, "Back to /data");
    }
}
// JUMLAH
// ============================================================
if ($message == "/totaldata"){
    $total =  $mysqli->query("SELECT * FROM btc");
    $jumlah = mysqli_num_rows($total);

    sendReply($chat_id, "Menampilkan jumlah seluruh data!");
    sendReply($chat_id, "Jumlah Data Keseluruhan BTC : ".$jumlah);
    sendReply($chat_id, "Back to /data");
}
// CHARTHARGAIDR
// ============================================================
if ($message == "/charthargaidr"){
    $image = "<a href='https://shot.screenshotapi.net/screenshot?token=55CCBY7-5JPMQ02-Q6YT7PG-SSBTPY8&url=https%3A%2F%2Fwasibelajarweb.000webhostapp.com%2FTugasBot%2Fimage%2Fwasi-diagramhargaidr.png&output=image&file_type=png&wait_for_event=load'>Diagram Harga IDR</a>";
    sendReply($chat_id, "Menampilkan diagram \"Harga IDR\"");
    sendReply($chat_id, "$image");
    sendReply($chat_id, "Back to /chart");
}
// CHARTHARGAUSDT
// ============================================================
if ($message == "/charthargausdt"){
    $image = "<a href='https://shot.screenshotapi.net/screenshot?token=55CCBY7-5JPMQ02-Q6YT7PG-SSBTPY8&url=https%3A%2F%2Fwasibelajarweb.000webhostapp.com%2FTugasBot%2Fimage%2Fwasi-diagramhargausdt.png&output=image&file_type=png&wait_for_event=load'>Diagram Harga USDT</a>";
    sendReply($chat_id, "Menampilkan diagram \"Harga USDT\"");
    sendReply($chat_id, "$image");
    sendReply($chat_id, "Back to /chart");
}
// CHARTVOLBTC
// ============================================================
if ($message == "/chartvolbtc"){
    $image = "<a href='https://shot.screenshotapi.net/screenshot?token=55CCBY7-5JPMQ02-Q6YT7PG-SSBTPY8&url=https%3A%2F%2Fwasibelajarweb.000webhostapp.com%2FTugasBot%2Fimage%2Fwasi-diagramvolbtc.png&output=image&file_type=png&wait_for_event=load'>Diagram Volume BTC</a>";
    sendReply($chat_id, "Menampilkan diagram \"Volume BTC\"");
    sendReply($chat_id, "$image");
    sendReply($chat_id, "Back to /chart");
}
// CHARTVOLIDR
// ============================================================
if ($message == "/chartvolidr"){
    $image = "<a href='https://shot.screenshotapi.net/screenshot?token=55CCBY7-5JPMQ02-Q6YT7PG-SSBTPY8&url=https%3A%2F%2Fwasibelajarweb.000webhostapp.com%2FTugasBot%2Fimage%2Fwasi-diagramvolidr.png&output=image&file_type=png&wait_for_event=load'>Diagram Volume IDR</a>";
    sendReply($chat_id, "Menampilkan diagram \"Volume IDR\"");
    sendReply($chat_id, "$image");
    sendReply($chat_id, "Back to /chart");
}
// CHARTLASTBUY
// ============================================================
if ($message == "/chartlastbuy"){
    $image = "<a href='https://shot.screenshotapi.net/screenshot?token=55CCBY7-5JPMQ02-Q6YT7PG-SSBTPY8&url=https%3A%2F%2Fwasibelajarweb.000webhostapp.com%2FTugasBot%2Fimage%2Fwasi-diagramlastbuy.png&output=image&file_type=png&wait_for_event=load'>Diagram Last Buy</a>";
    sendReply($chat_id, "Menampilkan diagram \"Last Buy\"");
    sendReply($chat_id, "$image");
    sendReply($chat_id, "Back to /chart");
}
// CHARTLASTSELL
// ============================================================
if ($message == "/chartlastsell"){
    $image = "<a href='https://shot.screenshotapi.net/screenshot?token=55CCBY7-5JPMQ02-Q6YT7PG-SSBTPY8&url=https%3A%2F%2Fwasibelajarweb.000webhostapp.com%2FTugasBot%2Fimage%2Fwasi-diagramlastsell.png&output=image&file_type=png&wait_for_event=load'>Diagram Last Sell</a>";
    sendReply($chat_id, "Menampilkan diagram \"Last Sell\"");
    sendReply($chat_id, "$image");
    sendReply($chat_id, "Back to /chart");
}

function sendReply($chat_id, $message) {
  file_get_contents($GLOBALS['api'] . '/sendMessage?chat_id=' . $chat_id . '&text=' . urlencode($message) . '&parse_mode=html');
}
