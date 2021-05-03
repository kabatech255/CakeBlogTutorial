<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

/**
 * S3Client component
 */
class S3ClientComponent extends Component
{
  /**
   * Default configuration.
   *
   * @var array
   */
  protected $_defaultConfig = [];

  protected $defaultBucket;

  public function initialize(array $config)
  {
    $this->s3 = S3Client::factory([
      'credentials' => [
        'key' => env('AWS_S3_KEY', ''),
        'secret' => env('AWS_S3_SECRET', ''),
      ],
      'region' => env('AWS_S3_REGION', ''),
      'version' => 'latest',
    ]);

    $this->defaultBucket = env('AWS_S3_BUCKET', '');
  }

  /**
   * Get a list of files
   * @param string $bucketName
   * @param string $dir
   * @param int $getMax
   * @return array
   * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/class-Aws.S3.S3Client.html#_listObjects
   */
  public function getList($bucketName=null, $dir=null, $getMax=100)
  {
    try {
      if(!$bucketName) $bucketName = $this->defaultBucket;
      $listObj = $this->s3->listObjects([
        'Bucket' => $bucketName,
        'MaxKeys' => $getMax,
        'Prefix' => $dir
      ]);

      foreach ($listObj['Contents'] as $file) {
        if (mb_substr($file['Key'], -1) !== "/" && (!$dir  || ($dir && strpos($file['Key'], sprintf('%s/', $dir)) !== false))) {
          $result[] = $file['Key'];
        }
      }

      return $result;
    } catch (S3Exception $e) {
      echo $e->getMessage();
    }
  }

  /**
   * Uploading files
   * @param string $filePath
   * @param string $storePath
   * @param string $bucketName
   * @return mixed
   * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#putobject
   */
  public function putFile($filePath, $storePath, $bucketName=null)
  {
    try {
      if(!$bucketName) $bucketName = $this->defaultBucket;
      $result = $this->s3->putObject(array(
        'Bucket'       => $bucketName,
        'Key'          => $storePath,
        'SourceFile'   => $filePath,
      ));

      return $result;
    } catch (S3Exception $e) {
      echo $e->getMessage();
    }
  }

  /**
   * File download
   * @param string $s3FilePath
   * @param string $storeFilePath
   * @param string $bucketName
   * @return mixed
   * @see https://docs.aws.amazon.com/ja_jp/AmazonS3/latest/dev/RetrieveObjSingleOpPHP.html
   */
  public function getFile($s3FilePath, $storeFilePath, $bucketName=null)
  {
    try {
      if(!$bucketName) $bucketName = $this->defaultBucket;
      $result = $this->s3->getObject([
        'Bucket' => $bucketName,
        'Key'    => $s3FilePath,
        'SaveAs' => $storeFilePath
      ]);

      return $result;
    } catch (S3Exception $e) {
      echo $e->getMessage();
    }
  }

  /**
   * Directory download (Recursive download)
   * @param string $s3DirPath
   * @param string $localDirPath
   * @param string $bucketName
   */
  public function getDirectory($s3DirPath, $localDirPath, $bucketName=null)
  {
    try {
      if(!$bucketName) $bucketName = $this->defaultBucket;
      $fileList = $this->getList($bucketName, $s3DirPath);
      $this->chkDirHandle($fileList, $localDirPath);
      foreach ($fileList as $fromPath) {
        $toPath = sprintf('%s/%s', $localDirPath, $fromPath);
        $this->getFile($fromPath, $toPath);
      }
    } catch (S3Exception $e) {
      echo $e->getMessage();
    }
  }

  /**
   * Copying files
   * @param string $s3FilePath
   * @param string $s3CopyFilePath
   * @param string $bucketNameFrom
   * @param string $bucketNameTo
   * @return mixed
   * @see https://docs.aws.amazon.com/ja_jp/AmazonS3/latest/dev/CopyingObjectUsingPHP.html
   */
  public function copyFile($s3FilePath, $s3CopyFilePath, $bucketNameFrom=null, $bucketNameTo=null)
  {
    try {
      if(!$bucketNameFrom) $bucketNameFrom = $this->defaultBucket;
      if(!$bucketNameTo) $bucketNameTo = $this->defaultBucket;
      $result = $this->s3->copyObject(array(
        'Bucket'     => $bucketNameTo,
        'Key'        => $s3CopyFilePath,
        'CopySource' => sprintf('%s/%s', $bucketNameFrom, $s3FilePath),
      ));

      return $result;
    } catch (S3Exception $e) {
      echo $e->getMessage();
    }
  }

