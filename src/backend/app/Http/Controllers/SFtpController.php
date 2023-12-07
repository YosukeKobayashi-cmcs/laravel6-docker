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

      $remoteFile = "files/$fileName";
      $sftpSetting = [
          'host' => 'sftp-server',
          'port' => 22,
          'username' => 'user',
          'passphrase' => 'password',
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
      
      $result = ['localFile' => $localFile, 'remotePath' => "ssh2.sftp://{$sftp}/{$remoteFile}", 'message' => 'ok'];
      return response()->json($result);
    }

    public function renameSFtpFile(Request $request) {
      $fileName = 'test.csv';
      $renameFileName = 'test_rename.csv';
  
      $remoteFile = "/files/$fileName";
      $renameFile = "/files/$renameFileName";
      $sftpSetting = [
          'host' => 'sftp-server',
          'port' => 22,
          'username' => 'user',
          'passphrase' => 'password',
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

      // ファイル名の変更
      $result = ['rename file' => $remoteFile, 'message' => 'ok'];
      if (!ssh2_sftp_rename($sftp, $remoteFile, $renameFile)) {
        $result['message'] = 'error';
      }

      ssh2_disconnect($connection);

      return response()->json($result);
    }
}
