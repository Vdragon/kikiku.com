<?php
/*
This program is free software; you can redistribute it and/or modify 
it under the terms of the GNU General Public License as published by  
the Free Software Foundation; version 2 of the License.

This program is distributed in the hope that it will be useful, 
but WITHOUT ANY WARRANTY; without even the implied warranty of  
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the 
GNU General Public License for more details. 

You should have received a copy of the GNU General Public License 
along with this program; if not, write to the Free Software 
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA 
*/

class CodeGuard_Exporter {
  const DEFAULT_FILE_LIST_BUNDLE_SIZE = 500;
  const TABLE_DATA_BUNDLE_SIZE = 1000;

  function __construct() {
  }

  // Check for openssl requirements
  static function test_requirements() {
    return function_exists('openssl_pkey_get_public') && function_exists('openssl_seal')
      && function_exists('base64_encode') && function_exists('openssl_public_decrypt')
        && function_exists('openssl_free_key') && function_exists('openssl_get_privatekey')
          && function_exists('openssl_get_publickey') && function_exists('sha1')
            && function_exists('gzcompress') && function_exists('base64_decode');
  }

  //
  // The 2048-bit RSA public key for CodeGuard's Backup Server.
  // All data returned by this plugin is strongly encrypted using the OpenSSL library
  // this key.
  //
  public function codeguard_public_key_contents() {
    // This is the test key, not the production one.
    //return "-----BEGIN PUBLIC KEY-----\nMFwwDQYJKoZIhvcNAQEBBQADSwAwSAJBAKpfrreqZb9B5pLYU02qFpXeMB2XUI70\nPhg7Dsp6lGgw43Dv8CbK/JNvn6PuCRYtHOzDpuLeG+1wjKfXgkzB2P8CAwEAAQ==\n-----END PUBLIC KEY-----\n";

    // CodeGuard public key
    return "-----BEGIN PUBLIC KEY-----\nMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA3k75f7nJSDj324f7k9pz\n8uwI6EDD9R+vMIlUOLt+oeeBw2mR2N9z7OMLXmHE3418RfDrZBEtBIymlh4WnBMd\nY37iWJD9FuxJbrzObWDrxGUCsE5bELKEFWcMO9OBSn+xXmVdiDn+pfmewpnQm5Sn\ndK0pECTKjAYn8VJR5lruUNc3+2hw8eiYA21f+x3DaMZx0MFtqBQ5288IkAOEie6V\n87zD4trStoSerhLsUbPXIYYK5kI+8xgIZuUwr068nhqG0JwmrTfLD9DPkLe43kqt\n7VHYvUkSlWk8NdYhy28nevsKH8RMVuklYWz6u9rGNLS5Ssf9ZenNQXUA/xNzg1BJ\nXwIDAQAB\n-----END PUBLIC KEY-----\n";
  } // end codeguard_public_key_contents


  ////////////////////////////////////////////////////////////////////////////////////
  // Web Application Security Functions
  ////////////////////////////////////////////////////////////////////////////////////

  // Compress and seal the data using openssl_seal and encode it for JSON transport.
  // This function is by Rietta Inc. It's licensed under the terms of the BSD license.
  private function compress_and_seal_with_json($dataToEncrypt) {
    $pubKey[] = openssl_pkey_get_public($this->codeguard_public_key_contents());
    $sealed = "";
    $ekeys = "";
    $result = openssl_seal(gzcompress($dataToEncrypt), $sealed, $ekeys, $pubKey);
    foreach($ekeys AS $key => $value) $ekeys_ascii[$key] = base64_encode($value);
    return array('encdata'=>base64_encode($sealed)
      ,'enckey'=>json_encode($ekeys_ascii)
    );
  } // end compress_and_seal_with_json

  // Using an RSA public key, verify that the data and timestamp were signed by the
  // associated RSA private key (that is not present here) using a SHA1 hash of the
  // data and its timestamp.
  // Return boolean TRUE if its valid.  FALSE, otherwise.
  // This function is by Rietta Inc. It's licensed under the terms of the BSD license.
  private function verify_with_rsa($received, $public_key_contents) {
    $to_return = false;
    $pubkeyid = openssl_get_publickey($public_key_contents);
    $signature = base64_decode($received->signature);
    if (openssl_public_decrypt($signature, $opened_signature, $pubkeyid)) {
      if(sha1($received->timestamp . $received->data) == $opened_signature) {
        $to_return = true;
      } // end if the SHA1 hash matches the signature
    } // end if openssl_public_decrypt worked with the signature
    openssl_free_key($pubkeyid);
    return $to_return;
  } // end verify_with_rsa_from_json

  // Wrapper for the encryption function that encrypts arbitrary data and returns
  // a JSON-encoded packet.  The data passed in must be able to be encoded with json.
  private function bundle_and_seal_with_json($data_to_seal) {
    return json_encode($this->compress_and_seal_with_json(json_encode($data_to_seal)));
  } // bundle_and_seal_with_json


