<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;

class SFtpController extends Controller
{

    public function outputCsvBySFtp(Request $request) {
      $fileName = 'test.csv';
      $localFile = public_path($fileName);

      if (!file_exists($localFile) || !is_readable($localFile)) {
          throw new Exception('Local file does not exist or is not readable');
      }

      $remoteFile = $fileName;
      $sftpSetting = [
          'host' => 'sparkling-water-50295.sftptogo.com',
          'port' => 22,
          'username' => '8e00f9a0a51ff8243d41a1b413134b',
          'passphrase' => 'b1xazuio15tjjbh3l1jeyrw0frgwvr0dw9leqvtu',
      ];
      $connection = ssh2_connect($sftpSetting['host'], $sftpSetting['port']);
      if (!$connection) {
          throw new Exception('Connection failed');
      }
            
      // 認証
      if (!ssh2_auth_password($connection, $sftpSetting['username'], $sftpSetting['passphrase'])) {
          throw new Exception('Authentication failed');
      }

      // SFTPセッションの開始
      $sftp = ssh2_sftp($connection);

      // ファイルのアップロード
      $stream = fopen("ssh2.sftp://{$sftp}/{$remoteFile}", 'w');

      if (!$stream) {
          throw new Exception('Could not open file');
      }

      $data_to_write = file_get_contents($localFile);
      if ($data_to_write === false) {
          throw new Exception('Could not read local file');
      }

      if (fwrite($stream, $data_to_write) === false) {
          throw new Exception('Could not send data from local file');
      }

      fclose($stream);
      
      $result = ['localFile' => $localFile, 'message' => 'ok'];
      return response()->json($result);
    }
}