  /**
   * Copy directory (Recursive copy)
   * @param string $s3FromDir
   * @param string $s3ToDir
   * @param string $bucketNameFrom
   * @param string $bucketNameTo
   */
  public function copyDirectory($s3FromDir, $s3ToDir, $bucketNameFrom=null, $bucketNameTo=null)
  {
    try {
      if(!$bucketNameFrom) $bucketNameFrom = $this->defaultBucket;
      if(!$bucketNameTo) $bucketNameTo = $this->defaultBucket;
      $fileList = $this->getList($bucketNameFrom, $s3FromDir);

      foreach ($fileList as $fromPath) {
        $toPath = sprintf('%s/%s', $s3ToDir, basename($fromPath));
        $this->copyFile($fromPath, $toPath);
      }
    } catch (S3Exception $e) {
      echo $e->getMessage();
    }
  }

  /**
   * Moving files
   * @param $s3FromPath
   * @param $s3ToPath
   * @return bool|mixed
   */
  public function moveFile($s3FromPath, $s3ToPath)
  {
    try {
      $result = false;
      if($this->copyFile($s3FromPath, $s3ToPath)) {
        $result = $this->deleteFile($s3FromPath);
      }

      return $result;
    } catch (S3Exception $e) {
      echo $e->getMessage();
    }
  }

  /**
   * Move directory (Recursive movement)
   * @param string $s3FromDir
   * @param string $s3ToDir
   * @param string null $bucketName
   */
  public function moveDirectory($s3FromDir, $s3ToDir, $bucketName=null)
  {
    try {
      if(!$bucketName) $bucketName = $this->defaultBucket;
      $fileList = $this->getList($bucketName, $s3FromDir);
      foreach ($fileList as $fromPath) {
        $toPath = sprintf('%s/%s', $s3ToDir, basename($fromPath));
        $this->moveFile($fromPath, $toPath);
      }
    } catch (S3Exception $e) {
      echo $e->getMessage();
    }
  }

  /**
   * Delete files
   * @param string $filePath
   * @param string $bucketName
   * @return mixed
   * @see https://docs.aws.amazon.com/ja_jp/AmazonS3/latest/dev/DeletingMultipleObjectsUsingPHPSDK.html
   */
  public function deleteFile($filePath, $bucketName=null)
  {
    try {
      if(!$bucketName) $bucketName = $this->defaultBucket;
      $result = $this->s3->deleteObject(array(
        'Bucket' => $bucketName,
        'Key'    => $filePath
      ));

      return $result;
    } catch (S3Exception $e) {
      echo $e->getMessage();
    }
  }

  /**
   * Delete directory (Remove recursively)
   * @param string $dirName
   * @param string $bucketName
   * @return mixed
   * @see https://docs.aws.amazon.com/ja_jp/AmazonS3/latest/dev/DeletingMultipleObjectsUsingPHPSDK.html
   * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html#deleteobjects
   */
  public function deleteDirectory($dirName, $bucketName=null)
  {
    try {
      if(!$bucketName) $bucketName = $this->defaultBucket;
      $fileList = $this->getList($bucketName, $dirName);
      $files = $this->createArrayMultipleObjects($fileList);

      $result = $this->s3->deleteObjects(array(
        'Bucket'  => $bucketName,
        'Delete' => [
          'Objects' => $files
        ]
      ));

      return $result;
    } catch (S3Exception $e) {
      echo $e->getMessage();
    }
  }

  /**
   * Convert from file list to array for multiple objects
   * @param $fileList
   * @return array
   */
  private function createArrayMultipleObjects($fileList)
  {
    foreach ($fileList as $name) {
      $files[] = array('Key' => $name);
    }
    return $files;
  }

  /**
   * Recursively check the path directories in the file list
   * @param $fileList
   */
  private function chkDirHandle($fileList, $localDirPath)
  {
    foreach ($fileList as $filename) {
      $localPath = sprintf('%s/%s', $localDirPath, dirname($filename));
      $this->chkDir($localPath);
    }
  }
  /**
   * Confirm existence of directory and create it if it does not exist.
   * @param $localDirPath
   */
  private function chkDir($localDirPath)
  {
    if(!file_exists($localDirPath)) {
      mkdir($localDirPath, 0777, true);
    }
  }
}