  public function decrypt_chunked_rsa($array_of_chunks, $private_key_contents) {
    $to_return = "";
    $privkey = openssl_get_privatekey($private_key_contents);
    if (is_array($array_of_chunks) && count($array_of_chunks) > 0) {
      $errors = 0;
      $items = 0;
      unset($data);
      foreach($array_of_chunks as $echunk) {
        $ciphertext = base64_decode($echunk);
        if(openssl_private_decrypt($ciphertext, $plaintext, $privkey)) {
          $data[] = $plaintext;
          $items++;
        } else {
          $errors++;
        } // end if
      } // end foreach
      if ($items > 0 && $errors == 0) {
        $to_return = implode($data);
      }
    } // end if
    return $to_return;
  } // end decrypt_chunked_rsa

  ////////////////////////////////////////////////////////////////////////////////////
  // CodeGuard Backup Functions
  ////////////////////////////////////////////////////////////////////////////////////

  public function get_verified_and_decrypt_chunked_rsa($encoded_data, $private_key_contents) {
    $to_return = false;
    try {
      $chunked_rsa = $this->get_verified_string(base64_decode($encoded_data));
      if (false != $chunked_rsa) {
        if ($private_key_contents) {
          $to_return = $this->decrypt_chunked_rsa(json_decode($chunked_rsa), $private_key_contents);
        }
      } else {
        $to_return = false;
      }
    } catch (Exception $e) {
      $to_return = false;
    }
    return $to_return;
  } // end get_verified_and_decrypt_chunked_rsa


  public function get_verified_string($json_data_received) {
    $to_return = false;
    $received = json_decode($json_data_received);

    $pub_key = $this->codeguard_public_key_contents();

    if ($this->verify_with_rsa($received, $pub_key)) {
      $to_return = $received->data;
    } // end if
    return $to_return;
  } // end get_verified_string


  ////////////////////////////////////////////////////////////////////////////////////
  // CodeGuard File Backup
  ////////////////////////////////////////////////////////////////////////////////////

  public function get_file_list_stream($FILE_LIST_BUNDLE_SIZE = CodeGuard_Exporter::DEFAULT_FILE_LIST_BUNDLE_SIZE) {

    // Try to get the absolute path of the blog root
    try {
      $path = $this->get_wp_root_path();
      $path = realpath($path);
      if (substr($path, -1) !== DIRECTORY_SEPARATOR)
        $path .=  DIRECTORY_SEPARATOR;
    } catch (Exception $e) {
      return false;
    }

    $file_list = array(); 
    $start_at = time();
    $queue = array($path => 1);
    $done  = array();
    $index = 0;
    while(!empty($queue)) {
      // Pop the next element from the queue
      foreach($queue as $path => $unused) {
        unset($queue[$path]);
        $done[$path] = null;
        break;
      }
      unset($unused);

      $dh = @opendir($path);
      if (!$dh) continue;
      while(($filename = readdir($dh)) !== false) {
        if ($filename == '.' || $filename == '..')
          continue;

        $filename = $path . $filename;

        // Need to log
        if(realpath($filename) == false)
          continue;

        if (is_link($filename)) {
          $filename = realpath($filename);
          $file_list[] = $this->get_file_attributes( $filename );       
        } else if (is_dir($filename)) {
          if (substr($filename, -1) !== DIRECTORY_SEPARATORATOR)
            $filename .= DIRECTORY_SEPARATOR;

          // Skip if the item is already in the queue or has already been done
          if (array_key_exists($filename, $queue) || array_key_exists($filename, $done))
            continue;

          // Add directory to list
          $file_list[] = $this->get_file_attributes( realpath($filename) );       

          // Add dir to the queue
          $queue[$filename] = null;
        } else {
          // Add the file to the list
          $filename = realpath($filename);
          $file_list[] = $this->get_file_attributes( $filename );       
        }

        if (count($file_list) >= $FILE_LIST_BUNDLE_SIZE) {
          echo $this->bundle_and_seal_with_json($file_list) . "\n";
          $bundle_count++;
          unset($file_list);
        }
      }
      closedir($dh);
    }

    if (count($file_list) > 0) {
      echo $this->bundle_and_seal_with_json($file_list) . "\n";
      $bundle_count++;
      unset($file_list);
    }

    return array('comment' => true
      , 'bundles' => $bundle_count
      , 'runtime' => time() - $start_at);
  }

  public function get_file_attributes_with_sha1 ($filename) {
    $result = $this->get_file_attributes($filename);
    $result['sha1'] = sha1_file($filename);
    return $result;
  }

  private function get_file_attributes( $filename ) {

    $fs = filesize("$filename");
    $lm = filemtime("$filename");
    $perms = fileperms("$filename");
    $user = fileowner("$filename");
    $group = filegroup("$filename");
    $is_symlink = is_link("$filename");
    $is_dir = is_dir("$filename");

    $file_attributes = array(
      'path' => $filename
      ,'size' => $fs
      ,'perms' => $perms
      ,'user' => $user
      ,'group' => $group
      ,'symlink' => $is_symlink
      ,'dir' => $is_dir
      ,'mtime' => $lm
    );
    return $file_attributes;
  }

  private function get_wp_root_path() {
    if ( defined('ABSPATH') ) {
      return ABSPATH;
    } else {
      return null;
    }
  }

  public function get_file_data_in_chunks($file_name, $block_size = 2097152, $start_at = -1, $end_at = -1) {

    $start_at = time();

    // First check out the file to make su