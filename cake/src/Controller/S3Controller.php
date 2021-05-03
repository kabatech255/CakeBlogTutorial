<?php

namespace App\Controller;

use App\Controller\AppController;

/**
 * S3 Controller
 *
 *
 * @method \App\Model\Entity\S3[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class S3Controller extends AppController
{
  protected $storagePath;
  protected $cloudRootDir;

  public function initialize()
  {
    parent::initialize();
    $this->loadComponent('S3Client');
    $this->autoRender = false;
    $this->storagePath = STORAGE_PATH;
    $this->cloudRootDir = 'tmp/';
  }

  public function getList()
  {
    $fileList = $this->S3Client->getList(null);
    print_r($fileList); exit;
  }

  /**
   * @param $fileName = 'users/1/thumbnail_1.png'(ex)
   * @return mixed
   */
  public function upload($fileName)
  {
    $fileLocalPath = sprintf('%s/%s', $this->storagePath, $fileName);
    $fileStorePath = sprintf('%s%s', $this->cloudRootDir, $fileName);

    return $this->S3Client->putFile($fileLocalPath, $fileStorePath);
  }

  /**
   * @param string $deletePath = 'users/1/thumbnail_1.png'(ex)
   * @return mixed
   */
  public function delete(string $deletePath)
  {
    return $this->S3Client->deleteFile(sprintf('%s%s', $this->cloudRootDir, $deletePath));
  }

  public function download()
  {
    $fileName = "test.png";
    $s3Dir = "";
    $storeDir = sprintf('%s/d', $this->storagePath);

    $s3FilePath = sprintf('%s%s', $s3Dir, $fileName);
    $storeFilePath = sprintf('%s/%s', $storeDir, $fileName);

    $fileObj = $this->S3Client->getFile($s3FilePath, $storeFilePath);
  }

  public function downloadDirectory()
  {
    $s3Dir = "cp";
    $localDir = "dl";
    $localDirPath = sprintf('%s/%s', $this->storagePath, $localDir);
    $this->S3Client->getDirectory($s3Dir, $localDirPath);
  }

  public function copy()
  {
    $fileName = "test.png";
    $s3Dir = "";
    $s3CopyDir = "cp/";

    $s3FilePath = sprintf('%s%s', $s3Dir, $fileName);
    $s3CopyFilePath = sprintf('%s%s', $s3CopyDir, $fileName);

    $this->S3Client->copyFile($s3FilePath, $s3CopyFilePath);
  }

  public function copyDirectory()
  {
    $s3FromDir = "cp";
    $s3ToDir = "cp_d";
    $this->S3Client->copyDirectory($s3FromDir, $s3ToDir);
  }

  public function move()
  {
    $fileName = "test.png";
    $s3FromDir = "cp/";
    $s3ToDir = "mv/";
    $s3FromPath = sprintf('%s%s', $s3FromDir, $fileName);
    $s3ToPath = sprintf('%s%s', $s3ToDir, $fileName);
    $this->S3Client->moveFile($s3FromPath, $s3ToPath);
  }

  public function moveDirectory()
  {
    $s3FromDir = "mv";
    $s3ToDir = "mv_d";
    $this->S3Client->moveDirectory($s3FromDir, $s3ToDir);
  }

  public function deleteDirectory()
  {
    $s3DirPath = 'cp';
    $this->S3Client->deleteDirectory($s3DirPath);
  }
}
